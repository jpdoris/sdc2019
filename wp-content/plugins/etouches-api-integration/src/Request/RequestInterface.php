<?php

namespace AventriEventSync\Request;

interface RequestInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * Returns the request URL, relative to the base URL of the server endpoint.
     *
     * @return string
     */
    public function get_url();

    /**
     * @param string $url
     */
    public function set_url($url);

    /**
     * Returns a request method.
     * Must be one of the method constants, defined in this interface.
     *
     * @return string
     */
    public function get_method();

    /**
     * Returns an array of query parameters.
     * Their values are to be later converted in a query string.
     *
     * @return array
     */
    public function get_query_parameters();
}
