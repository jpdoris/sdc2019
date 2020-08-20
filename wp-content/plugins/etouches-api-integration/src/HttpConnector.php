<?php

namespace AventriEventSync;

use AventriEventSync\Exception\ConnectionException;
use AventriEventSync\Exception\MissingAccessToken;
use AventriEventSync\Exception\RequestMethodNotImplemented;
use AventriEventSync\Request\AbstractApiRequest;
use AventriEventSync\Request\AuthorizationRequest;
use AventriEventSync\Request\ImageRequest;
use AventriEventSync\Request\RequestInterface;

class HttpConnector
{
    const BASE_URL = 'https://www.eiseverywhere.com';

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
     * @return int
     */
    public function get_account_id()
    {
        return $this->account_id;
    }

    /**
     * @param RequestInterface $request
     *
     * @return array
     * @throws RequestMethodNotImplemented
     * @throws ConnectionException
     * @throws MissingAccessToken
     */
    public function send_request(RequestInterface $request)
    {
        // Currently, only GET request method has been implemented
        // for the sake of simplicity.
        if ($request->get_method() !== RequestInterface::METHOD_GET) {
            throw new RequestMethodNotImplemented($request->get_method());
        }

        $is_authorization_request = $request instanceof AuthorizationRequest;
        $is_api_request = $request instanceof AbstractApiRequest;

        // Do authorization to fetch the access token from the API.
        // However, do not call the authorize method when it is an authorization request,
        // otherwise that would result in an infinite recursion.
        $access_token = null;

        if (!$is_authorization_request) {
            $access_token = $this->authorize();
        }

        if ($access_token !== null || $is_authorization_request) {
            $headers = [];

            // JSON headers
            if ($is_api_request) {
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';
            }

            // Build request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

            $request_url = self::BASE_URL . '/' . $request->get_url();

            $query_parameters = $request->get_query_parameters();

            // Add the access token to the query params,
            // only if it is not the authorization request.
            // The authorization request does not need an access token.
            if ($is_api_request && !$is_authorization_request) {
                $query_parameters['accesstoken'] = $access_token;
            }

            // Create the query string and attach it to the request URL.
            $query_string = http_build_query($query_parameters);
            if ($query_string) {
                $request_url .= '?' . $query_string;
            }

            curl_setopt($ch, CURLOPT_URL, $request_url);
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);

            $response = curl_exec($ch);
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_info = curl_getinfo($ch);

            curl_close($ch);

            if ($response !== false) {
                // The request is successful only if the HTTP code is 2xx.
                // Note that the response could contain an error,
                // despite the response being successful.
                // You must explicitly check the response in your implementation.
                if (strpos($response_code, '2') === 0) {
                    if ($request instanceof ImageRequest) {
                        return isset($curl_info['url']) && $curl_info['url']
                            ? [
                                'url' => $curl_info['url'],
                            ]
                            : [];
                    }

                    return json_decode($response, true);
                }

                throw new ConnectionException(
                    'Error: ' . $response_code . '. ' . $response
                );
            }

            throw new ConnectionException(
                'cURL Error: ' . curl_errno($ch) . '. ' . curl_error($ch)
            );
        }

        throw new MissingAccessToken('No access token was returned by the API');
    }

    /**
     * Authorizes the client and sets the access token.
     *
     * @return string|null
     * @throws ConnectionException
     * @throws RequestMethodNotImplemented
     * @throws MissingAccessToken
     */
    private function authorize()
    {
        $authorizationRequest = new AuthorizationRequest(
            $this->account_id,
            $this->api_key
        );

        $response = $this->send_request($authorizationRequest);

        // The access token is trimmed, as suggested by the documentation
        // Check this section: "Ok, I’ve URL encoded my access token but...".
        // @see https://developer.etouches.com/ehome/developer/faq/
        return isset($response['accesstoken'])
            ? trim($response['accesstoken'])
            : null;
    }
}
