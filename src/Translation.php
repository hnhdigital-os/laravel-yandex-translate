<?php

namespace Bluora\Yandex;

/**
 * Translation
 * @author Nikita Gusakov <dev@nkt.me>
 */
class Translation
{
    /**
     * @var string|array
     */
    protected $source;
    /**
     * @var string|array
     */
    protected $result;

    /**
     * @var array
     */
    protected $language;

    /**
     * Create new translation object.
     *
     * @param string|array $source   The source text
     * @param string|array $result   The translation result
     * @param string       $language Translation language
     * @return self
     */
    public function __construct($source, $result, $language)
    {
        $this->source = $source;
        $this->result = $result;
        $this->language = explode('-', $language);
    }

    /**
     * Get the original text.
     *
     * @return string|array The source text
     */
    public function getOriginal()
    {
        return $this->source;
    }

    /**
     * Get the translated text.
     *
     * @return array|string The result text
     */
    public function getTranslation()
    {
        return $this->result;
    }

    /**
     * Get the original text lanaguage.
     *
     * @return string The source language.
     */
    public function getOriginalLanguage()
    {
        return $this->language[0];
    }

    /**
     * Get the translated text's lanaguage.
     *
     * @return string The translated language.
     */
    public function getTranslationLanguage()
    {
        return $this->language[1];
    }

    /**
     * Return the translated text.
     *
     * @return string The translation text.
     */
    public function __toString()
    {
        if (is_array($this->result)) {
            return join(' ', $this->result);
        }

        return (string) $this->result;
    }
}
