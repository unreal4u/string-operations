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
     * Constructor
     *
     * @param string $charset The charset we will be using for all our operations. Defaults to "UTF-8"
     * @throws \Exception If mbstring extension is not installed, this will throw an exception
     */
    public function __construct($charset='UTF-8') {
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding($charset);
        } else {
            throw new \Exception('mbstring extension must be installed!');
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
}
