<?php

namespace AventriEventSync\Service;

class Synchronizer
{
    /**
     * @var SessionSync
     */
    private $session_sync;

    /**
     * @var SpeakerSync
     */
    private $speaker_sync;

    public function __construct()
    {
        $this->session_sync = new SessionSync();
        $this->speaker_sync = new SpeakerSync();
    }

    /**
     * Execute all synchronization tasks.
     *
     * @throws \AventriEventSync\Exception\ConnectionException
     * @throws \AventriEventSync\Exception\MissingAccessToken
     * @throws \AventriEventSync\Exception\RequestMethodNotImplemented
     */
    public function sync()
    {
        $this->session_sync->sync();
        $this->speaker_sync->sync();
    }
}
