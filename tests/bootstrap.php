<?php

/**
 * Mock class to return a StdClass overwriting the magic __set_state function
 *
 * @author unreal4u
 */
class createStdClass {
    /**
     * Overwrites default behaviour
     *
     * @param array $array
     * @return stdClass
     */
    static function __set_state(array $array) {
        // Create a standard class
        $stdClass = new stdClass();
        foreach($array as $k => $v) {
            $stdClass->$k = $v;
        }

        /*
         * Little trick: we must convert the encoding to whatever it's value is, except for UTF-8 because our files are
         * already saved in this charset
         */
        if (strtolower($stdClass->charset) != 'default' && strtoupper($stdClass->charset) != 'UTF-8') {
            $stdClass->text = iconv('UTF-8', $stdClass->charset, $stdClass->text);
        }

        // Now we are finally ready to return the stdClass
        return $stdClass;
    }
}
