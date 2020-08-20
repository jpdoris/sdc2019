<?php

namespace AventriEventSync\Request;

abstract class AbstractApiRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * Returns the API action, prefixed with its namespace.
     *
     * @example 'global/authorize'
     *          'ereg/getEvent'
     *
     * @return string
     */
    abstract public function get_api_action();

    /**
     * {@inheritdoc}
     */
    public function get_url()
    {
        if ($this->url === null) {
            $this->set_url('api/v2/' . $this->get_api_action() . '.json');
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
}
