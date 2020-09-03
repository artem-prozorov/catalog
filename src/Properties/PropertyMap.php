<?php

declare(strict_types=1);

namespace Catalog\Core\Properties;

use Catalog\Core\Catalog;
use OutOfBoundsException;

class PropertyMap
{
    protected array $map = [];

    /**
     * Constructor
     *
     * @access	public
     * @param	array<string> $map
     * @return	void
     */
    public function __construct(array $map)
    {
        foreach ($map as $key => $value) {
            if (is_string($value)) {
                $this->map[$value] = null;
            } elseif (is_string($key) && $value instanceof Property) {
                $this->map[$key] = $value;
            }
        }
    }

    /**
     * Returns codes of properties
     *
     * @access	public
     * @return	array
     */
    public function getCodes(): array
    {
        return array_keys($this->map);
    }

    /**
     * Loads propertes with the provided codes
     * If parameter $codes is null, all properties are loaded
     *
     * @access	public
     * @param	array  	$codes      	Default: null
     * @param	boolean	$forceReload	Default: false
     * @return	void
     */
    public function load(array $codes = null, bool $forceReload = false)
    {
        if (empty($codes)) {
            $codes = $forceReload ? $this->getCodes() : $this->getUninitializedPropertyCodes();
        }

        $rows = Catalog::getRepositoryFactory()
            ->getRepository(Property::class)
            ->get(['code' => $codes]);

        foreach ($rows as $row) {
            $this->map[$row['code']] = new Property($row);
        }
    }

    /**
     * Returns a property with the requested code
     *
     * @access	public
     * @param	string	$code	
     * @return	Property
     */
    public function getProperty(string $code): Property
    {
        if (! array_key_exists($code, $this->map)) {
            throw new OutOfBoundsException('Unknown property code: ' . $code);
        }

        if (empty($this->map[$code])) {
            $this->load([$code]);
        }

        return $this->map[$code];
    }

    /**
     * Returns an array of property IDs
     *
     * @access	public
     * @param	array	$codes	Default: null
     * @return	array<int>
     */
    public function getPropertyIds(array $codes = null): array
    {
        $this->load($codes);

        $ids = [];

        $codes = ($codes === null) ? $this->getCodes() : $codes;

        foreach ($codes as $code) {
            if (! empty($this->map[$code])) {
                $ids[] = $this->map[$code]->getId();
            }
        }

        return $ids;
    }

    /**
     * Get Property by its ID. 
     * This method will trigger eager loading of all properties
     *
     * @access	public
     * @param	int	$id
     * @return	Property
     */
    public function getPropertyById(int $id): Property
    {
        $this->load();

        foreach ($this->map as $property) {
            if ($property->getId() === $id) {
                return $property;
            }
        }

        throw new OutOfBoundsException('No property with ID ' . $id);
    }

    /**
     * Returns an array of codes of properties that has not yet been initialized
     *
     * @access	public
     * @return	array
     */
    public function getUninitializedPropertyCodes(): array
    {
        $uninitialized = [];

        foreach ($this->map as $key => $value) {
            if (empty($value)) {
                $uninitialized[] = $key;
            }
        }

        return $uninitialized;
    }
}
