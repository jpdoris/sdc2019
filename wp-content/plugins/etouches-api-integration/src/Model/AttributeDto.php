<?php

namespace AventriEventSync\Model;

class AttributeDto implements ModelInterface
{
    const ATTRIBUTE_DELIMITER = '---';
    const KEY_VALUE_DELIMITER = '->';

    /**
     * @param string $string
     *
     * @return self[]
     */
    public static function many_from_string($string)
    {
        $attributes = [];

        if ($string) {
            $parameters = explode(self::ATTRIBUTE_DELIMITER, $string);

            array_shift($parameters);
            array_pop($parameters);

            foreach ($parameters as $parameter) {
                list($key, $value) = array_map(
                    'trim',
                    explode(self::KEY_VALUE_DELIMITER, $parameter)
                );

                // Make the key look like a variable.
                // @example 'Topic'             becomes 'topic'
                //          'Main speaker'      becomes 'main_speaker'
                //          'Speaker-assistant' becomes 'speaker_assistant'
                $key = str_replace([' ', '-'], '_', strtolower($key));

                // Assign the value to the key-variable
                if ($key && $value) {
                    $attributes[] = new self(
                        $key,
                        $value
                    );
                }
            }
        }

        return $attributes;
    }

    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function get_key()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function get_value()
    {
        return $this->value;
    }
}
