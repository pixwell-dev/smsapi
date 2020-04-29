<?php

namespace NotificationChannels\Smsapi;

abstract class SmsapiMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * @var bool
     */
    public $test;

    /**
     * @var array
     */
    private $filledParams = [];

    /**
     * Create a message object.
     *
     * @param string $content
     *
     * @return static
     */
    public static function create(string $content = ''): self
    {
        return new static($content);
    }

    /**
     * Create a new message instance.
     *
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function fill(array $options): self
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
                $this->filledParams[] = $key;
            }
        }

        return $this;
    }

    public function getFilledParams(): array
    {
        return $this->filledParams;
    }
}
