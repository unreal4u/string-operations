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
     * The default is to assume we have no intl extension installed
     * @var boolean
     */
    private $_intlExtensionInstalled = false;

    public function __construct($charset='UTF-8') {
        if (function_exists('mb_strlen')) {
            $this->_intlExtensionInstalled = true;
            mb_internal_encoding($charset);
        }
    }

    /**
     * Gets the (real) size of a string. If mb_strlen is available, it uses that value, else it will use std strlen
     *
     * @param string $string
     * @return int
     */
    private function _strlen($string) {
        if ($this->_intlExtensionInstalled) {
            $size = mb_strlen($string);
        } else {
            $size = strlen($string);
        }

        return $size;
    }

    /**
     * Gets the length of a string
     *
     * @param unknown_type $string
     * @param unknown_type $delimiter
     * @param unknown_type $limit
     * @return number
     */
    protected function _strpos($string, $delimiter, $limit) {
        if ($this->_intlExtensionInstalled) {
            $return = mb_strpos($string, $delimiter, $limit);
        } else {
            $return = strpos($string, $delimiter, $limit);
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
        $stringLength = $this->_strlen($string);

        if ($stringLength > $limit) {
            $until = $this->_getMaximumOffset($limit, $this->_strpos($string, $delimiter, $limit));
            if ($this->_intlExtensionInstalled) {
                $return = mb_substr($string, 0, $until);
            } else {
                $return = substr($string, 0, $until);
            }

            if ($return !== $string) {
                   $return .= $append;
            }
        }

        return $return;
    }
}
