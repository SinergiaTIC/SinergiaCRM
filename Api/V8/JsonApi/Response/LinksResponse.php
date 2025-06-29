<?php
namespace Api\V8\JsonApi\Response;

#[\AllowDynamicProperties]
class LinksResponse implements \JsonSerializable
{
    /**
     * @var string
     */
    private $self;

    /**
     * @var string|array
     */
    private $related;

    /**
     * @return string
     */
    public function getSelf()
    {
        return $this->self;
    }

    /**
     * @param string $self
     */
    public function setSelf($self)
    {
        $this->self = $self;
    }

    /**
     * @return array|string
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * @param array|string $related
     */
    public function setRelated($related)
    {
        $this->related = $related;
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
            'self' => $this->getSelf(),
            'related' => $this->getRelated()
        ];

        return array_filter($response);
    }
}
