<?php

namespace Bluora\Yandex;

use Illuminate\Support\Facades\Config;

/**
 * Translate
 * @author Nikita Gusakov <dev@nkt.me>
 * @link   http://api.yandex.com/translate/doc/dg/reference/translate.xml
 */
class Translate
{
    const BASE_URL = 'https://translate.yandex.net/api/v1.5/tr.json/';
    const MESSAGE_UNKNOWN_ERROR = 'Unknown error';
    const MESSAGE_API_KEY_MISSING = 'API key is missing';
    const MESSAGE_JSON_ERROR = 'JSON parse error';
    const MESSAGE_INVALID_RESPONSE = 'Invalid response from service';

    /**
     * @var string
     */
    protected $key;

    /**
     * @var resource
     */
    protected $handler;

    /**
     * Create new translation object.
     *
     * @link http://api.yandex.com/key/keyslist.xml Get a free API key on this page.
     *
     * @param  $key API key to override config.
     * @throws Exception
     * @return self
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct($key = false)
    {
        $this->key = ($key === false) ? Config::get('services.yandex-translate.key', false) : $key;
        if ($this->key === false) {
            throw new Exception(MESSAGE_API_KEY_MISSING, -1);
        }
        $this->handler = curl_init();
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Returns a list of translation directions supported by the service.
     *
     * @link http://api.yandex.com/translate/doc/dg/reference/getLangs.xml
     *
     * @param string $culture If set, the service's response will contain a list of language codes
     * @return array
     */
    public function getSupportedLanguages($culture = null)
    {
        return $this->execute('getLangs', [
            'ui' => $culture
        ]);
    }

    /**
     * Detects the language of the specified text.
     *
     * @link http://api.yandex.com/translate/doc/dg/reference/detect.xml
     *
     * @param string $text The text to detect the language for.
     * @return string
     */
    public function detect($text)
    {
        $data = $this->execute('detect', [
            'text' => $text
        ]);

        return $data['lang'];
    }

    /**
     * Translates the text.
     *
     * @link http://api.yandex.com/translate/doc/dg/reference/translate.xml
     *
     * @param string|array $text     The text to be translated.
     * @param string       $language Translation direction (for example, "en-ru" or "ru").
     * @param bool         $html     Text format, if true - html, otherwise plain.
     * @param int          $options  Translation options.
     * @return array
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function translate($text, $from_language, $to_language, $html = false, $options = 0)
    {
        $lang = (empty($from_language)) ? $to_language : $from_language.'-'.$to_language;

        // Remove any string based keys
        if (is_array($original_text = $text)) {
            $text = array_values($text);
        }

        $data = $this->execute('translate', [
            'text'    => $text,
            'lang'    => $lang,
            'format'  => $html ? 'html' : 'plain',
            'options' => $options
        ]);

        if (!is_array($text)) {
            $data['text'] = array_shift($data['text']);
        }

        return new Translation($original_text, $data['text'], $data['lang']);
    }

    /**
     * Execute the translation request.
     *
     * @param string $uri
     * @param array  $parameters
     * @throws Exception
     * @return array
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function execute($uri, array $parameters)
    {
        $parameters['key'] = $this->key;
        curl_setopt($this->handler, CURLOPT_URL, static::BASE_URL . $uri);
        curl_setopt($this->handler, CURLOPT_POST, true);

        $post = http_build_query($parameters);

        if (isset($parameters['text']) && is_array($parameters['text'])) {
            $post = preg_replace('#(^|&)text%5B[0-9]+%5D=#', '$1text=', $post);
        }

        curl_setopt($this->handler, CURLOPT_POSTFIELDS, $post);

        $remote_result = curl_exec($this->handler);
        if ($remote_result === false) {
            throw new Exception(curl_error($this->handler), curl_errno($this->handler));
        }

        $result = json_decode($remote_result, true);
        if (!$result) {
            $error_message = self::MESSAGE_UNKNOWN_ERROR;
            if (version_compare(PHP_VERSION, '5.3', '>=')) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $error_message = self::MESSAGE_JSON_ERROR;
                    if (version_compare(PHP_VERSION, '5.5', '>=')) {
                        $error_message = json_last_error_msg();
                    }
                }
            }
            throw new Exception(sprintf('%s: %s', self::MESSAGE_INVALID_RESPONSE, $error_message));
        } elseif (isset($result['code']) && $result['code'] > 200) {
            throw new Exception($result['message'], $result['code']);
        }

        return $result;
    }
}
