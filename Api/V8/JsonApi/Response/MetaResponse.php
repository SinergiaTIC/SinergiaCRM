<?php
namespace Api\V8\JsonApi\Response;

class MetaResponse implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $properties = [];

    /**
     * Meta object can contain any properties.
     *
     * @param array|\stdClass $properties
     *
     * @throws \InvalidArgumentException When bean is not found with the given id.
     */
    public function __construct($properties = [])
    {
        if (!is_array($properties) && !$properties instanceof \stdClass) {
            throw new \InvalidArgumentException('The properties must be an array or sdtClass');
        }

        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        return isset($this->properties[$name]) ? $this->properties[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * @inheritdoc
     */
    // STIC Custom 20250304 JBL - jsonSerialize() should either be compatible with JsonSerializable::jsonSerialize(): mixed
    // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
    // public function jsonSerialize()
    public function jsonSerialize(): mixed
    // END STIC Custom
    {
        return $this->properties;
    }
}
