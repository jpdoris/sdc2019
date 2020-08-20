<?php

namespace AventriEventSync\Request;

class GetSpeaker extends AbstractApiRequest
{
    /**
     * @var int
     */
    private $event_id;

    /**
     * @var int
     */
    private $speaker_id;

    /**
     * @param int $event_id
     * @param int $speaker_id
     */
    public function __construct($event_id, $speaker_id)
    {
        $this->event_id = $event_id;
        $this->speaker_id = $speaker_id;
    }

    /**
     * {@inheritdoc}
     */
    public function get_api_action()
    {
        return 'ereg/getSpeaker';
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
            'eventid' => $this->event_id,
            'speakerid' => $this->speaker_id,
        ];
    }
}
