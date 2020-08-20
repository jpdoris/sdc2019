<?php

namespace AventriEventSync\Model;

use AventriEventSync\Exception\MissingArgument;
use AventriEventSync\HttpConnector;
use AventriEventSync\Request\ImageRequest;

class SpeakerDto implements ModelInterface
{
    /**
     * @param array         $aventri_speaker
     * @param HttpConnector $http_connector
     *
     * @return self
     * @throws MissingArgument
     * @throws \AventriEventSync\Exception\ConnectionException
     * @throws \AventriEventSync\Exception\MissingAccessToken
     * @throws \AventriEventSync\Exception\RequestMethodNotImplemented
     */
    public static function from_aventri_speaker(
        array $aventri_speaker,
        HttpConnector $http_connector
    ) {
        if (!isset($aventri_speaker['speakerid'])) {
            throw new MissingArgument('id', __CLASS__);
        }

        $raw_bio = $aventri_speaker['bio'] ?? '';

        // Generate attributes from biography
        $attributes = AttributeDto::many_from_string($raw_bio);

        // Remove the attributes string from the biography
        $bio_parts = explode(AttributeDto::ATTRIBUTE_DELIMITER, $raw_bio);
        $bio = array_shift($bio_parts);
        $bio .= array_pop($bio_parts);

        $image = null;
        if (isset($aventri_speaker['image']) && $aventri_speaker['image']) {
            $image_id = $aventri_speaker['image'];
            $image_response = $http_connector->send_request(
                new ImageRequest(
                    $image_id,
                    $http_connector->get_account_id()
                )
            );

            if (isset($image_response['url']) && $image_response['url']) {
                $image = $image_response['url'];
            }
        }

        $sessions = $aventri_speaker['sessions'];

        if (!empty($sessions)) {
            $session_ids = array_map(
                function ($session) {
                    return (int)$session['sessionid'];
                },
                array_filter($sessions, function ($session) {
                    return $session['sessionid'] !== null;
                })
            );

            $attributes[] = new AttributeDto(
                'aventri_session_ids',
                json_encode($session_ids)
            );
        }

        return new self(
            (int)$aventri_speaker['speakerid'],
            isset($aventri_speaker['fname']) && $aventri_speaker['fname']
                ? $aventri_speaker['fname']
                : null,
            isset($aventri_speaker['mname']) && $aventri_speaker['mname']
                ? $aventri_speaker['mname']
                : null,
            isset($aventri_speaker['lname']) && $aventri_speaker['lname']
                ? $aventri_speaker['lname']
                : null,
            isset($aventri_speaker['email']) && $aventri_speaker['email']
                ? $aventri_speaker['email']
                : null,
            isset($aventri_speaker['title']) && $aventri_speaker['title']
                ? $aventri_speaker['title']
                : null,
            isset($aventri_speaker['company']) && $aventri_speaker['company']
                ? $aventri_speaker['company']
                : null,
            $image,
            $bio,
            $attributes
        );
    }

    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $first_name;

    /**
     * @var string|null
     */
    private $middle_name;

    /**
     * @var string|null
     */
    private $last_name;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $company;

    /**
     * @var string|null
     */
    private $image;

    /**
     * @var string
     */
    private $bio;

    /**
     * @var AttributeDto[]
     */
    private $attributes;

    /**
     * @param int            $id
     * @param string|null    $first_name
     * @param string|null    $middle_name
     * @param string|null    $last_name
     * @param string|null    $email
     * @param string|null    $title
     * @param string|null    $company
     * @param string|null    $image
     * @param string         $bio
     * @param AttributeDto[] $attributes
     */
    public function __construct(
        $id,
        $first_name,
        $middle_name,
        $last_name,
        $email,
        $title,
        $company,
        $image,
        $bio,
        $attributes
    ) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->title = $title;
        $this->company = $company;
        $this->image = $image;
        $this->bio = $bio;
        $this->attributes = $attributes;
    }

    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function get_first_name()
    {
        return $this->first_name;
    }

    /**
     * @return string|null
     */
    public function get_middle_name()
    {
        return $this->middle_name;
    }

    /**
     * @return string|null
     */
    public function get_last_name()
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        $name = '';

        $first_name = $this->first_name;
        $last_name = $this->last_name;

        if ($first_name !== null) {
            $name = $first_name;
        }

        if ($first_name !== null && $last_name !== null) {
            $name .= ' ';
        }

        if ($last_name !== null) {
            $name .= $last_name;
        }

        return $name;
    }

    /**
     * @return string|null
     */
    public function get_email()
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function get_company()
    {
        return $this->company;
    }

    /**
     * @return string|null
     */
    public function get_image()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function get_bio()
    {
        return $this->bio;
    }

    /**
     * @return AttributeDto[]
     */
    public function get_attributes()
    {
        return $this->attributes;
    }
}
