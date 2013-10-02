<?php

namespace u4u;

/**
 * Class that does several operations on strings to ensure data gets truncated properly
 *
 * @author unreal4u
 */
class stringOperations {
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
     * Gets the length of a string and if no delimiter is defined, it will set it until the end of what the string
     * should be
     *
     * @param string $string
     * @param string $delimiter
     * @param int $limit
     * @return int
     */
    protected function _strpos($string, $delimiter, $limit) {
        $return = $limit;
        if ($delimiter !== '') {
            $return = mb_strpos($string, $delimiter, $limit);
        }

        return $return;
    }

    /**
     * Gets the absolute maximum of characters that are allowed, or any number below that
     *
     * @param int $limit
     * @param int $number
     * @return int
     */
    protected function _getMaximumOffset($limit, $number=0) {
        // Absolute maximum number of characters
        $maxCharacterLimit = ceil(((100 + $this->maximumDeviation) * $limit) / 100);
        if ($number !== false && $number < $maxCharacterLimit) {
            $maxCharacterLimit = $number;
        }

        return $maxCharacterLimit;
    }

    /**
     * Truncates text to a given length after delimiter
     *
     * This function will truncate a string to a given length, but only a few characters after that word whenever a
     * blank space is found. At that point, it will replace the remaining text with the given $append string
     *
     * @param string $string
     * @param int $limit Defaults to 150 characters
     * @param string $delimiter Defaults to a space
     * @param string $append Defaults to three dots
     */
    public function truncate($string, $limit=150, $delimiter=' ', $append='...') {
        $return = $string;
        $stringLength = mb_strlen($string);

        if ($stringLength > $limit) {
            $until = $this->_getMaximumOffset($limit, $this->_strpos($string, $delimiter, $limit));
            $return = mb_substr($string, 0, $until);

            // Do not append if the resulting string is exactly the same as it came in
            if ($return !== $string) {
                   $return .= $append;
            }
        }

        return $return;
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
     * Decomposes a RFC5322 email address into an array with the 2 elements apart
     *
     * Can handle with unclean data
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
                $decodedHeader = imap_mime_header_decode($decomposedName);
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
}
