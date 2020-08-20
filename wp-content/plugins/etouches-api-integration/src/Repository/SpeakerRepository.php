<?php

namespace AventriEventSync\Repository;

use AventriEventSync\Exception\MissingArgument;
use AventriEventSync\Model\SpeakerDto;
use AventriEventSync\Request\GetSpeaker;
use AventriEventSync\Request\ListSpeakers;

class SpeakerRepository extends AbstractRepository
{
    /**
     * @return SpeakerDto[]
     * @throws \AventriEventSync\Exception\ConnectionException
     * @throws \AventriEventSync\Exception\MissingAccessToken
     * @throws \AventriEventSync\Exception\RequestMethodNotImplemented
     */
    public function find_all()
    {
        $speakers = [];

        $aventri_speakers = $this->http_connector->send_request(
            new ListSpeakers($this->event_id)
        );

        foreach ($aventri_speakers as $aventri_speaker) {
            $aventri_speaker = $this->http_connector->send_request(
                new GetSpeaker(
                    $this->event_id,
                    (int)$aventri_speaker['speakerid']
                )
            );

            try {
                $speakers[] = SpeakerDto::from_aventri_speaker(
                    $aventri_speaker,
                    $this->http_connector
                );
            } catch (MissingArgument $e) {
                // Invalid speakers are skipped.
                continue;
            }
        }

        return $speakers;
    }
}
