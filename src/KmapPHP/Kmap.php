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
 * @property string appTag Application tag to send as a result of a test
 * @property string name Filename to send as a result of a test
 * @property int size Size of the map 2, 3, or 4
 * @property bool test Set true if test results should be send to a server
 * @property \CL\KmapPHP\KmapAPI api The API to set
 * @property array minterms Array of minterms to use
 * @property array dontcares Array of don't cares to use
 * @property array labels Variable labels to use
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
     * Reset the Kmap object to simple size 3
     */
    public function reset() {
        $this->name = null;
        $this->appTag = null;

        $this->size = 3;
        $this->manual = false;
        $this->solve = false;
        $this->solved = false;
        $this->genDontCareOption = true;
        $this->generator = true;
        $this->fixed = false;
        $this->verbose = true;
        $this->resultSel = null;
        $this->expressionSel = null;
        $this->success = 'success';
        $this->options = [];
        $this->minterms = [];
        $this->dontcare = [];
        $this->genDontCare = false;
        $this->labels = null;
        $this->encode = false;

        $this->test = false;
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

            case 'encode':
                $this->encode = $value;
                break;

            case 'test':
                $this->test = $value;
                break;

            case "size":
                $this->size = $value;
                break;

            case 'manual':
                $this->manual = $value;
                break;

            case 'solve':
                $this->solve = $value;
                break;

            case 'solved':
                $this->solved = $value;
                break;

            case 'genDontCareOption':
                $this->genDontCareOption = $value;
                break;

            case 'generator':
                $this->generator = $value;
                break;

            case "minterms":
                $this->minterms = $value;
                break;

            case 'map':
                $this->map = $value;
                break;

            case "fixed":
                $this->fixed = $value;
                break;

            case "verbose":
                $this->verbose = $value;
                break;

            case "dontcare":
            case "dontcares":
                $this->dontcare = $value;
                break;

            case 'genDontCare':
                $this->genDontCare = $value;
                break;

            case 'labels':
                $this->labels = $value;
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
        $data = $this->options;
        $data['size'] = $this->size;

        if($this->fixed) {
            $data['fixed'] = true;
        }

        if(!$this->map) {
            $data['map'] = false;
        }

        if(!$this->generator) {
            $data['generator'] = false;
        }

        if($this->manual) {
            $data['manual'] = true;
        }

        if($this->solve) {
            $data['solve'] = true;
        }

        if($this->solved) {
            $data['solved'] = true;
        }

        if(!$this->verbose) {
            $data['verbose'] = false;
        }

        if(!$this->genDontCareOption) {
            $data['genDontCareOption'] = false;
        }

        if($this->genDontCare) {
            $data['genDontCare'] = true;
        }

        if($this->resultSel !== null) {
            $data['resultSel'] = $this->resultSel;
            $data['expressionSel'] = $this->expressionSel;
            $data['success'] = $this->success;
        }

        if(count($this->minterms) > 0) {
            $data['minterms'] = $this->minterms;
        }

        if(count($this->dontcare) > 0) {
            $data['dontcare'] = $this->dontcare;
        }

        if($this->labels !== null) {
            $data['labels'] = $this->labels;
        }

        if($this->test && $this->api->test !== null) {
            // Api dependent features
            $data['testAPI'] = $this->api->test;

            if($this->name !== null) {
                $data['name'] = $this->name;
            }

            if($this->appTag !== null) {
                $data['appTag'] = $this->appTag;
            }
        }


        if($this->encode) {
            $payload = base64_encode(htmlspecialchars(json_encode($data), ENT_NOQUOTES));

        } else {
            $payload = htmlspecialchars(json_encode($data), ENT_NOQUOTES);
        }

        return '<div class="kmap-cl-install">' . $payload . '</div>';
    }

    /**
     * Add other Kmap config options.
     * @param string $option Option name
     * @param mixed $value Value to set
     */
    public function option($option, $value) {
        $this->options[$option] = $value;
    }

    private $size;      // Size: 2, 3, or 4
    private $appTag;    // Application tag to send to test results
    private $name;      // Name to send to test results
    private $api;
    private $options;               // Other options to set
    private $test;      // Submit test results?

    private $manual;                // Manual data entry?
    private $solve;                 // Include Solve button?
    private $solved;                // Present a solved map?
    private $genDontCareOption;     // Generator has don't cares as an option?
    private $generator;             // Display the generator
    private $fixed;                 // Fixed minterm choice, no generator
    private $genDontCare = false;	// Generate don't cares in problems
    private $map = true;            // Include the actual map?

    // A results selector. Selector that will be set to the success value
    // if the expression successfully checks
    private $resultSel;

    private $success;               // Value resultSel will be set to if check is successful ('fail' otherwise)
    private $expressionSel;         // Selector that will be set to the expression each time check is pressed.

    private $verbose = true;		// Verbose response on mistakes
    private $minterms = [];	        // The minterms for the problem
    private $dontcare = []; 	    // Minterms we don't care about
    private $labels = null;			// Optional array of labels to use
    private $encode;                // base64 encode the div contents?
}