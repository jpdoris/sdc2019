<?php

namespace AventriEventSync\Service;

use AventriEventSync\Aventri;
use AventriEventSync\Model\SpeakerDto;
use AventriEventSync\Repository\SpeakerRepository;

class SpeakerSync
{
    /**
     * @var SpeakerRepository
     */
    private $speaker_repository;

    public function __construct()
    {
        $this->speaker_repository = new SpeakerRepository();
    }

    /**
     * @throws \AventriEventSync\Exception\ConnectionException
     * @throws \AventriEventSync\Exception\MissingAccessToken
     * @throws \AventriEventSync\Exception\RequestMethodNotImplemented
     */
    public function sync()
    {
        $speakers = $this->speaker_repository->find_all();

        // Insert/Update all speakers, returned by the API.
        if (!empty($speakers)) {
            foreach ($speakers as $speaker) {
                $this->sync_speaker($speaker);
            }
        }

        // Find existing speaker posts in WordPress that no longer
        // exist in the speakers, returned by the API and delete them.
        $speaker_posts = get_posts([
            'post_type' => Aventri::POST_TYPE_SPEAKER,
            'posts_per_page' => -1,
        ]);

        if (!empty($speaker_posts)) {
            foreach ($speaker_posts as $speaker_post) {
                $is_deleted = true;
                $aventri_speaker_id = (int)get_post_meta($speaker_post->ID, 'aventri_speaker_id')[0];

                foreach ($speakers as $speaker) {
                    if ($speaker->get_id() === $aventri_speaker_id) {
                        $is_deleted = false;
                        break;
                    }
                }

                if ($is_deleted) {
                    wp_delete_post($speaker_post->ID, true);
                }
            }
        }
    }

    /**
     * Synchronize a single speaker by either inserting him/her into the
     * database or updating the already existing speaker.
     *
     * @param SpeakerDto $speaker
     */
    private function sync_speaker(SpeakerDto $speaker)
    {
        $post_data = [
            'post_type' => Aventri::POST_TYPE_SPEAKER,
            'post_title' => $speaker->get_name(),
            'post_content' => $speaker->get_bio(),
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'meta_input' => [
                'aventri_speaker_id' => $speaker->get_id(),
            ],
        ];

        $first_name = $speaker->get_first_name();
        if ($first_name !== null) {
            $post_data['meta_input']['first_name'] = $first_name;
        }

        $middle_name = $speaker->get_middle_name();
        if ($middle_name !== null) {
            $post_data['meta_input']['middle_name'] = $middle_name;
        }

        $last_name = $speaker->get_last_name();
        if ($last_name !== null) {
            $post_data['meta_input']['last_name'] = $last_name;
        }

        $email = $speaker->get_email();
        if ($email !== null) {
            $post_data['meta_input']['email'] = $email;
        }

        $title = $speaker->get_title();
        if ($title !== null) {
            $post_data['meta_input']['title'] = $title;
        }

        $company = $speaker->get_company();
        if ($company !== null) {
            $post_data['meta_input']['company'] = $company;
        }

        $image = $speaker->get_image();
        if ($image !== null) {
            $post_data['meta_input']['image'] = $image;
        }

        foreach ($speaker->get_attributes() as $attribute) {
            $post_data['meta_input'][$attribute->get_key()] = $attribute->get_value();
        }

        $speaker_posts = get_posts([
            'post_type' => Aventri::POST_TYPE_SPEAKER,
            'meta_query' => [
                [
                    'key' => 'aventri_speaker_id',
                    'value' => $speaker->get_id(),
                    'compare' => '=',
                ],
            ],
            'posts_per_page' => -1,
        ]);

        if (isset($speaker_posts[0])) {
            $speaker_post = $speaker_posts[0];
            $post_data['ID'] = $speaker_post->ID;
            wp_update_post($post_data);

            // Delete old meta data from speaker
            $meta_data = get_post_meta($speaker_post->ID);

            if (!empty($meta_data)) {
                foreach ($meta_data as $key => $meta_datum) {
                    if (!array_key_exists($key, $post_data['meta_input'])) {
                        delete_post_meta($speaker_post->ID, $key);
                    }
                }
            }
        } else {
            wp_insert_post($post_data);
        }
    }
}
