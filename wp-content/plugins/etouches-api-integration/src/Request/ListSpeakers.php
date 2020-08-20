<?php

namespace AventriEventSync\Request;

class ListSpeakers extends AbstractApiRequest
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
     * @param int      $event_id
     * @param int|null $limit
     * @param int|null $offset
     */
    public function __construct($event_id, $limit = null, $offset = null)
    {
        $this->event_id = $event_id;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function get_api_action()
    {
        return 'ereg/listSpeakers';
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

        return $parameters;
    }
}
