<?php

namespace AventriEventSync\Request;

class AuthorizationRequest extends AbstractApiRequest
{
    /**
     * @var int
     */
    private $account_id;

    /**
     * @var string
     */
    private $api_key;

    /**
     * @param int    $account_id
     * @param string $api_key
     */
    public function __construct($account_id, $api_key)
    {
        $this->account_id = $account_id;
        $this->api_key = $api_key;
    }

    /**
     * {@inheritdoc}
     */
    public function get_api_action()
    {
        return 'global/authorize';
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
            'accountid' => $this->account_id,
            'key' => $this->api_key,
        ];
    }
}
