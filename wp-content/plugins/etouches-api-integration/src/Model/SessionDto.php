<?php

namespace AventriEventSync\Model;

use AventriEventSync\Exception\MissingArgument;

class SessionDto implements ModelInterface
{
    /**
     * @param array $aventri_session
     *
     * @return self
     * @throws MissingArgument
     */
    public static function from_aventri_session(array $aventri_session)
    {
        if (!isset($aventri_session['sessionid'])) {
            throw new MissingArgument('id', __CLASS__);
        }

        $raw_description = $aventri_session['additional_details']['desceng'] ?? '';

        // Generate attributes from description
        $attributes = AttributeDto::many_from_string($raw_description);

        // Remove the attributes string from the description
        $description_parts = explode(AttributeDto::ATTRIBUTE_DELIMITER, $raw_description);
        $description = array_shift($description_parts);
        $description .= array_pop($description_parts);
        $description = trim(preg_replace('/<div style="display: none;">|<\/div>/','', $description));

        $date = isset($aventri_session['sessiondate'])
            ? new \DateTime($aventri_session['sessiondate'])
            : null;

        $starts_at = null;
        if ($date !== null && isset($aventri_session['starttime'])) {
            $starts_at = new \DateTime(
                $date->format('Y-m-d')
                . ' '
                . $aventri_session['starttime']
            );
        }

        $ends_at = null;
        if ($date !== null && isset($aventri_session['endtime'])) {
            $ends_at = new \DateTime(
                $date->format('Y-m-d')
                . ' '
                . $aventri_session['endtime']
            );
        }

        $speakers = $aventri_session['speakers'];

        if (!empty($speakers)) {
            $speaker_ids = array_map(function ($speaker) {
                return (int)$speaker['speakerid'];
            }, $speakers);

            $attributes[] = new AttributeDto(
                'aventri_speaker_ids',
                json_encode($speaker_ids)
            );
        }

        return new self(
            (int)$aventri_session['sessionid'],
            $aventri_session['sessionkey'] ?? '',
            $aventri_session['reportname'] ?? '',
            $description,
            $date,
            $starts_at,
            $ends_at,
            $attributes
        );
    }

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime|null
     */
    private $date;

    /**
     * @var \DateTime|null
     */
    private $starts_at;

    /**
     * @var \DateTime|null
     */
    private $ends_at;

    /**
     * @var AttributeDto[]
     */
    private $attributes;

    /**
     * @param int            $id
     * @param string         $key
     * @param string         $title
     * @param string         $description
     * @param \DateTime      $date
     * @param \DateTime      $starts_at
     * @param \DateTime      $ends_at
     * @param AttributeDto[] $attributes
     */
    public function __construct(
        $id,
        $key,
        $title,
        $description,
        $date,
        $starts_at,
        $ends_at,
        array $attributes
    ) {
        $this->id = $id;
        $this->key = $key;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->starts_at = $starts_at;
        $this->ends_at = $ends_at;
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
     * @return string
     */
    public function get_key()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function get_description()
    {
        return $this->description;
    }

    /**
     * @return \DateTime|null
     */
    public function get_date()
    {
        return $this->date;
    }

    /**
     * @return \DateTime|null
     */
    public function get_starts_at()
    {
        return $this->starts_at;
    }

    /**
     * @return \DateTime|null
     */
    public function get_ends_at()
    {
        return $this->ends_at;
    }

    /**
     * @return AttributeDto[]
     */
    public function get_attributes()
    {
        return $this->attributes;
    }
}
