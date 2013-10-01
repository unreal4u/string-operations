<?php

namespace u4u;

/**
 * Class that does several operations on strings to ensure data gets truncated properly
 *
 * @author unreal4u
 */
class stringOperations {
    /**
     * The maximum offset a string is allowed to pass after the limit has been reached
     * @var int
     */
    public $maximumOffset = 10;

    /**
     * Gets the (real) size of a string. If mb_strlen is available, it uses that value, else it will use std strlen
     *
     * @param string $string
     * @return int
     */
    private function _strlen($string) {
        if (function_exists('mb_strlen')) {
            $size = mb_strlen($string);
        } else {
            $size = strlen($string);
        }

        return $size;
    }

    /**
     * Gets the absolute maximum of characters that are allowed, or any number below that
     *
     * @param int $limit
     * @param int $number
     * @return int
     */
    private function _getMaximumOffset($limit, $number=0) {
        // Absolute maximum number of characters
        $maxCharacterLimit = ((100 + $this->maximumOffset) * $limit) / 100;
        if ($number < $maxCharacterLimit) {
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
        $stringLength = $this->_strlen($string);

        if ($stringLength > $limit) {
            $until = $this->_getMaximumOffset($limit, strpos($string, $delimiter, $limit));
            $return = substr($string, 0, $until).$append;
        }

        return $return;
    }
}
