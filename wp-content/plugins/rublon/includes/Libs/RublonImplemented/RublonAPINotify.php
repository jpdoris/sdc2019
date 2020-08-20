<?php

namespace Rublon_WordPress\Libs\RublonImplemented;

use Rublon\Core\Api\RublonAPIClient;
use Rublon\Core\RublonAuthParams;
use Rublon\Core\RublonConsumer;

/**
 * API request: Notify Rublon about errors
 *
 */
class RublonAPINotify extends RublonAPIClient
{
    /**
     * Logged in user email
     */
    const FIELD_DATA = 'data';

    /**
     * Logged in user id
     */
    const FIELD_USER_ID = 'appUserId';

    /**
     * URL path of the request.
     *
     * @var string
     */
    protected $urlPath = '/api/sdk/notify';

    /**
     * RublonAPINotify constructor.
     *
     * @param RublonConsumer $rublon
     * @param $userId
     * @param $data
     */
    public function __construct(RublonConsumer $rublon, $userId, $data)
    {

        parent::__construct($rublon);

        if (!$rublon->isConfigured()) {
            return;
        }
        // Set request URL and parameters
        $url = $rublon->getAPIDomain() . $this->urlPath;
        $params = array(
            RublonAuthParams::FIELD_SYSTEM_TOKEN => $rublon->getSystemToken(),
            self::FIELD_USER_ID => $userId,
            self::FIELD_DATA => $data
        );
        $this->setRequestURL($url)->setRequestParams($params);
    }
}
