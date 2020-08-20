<?php

namespace AventriEventSync\Repository;

use AventriEventSync\Exception\MissingArgument;
use AventriEventSync\Model\SessionDto;
use AventriEventSync\Request\GetSession;
use AventriEventSync\Request\ListSessions;

class SessionRepository extends AbstractRepository
{
    /**
     * @return SessionDto[]
     * @throws \AventriEventSync\Exception\ConnectionException
     * @throws \AventriEventSync\Exception\MissingAccessToken
     * @throws \AventriEventSync\Exception\RequestMethodNotImplemented
     */
    public function find_all()
    {
        $sessions = [];

        $aventri_sessions = $this->http_connector->send_request(
            new ListSessions($this->event_id)
        );

        foreach ($aventri_sessions as $aventri_session) {
            $aventri_session = $this->http_connector->send_request(
                new GetSession(
                    $this->event_id,
                    (int)$aventri_session['sessionid'],
                    $aventri_session['sessionkey']
                )
            );

            try {
                $sessions[] = SessionDto::from_aventri_session($aventri_session);
            } catch (MissingArgument $e) {
                // Invalid sessions are skipped.
                continue;
            }
        }

        return $sessions;
    }
}
