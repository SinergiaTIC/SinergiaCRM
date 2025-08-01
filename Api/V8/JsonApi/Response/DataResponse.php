<?php
namespace Api\V8\JsonApi\Response;

#[\AllowDynamicProperties]
class DataResponse implements \JsonSerializable
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $id;

    /**
     * @var AttributeResponse
     */
    private $attributes;

    /**
     * @var RelationshipResponse
     */
    private $relationships;

    /**
     * @var LinksResponse
     */
    private $links;

    /**
     * @param string $type
     * @param string $id
     */
    public function __construct($type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return AttributeResponse
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param AttributeResponse $attributes
     */
    public function setAttributes(AttributeResponse $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return RelationshipResponse
     */
    public function getRelationships()
    {
        return $this->relationships;
    }

    /**
     * @param RelationshipResponse $relationships
     */
    public function setRelationships(RelationshipResponse $relationships)
    {
        $this->relationships = $relationships;
    }

    /**
     * @return LinksResponse
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param LinksResponse $links
     */
    public function setLinks(LinksResponse $links)
    {
        $this->links = $links;
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
        $response = [
            'type' => $this->getType(),
            'id' => $this->getId(),
            'attributes' => $this->getAttributes(),
            'relationships' => $this->getRelationships(),
            'links' => $this->getLinks()
        ];

        return array_filter($response);
    }
}
