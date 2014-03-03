<?php

namespace unreal4u;

/**
 * Class that does several operations on strings to ensure data gets truncated properly
 *
 * @package stringOperations
 * @author Camilo Sperberg
 * @copyright 2010 - 2014 Camilo Sperberg
 * @version 0.4.0
 * @license BSD License
 */
class stringOperations {

    /**
     * The version of this class
     * @var string
     */
    private $classVersion = '0.4.0';

    /**
     * The maximum deviation a string is allowed to pass after the limit has been reached, in percentage
     * @var int
     */
    public $maximumDeviation = 10;

    /**
     * The charset we will be working with
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * Constructor
     *
     * @param string $charset The charset we will be using for all our operations. Defaults to "UTF-8"
     * @throws \Exception If mbstring extension is not installed, this will throw an exception
     * @throws \Exception If imap extension is not installed, this will throw an exception
     */
    public function __construct($charset='UTF-8') {
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding($charset);
            $this->charset = mb_internal_encoding();
        } else {
            throw new \Exception('mbstring extension must be installed');
        }

        if (!function_exists('imap_list')) {
            throw new \Exception('imap extension must be installed');
        }
    }

    /**
     * Returns a string with basic information of this class
     *
     * @return string
     */
    public function __toString() {
        return basename(__FILE__).' v'.$this->classVersion.' by Camilo Sperberg - http://unreal4u.com/';
    }

    /**
     * Gets the length of a string and if no delimiter is defined, it will set it until the end of what the string
     * should be
     *
     * @param string $string
     * @param array $delimiters One or more delimiters
     * @param int $limit
     * @return int
     */
    protected function _strpos($string, array $delimiters, $limit) {
        $candidates = array();
        foreach ($delimiters as $delimiter) {
            if (!empty($delimiter)) {
                $candidates[] = mb_strpos($string, $delimiter, $limit);
            }
        }
        if (empty($candidates)) {
            $candidates[] = $limit;
        }

        return $candidates;
    }

    /**
     * Gets the best possible candidate, most close to the maximum limit
     *
     * @param int $limit
     * @param float $maxCharacterLimit
     * @param array $candidates
     * @return int
     */
    protected function _getClosestOffset($limit, $maxCharacterLimit, array $candidates) {
        $return = $limit;
        foreach ($candidates as $candidate) {
            if ($candidate <= $maxCharacterLimit) {
                return $candidate;
            }
        }

        return $return;
    }

    /**
     * Gets the absolute maximum of characters that are allowed, or any number below that
     *
     * @param int $limit
     * @param array $number
     * @return int
     */
    protected function _getMaximumOffset($limit, array $candidates=array()) {
        // Absolute maximum number of characters
        $maxCharacterLimit = ceil(((100 + $this->maximumDeviation) * $limit) / 100);
        if (count($candidates) > 1) {
            $number = $this->_getClosestOffset($limit, $maxCharacterLimit, $candidates);
        } else {
            $number = reset($candidates);
        }

        if ($number !== false && $number < $maxCharacterLimit) {
            $maxCharacterLimit = $number;
        }

        return $maxCharacterLimit;
    }

    /**
     * Converts from one charset to another
     *
     * @TODO Test this properly
     *
     * @param string $from
     * @param string $to
     * @param string $text
     * @return string
     */
    protected function _convertCharset($from, $to, $text) {
        switch ($from) {
            case 'default':
                $from = $this->charset;
            break;
        }

        $return = $text;
        if ($from != $to) {
            $return = iconv($from, $to, $text);
        }

        return $return;
    }

    /**
     * Truncates text to a given length after delimiter
     *
     * This function will truncate a string to a given length, but only a few characters after that word whenever a
     * blank space is found. At that point, it will replace the remaining text with the given $append string
     *
     * @param string $string
     * @param int $limit Defaults to 150 characters
     * @param array $delimiters Defaults to a space
     * @param string $append Defaults to three dots
     */
    public function truncate($string, $limit=150, $delimiters=array(' '), $append='...') {
        $return = $string;
        $stringLength = mb_strlen($string);

        if ($stringLength > $limit) {
            if (is_string($delimiters)) {
                $delimiters = array($delimiters);
            }

            $until = $this->_getMaximumOffset($limit, $this->_strpos($string, $delimiters, $limit));
            $return = mb_substr($string, 0, $until);

            // Do not append if the resulting string is exactly the same as it came in
            if ($return !== $string) {
                $return .= $append;
            }
        }

        return $return;
    }

    /**
     * Decomposes a RFC5322 email address into an array with the 2 elements apart
     *
     * Can handle with unclean data. This function does NOT use the imap_rfc822_parse_adrlist() function because of some
     * problems while handling international email addresses within that function
     *
     * @param string $email
     * @return array Returns array('name' => 'XX', 'email' => 'YY');
     */
    public function decomposeCompleteEmail($email) {
        $return = array('name' => '', 'email' => '');
        $email = urldecode($email);
        if (is_string($email) && mb_strpos($email, '@') !== false) {
            $return['email'] = trim(str_replace(array('<', '>'), '', mb_substr($email, mb_strrpos($email, '<'))));
            $decomposedName  = trim(str_replace('"', '', mb_substr($email, 0, mb_strrpos($email, '<'))));

            if (mb_strpos($decomposedName, '=?') === 0) {
                $decodedHeader = \imap_mime_header_decode($decomposedName);
                if (!empty($decodedHeader[0]->text)) {
                    $entireName = '';
                    foreach ($decodedHeader as $namePart) {
                        $entireName .= trim($this->_convertCharset($namePart->charset, $this->charset, $namePart->text)).' ';
                    }
                    $decomposedName = trim($entireName);
                }
            }

            $return['name'] = $decomposedName;
        }

        return $return;
    }

    /**
     * Creates a slug from a string
     *
     * Although copied from another developer, this function has been rewritten to support leaving slashes intact. as an
     * optional function argument
     *
     * @author alix.axel@gmail.com - http://stackoverflow.com/questions/2103797/url-friendly-username-in-php/2103815#2103815
     * @author Camilo Sperberg
     *
     * @param string $string The string from which we want to create the slug from
     * @param boolean $convertSlash Whether slashes will be converted to hyphens. Defaults to true
     * @return string Returns the slug, ready to be used as an url, if string is not valid, will return an empty string
     */
    public function createSlug($string='', $convertSlash=true) {
        $return = '';
        // Only if we have a valid string and the same is not empty
        if ((is_string($string) || is_numeric($string)) && $string != '') {
            $string = str_ireplace('&amp;', '&', $string);

            // @TODO Change hard-coded UTF-8 to $this->charset (and test!)
            $return = strtolower(trim(preg_replace(
                    '~[^0-9a-z/]+~i',
                    '-',
                    html_entity_decode(preg_replace(
                            '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|th|tilde|uml);~i',
                            '$1',
                            htmlentities($string, ENT_QUOTES, 'UTF-8')),
                            ENT_QUOTES,
                            'UTF-8')),
                    '-')
            );

            // Do the intensive labor only if we have a string left to do something
            if ($return != '') {
                if ($convertSlash) {
                    // If we want to convert slashes to hyphens, a straightforward replace will do the job
                    $return = trim(preg_replace(array('[/]', '/-+/'), '-', $return), '-');
                } else {
                    // Check whether the original string ends with a slash
                    $endsWithSlash = false;
                    // At this point, it is save to use PHP's main functions because all multibyte strings will already be stripped out
                    if (strrpos($return, '/') == strlen($return) - 1) {
                        $endsWithSlash = true;
                    }
                    // Tear apart the string and whipe out some not-needed chars
                    $tmpReturn = explode('/', $return);
                    $return = '';
                    foreach($tmpReturn AS $stringPart) {
                        $return .= trim($stringPart, '-').'/';
                    }

                    // Finally, replace all extra slashes, including the now new end slash
                    $return = substr(preg_replace('/\/+/', '/', $return), 0, -1);

                    // Restore the last slash but only if it was present
                    if ($endsWithSlash) {
                        $return .= '/';
                    }
                }
            }
        }

        return $return;
    }
}
