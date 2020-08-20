<?php

namespace AventriEventSync\Request;

class GetSession extends AbstractApiRequest
{
    /**
     * @var int
     */
    private $event_id;

    /**
     * @var int
     */
    private $session_id;

    /**
     * @var string
     */
    private $session_key;

    /**
     * @var bool|null
     */
    private $show_hidden;

    /**
     * @param int       $event_id
     * @param int       $session_id
     * @param string    $session_key
     * @param bool|null $show_hidden
     */
    public function __construct($event_id, $session_id, $session_key, $show_hidden = null)
    {
        $this->event_id = $event_id;
        $this->session_id = $session_id;
        $this->session_key = $session_key;
        $this->show_hidden = $show_hidden;
    }

    /**
     * {@inheritdoc}
     */
    public function get_api_action()
    {
        return 'ereg/getSession';
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
            'sessionid' => $this->session_id,
            'sessionkey' => $this->session_key,
        ];

        if ($this->show_hidden !== null) {
            $parameters['showhidden'] = $this->show_hidden ? 1 : 0;
        }

        return $parameters;
    }
}
