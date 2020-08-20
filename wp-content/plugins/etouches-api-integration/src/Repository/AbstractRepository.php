<?php

namespace AventriEventSync\Repository;

use AventriEventSync\HttpConnector;
use AventriEventSync\Aventri;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var HttpConnector
     */
    protected $http_connector;

    /**
     * @var int
     */
    protected $event_id;

    public function __construct()
    {
        $plugin_options = get_option(Aventri::KEY);

        $account_id = $plugin_options['account_id'] ?? null;
        $api_key = $plugin_options['api_key'] ?? null;
        $event_id = $plugin_options['event_id'] ?? null;

        if ($account_id && $api_key && $event_id) {
            $this->event_id = (int)$event_id;
            $this->http_connector = new HttpConnector(
                (int)$account_id,
                $api_key
            );
        }
    }
}
