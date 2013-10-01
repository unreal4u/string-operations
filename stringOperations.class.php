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

    public function __construct($charset='UTF-8') {
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding($charset);
        } else {
            throw new \Exception('mbstring extension must be installed!');
        }
    }

    /**
     * Gets the (real) length of a string
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

            if ($return !== $string) {
                   $return .= $append;
            }
        }

        return $return;
    }
}
