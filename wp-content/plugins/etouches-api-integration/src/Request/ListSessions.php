<?php

namespace AventriEventSync\Request;

class ListSessions extends AbstractApiRequest
{
    /**
     * @var int
     */
    private $event_id;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * @var int|null
     */
    private $offset;

    /**
     * @var array
     */
    private $fields;

    /**
     * @param int      $event_id
     * @param int|null $limit
     * @param int|null $offset
     * @param array    $fields
     */
    public function __construct($event_id, $limit = null, $offset = null, array $fields = [])
    {
        $this->event_id = $event_id;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->fields = array_merge([
            'sessiondate',
            'starttime',
            'endtime',
        ], $fields);
    }

    /**
     * {@inheritdoc}
     */
    public function get_api_action()
    {
        return 'ereg/listSessions';
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
        $parameters = [
            'eventid' => $this->event_id,
        ];

        if ($this->limit !== null) {
            $parameters['limit'] = $this->limit;
        }

        if ($this->offset !== null) {
            $parameters['offset'] = $this->offset;
        }

        if (!empty($this->fields)) {
            $parameters['fields'] = $this->fields;
        }

        return $parameters;
    }
}
