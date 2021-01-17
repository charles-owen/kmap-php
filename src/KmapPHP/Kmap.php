<?php
/**
 * @file
 * PHP server-side support for Cirsim
 */

namespace CL\KmapPHP;

/**
 * Server-side support for Kmap
 *
 * @cond
 * @property string appTag
 * @endcond
 */
class Kmap {
    /**
     * Kmap constructor.
     * Sets the default values.
     */
    public function __construct() {
        $this->reset();
    }

    /**
     * Reset the Cirsim object to no single, tests, or only options.
     */
    public function reset() {
        $this->appTag = null;
        $this->name = null;
        $this->size = 3;        // Default size
        $this->api = new KmapAPI();
    }


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
            case 'appTag':
                return $this->appTag;

            case 'api':
                return $this->api;

            case 'name':
                return $this->name;

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
     * answer | string | JSON answer to the problem
     * appTag | string | If set, value is used as appTag for the file system
     * components | array | Array of components to include
     * export | boolean | If true, export/import menu options are available (default=true)
     * save | boolean | If true, the save menu option is included (default=false)
     * staff | boolean | If true, current user is a staff member (default=false);
     * tab | string | Adds a single tab to the circuit
     * tabs | array | Adds an array of tabs to the circuit
     * tests | array | Array of Cirsim tests
     *
     * @param string $property Property name
     * @param mixed $value Value to set
     */
    public function __set($property, $value) {
        if(!$this->set($property, $value)) {
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property ' . $property .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
                E_USER_NOTICE);
        }
    }

    /**
     * Property set direct function. Returns true if set was successful.
     * @param string $property Property name
     * @param mixed $value Value to set
     * @return bool true if a supported set value.
     */
    public function set($property, $value) {
        switch($property) {
            case 'appTag':
                $this->appTag = $value;
                break;

            case 'name':
                $this->name = $value;
                break;

            case 'size':
                $this->size = $value;
                break;

            default:
                return false;
        }

        return true;
    }



    /**
     * Present the Kmap div in a view.
     * @return string HTML
     */
    public function present() {
        $html = '';

        $data = ['size'=>$this->size];

        foreach($this->options as $option => $value) {
            $data[$option] = $value;
        }

        if($this->api->test !== null) {
            // Api dependent features
            $data['test'] = $this->api->test;

            if($this->name !== null) {
                $data['name'] = $this->name;
            }

            if($this->appTag !== null) {
                $data['appTag'] = $this->appTag;
            }
        }

        $payload = htmlspecialchars(json_encode($data), ENT_NOQUOTES);
        $html .= '<div class="kmap-cl-install">' . $payload . '</div>';

        return $html;
    }

    /**
     * Add other Kmap config options.
     * @param string $option Option name
     * @param mixed $value Value to set
     */
    public function option($option, $value) {
        $this->options[$option] = $value;
    }

    private $size;
    private $appTag;    // Application tag to send to test results
    private $name;      // Name to send to test results
    private $api;
}