<?php

namespace AventriEventSync\Service;

use AventriEventSync\Aventri;
use AventriEventSync\Model\SessionDto;
use AventriEventSync\Repository\SessionRepository;

class SessionSync
{
    /**
     * @var SessionRepository
     */
    private $session_repository;

    public function __construct()
    {
        $this->session_repository = new SessionRepository();
    }

    /**
     * @throws \AventriEventSync\Exception\ConnectionException
     * @throws \AventriEventSync\Exception\MissingAccessToken
     * @throws \AventriEventSync\Exception\RequestMethodNotImplemented
     */
    public function sync()
    {
        $sessions = $this->session_repository->find_all();

        // Insert/Update all sessions, returned by the API.
        if (!empty($sessions)) {
            foreach ($sessions as $session) {
                $this->sync_session($session);
            }
        }

        // Find existing session posts in WordPress that no longer
        // exist in the sessions, returned by the API and delete them.
        $session_posts = get_posts([
            'post_type' => Aventri::POST_TYPE_SESSION,
            'posts_per_page' => -1,
        ]);

        if (!empty($session_posts)) {
            foreach ($session_posts as $session_post) {
                $is_deleted = true;
                $aventri_session_id = (int)get_post_meta($session_post->ID, 'aventri_session_id')[0];

                foreach ($sessions as $session) {
                    if ($session->get_id() === $aventri_session_id) {
                        $is_deleted = false;
                        break;
                    }
                }

                if ($is_deleted) {
                    wp_delete_post($session_post->ID, true);
                }
            }
        }
    }

    /**
     * Synchronize a single session by either inserting it into the
     * database or updating the already existing session.
     *
     * @param SessionDto $session
     */
    private function sync_session(SessionDto $session)
    {
        $post_data = [
            'post_type' => Aventri::POST_TYPE_SESSION,
            'post_title' => $session->get_title(),
            'post_content' => $session->get_description(),
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'meta_input' => [
                'aventri_session_id' => $session->get_id(),
            ],
        ];

        $date = $session->get_date();
        if ($date !== null) {
            $post_data['meta_input']['date'] = $date->format('Y-m-d');
        }

        $starts_at = $session->get_starts_at();
        if ($starts_at !== null) {
            $post_data['meta_input']['starts_at'] = $starts_at->format('Y-m-d H:i:s');
        }

        $ends_at = $session->get_ends_at();
        if ($ends_at !== null) {
            $post_data['meta_input']['ends_at'] = $ends_at->format('Y-m-d H:i:s');
        }

        foreach ($session->get_attributes() as $attribute) {
            $post_data['meta_input'][$attribute->get_key()] = $attribute->get_value();
        }

        $session_posts = get_posts([
            'post_type' => Aventri::POST_TYPE_SESSION,
            'meta_query' => [
                [
                    'key' => 'aventri_session_id',
                    'value' => $session->get_id(),
                    'compare' => '=',
                ],
            ],
            'posts_per_page' => -1,
        ]);

        if (isset($session_posts[0])) {
            $session_post = $session_posts[0];
            $post_data['ID'] = $session_post->ID;
            wp_update_post($post_data);

            // Delete old meta data from session
            $meta_data = get_post_meta($session_post->ID);

            if (!empty($meta_data)) {
                foreach ($meta_data as $key => $meta_datum) {
                    if (!array_key_exists($key, $post_data['meta_input'])) {
                        delete_post_meta($session_post->ID, $key);
                    }
                }
            }
        } else {
            wp_insert_post($post_data);
        }
    }
}
