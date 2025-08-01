<?php
namespace Api\V8\JsonApi\Response;

#[\AllowDynamicProperties]
class DocumentResponse implements \JsonSerializable
{
    /**
     * @var array|DataResponse|DataResponse[]
     */
    private $data = [];

    /**
     * @var MetaResponse
     */
    private $meta;

    /**
     * @var LinksResponse
     */
    private $links;

    /**
     * @return array|DataResponse|DataResponse[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array|DataResponse|DataResponse[] $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return MetaResponse
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param MetaResponse $meta
     */
    public function setMeta(MetaResponse $meta)
    {
        $this->meta = $meta;
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
            'data' => $this->getData()
        ];

        if (!$this->getData() && !$this->getMeta()) {
            $this->setMeta(new MetaResponse(['message' => 'Request was successful, but there is no result']));
        }

        if ($this->getMeta()) {
            $response = ['meta' => $this->getMeta()] + $response;
        }

        if ($this->getLinks()) {
            $response['links'] = $this->getLinks();
        }

        return $response;
    }
}
