<?php
/**
 * @file
 * PHP server-site support for Cirsim: API settings.
 */

namespace CL\KmapPHP;

/**
 * PHP server-site support for Kmap: API settings.
 *
 * @property string test API path for test results
 */
class KmapAPI {
    /**
     * Property get magic method
     *
     * <b>Properties</b>
     * Property | Type | Description
     * -------- | ---- | -----------
     *
     * @param string $property Property name
     * @return mixed
     */
    public function __get($property) {
        switch($property) {

            case 'test':
                return $this->test;

            default:
                $trace = debug_backtrace();
                trigger_error(
                    'Undefined property ' . $property .
                    ' in ' . $trace[0]['file'] .
                    ' on line ' . $trace[0]['line'],
                    E_USER_NOTICE);
                return null;
        }
    }

    /**
     * Property set magic method
     *
     * <b>Properties</b>
     * Property | Type | Description
     * -------- | ---- | -----------
     * load | string | API path for loading files
     * files | string | API path for getting possible files
     * save | string | API path for saving files
     *
     * @param string $property Property name
     * @param mixed $value Value to set
     */
    public function __set($property, $value) {
        switch($property) {
            case 'test':
                $this->test = $value;
                break;

            default:
                $trace = debug_backtrace();
                trigger_error(
                    'Undefined property ' . $property .
                    ' in ' . $trace[0]['file'] .
                    ' on line ' . $trace[0]['line'],
                    E_USER_NOTICE);
                break;
        }
    }

    private $test = null;
}