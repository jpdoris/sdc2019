<?php

namespace AventriEventSync\Request;

class ImageRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $account_id;

    /**
     * @param int $id
     * @param int $account_id
     */
    public function __construct($id, $account_id)
    {
        $this->id = $id;
        $this->account_id = $account_id;
    }

    /**
     * {@inheritdoc}
     */
    public function get_url()
    {
        if ($this->url === null) {
            $this->set_url('image.php');
        }

        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function set_url($url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function get_method()
    {
        return self::METHOD_GET;
    }

    /**
     * {@inheritdoc}
     */
    public function get_query_parameters()
    {
        return [
            'acc' => $this->account_id,
            'id' => $this->id,
        ];
    }
}
