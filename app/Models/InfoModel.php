<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 22.03.2016
 * Time: 18:37
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Adds an info method to each eloquent model (all models inherit from this one).
 *
 * The returned info is an array that contains preformatted values. It (or parts of it)
 * may be passed on to a view.
 */
class InfoModel extends Model
{

    // standard date format (used to convert a date to a string)
    public $info_date_format = 'd.m.Y';
    // representation of a null value
    public $info_null_string = '-';
    // methods to call - the return values key is the method name
    public $info_methods = [];
    // output key names in camelCase?
    public $info_camel_case = true;
    // used to get the short name of the class
    public $reflection_class;

    /**
     * Returns an unique key which is used to store information
     * about this instance - see method to_info(...).
     *
     * @return an unique key which is used to store information
 *              about this instance - see method to_info(...)
     */
    public function info_array_key() {
        if (!$this->reflection_class) {
            $this->reflection_class = new \ReflectionClass($this);
        }

        return strtolower($this->reflection_class->getShortName()[0])
                    . substr($this->reflection_class->getShortName(), 1);
    }

    /**
     * Returns an array containing a string representation of each date.
     *
     * Format [ <date attribute name> => <string representation of date> ]
     *
     * @return array an array containing a string representation of each date
     */
    public function date_info() {
        $info = [];

        foreach ($this->getDates() as $date_attribute_name) {
            $value = $this->getAttribute($date_attribute_name);
            $name = $this->info_camel_case ? camel_case($date_attribute_name) : $date_attribute_name;

            $info[$name] = $value !== null ? $value->format($this->info_date_format) : $this->info_null_string;
        }

        return $info;
    }

    /**
     * Returns an array containing a string representation of each attribute value. Dates aren't
     * listed - please use method date_info to obtain a list of date strings.
     *
     * Format [ <attribute name> => <string representation of value> ]
     *
     * @return an array containing a string representation of each attribute value (excluding dates)
     */
    public function attribute_info() {
        $info = [];
        $dates = $this->getDates();

        foreach (array_keys($this->attributesToArray()) as $attribute_name) {
            if (!in_array($attribute_name, $dates)) {
                $value = $this->getAttribute($attribute_name);
                $name = $this->info_camel_case ? camel_case($attribute_name) : $attribute_name;

                $info[$name] = $value !== null ? $value : $this->info_null_string;
            }
        }

        return $info;
    }

    /**
     * Returns an array containing the results of some method calls. $this->info_methods
     * lists the methods to call (i.e. their names). The name of the method is used to
     * store the return value.
     *
     * Format [ <method name> => <return value> ]
     *
     * @return an array containing a string representation of each attribute value (excluding dates)
     */
    public function method_info() {
        $info = [];

        foreach ($this->info_methods as $method_name) {
            $name = $this->info_camel_case ? camel_case($method_name) : $method_name;
            $info[$name] = $this->$method_name();
        }

        return $info;
    }

    /**
     * Adds an array containing preformatted values (to the given array). It
     * (or parts of it) may be passed on to a view. A new array is instantiated
     * if no array is given.
     *
     * The returned array contains one key - the info array key (e.g. 'patient') -
     * that in return contains all the name / value pairs.
     *
     * The array is build up by calling the methods
     * - attribute_info(...)
     *      (adds a string representation for each attributes value)
     * - date_info(...)
     *      (adds a string representation for each date)
     * - method_info(...)
     *      (adds the return values of a set of predefined method calls)
     *
     * Please refer to the documentation of those methods to determine the
     * included information.
     *
     * @param array $current_info
     *             a base array
     * @return array an array containing generated information (describing this
     *          instance) - all original entries are retained
     */
    public function to_info($current_info = []) {
        $attributes = $this->attribute_info();

        $members = array_merge($attributes, $this->date_info());
        $info = array_merge($members, $this->method_info());

        $current_info[$this->info_array_key()] = $info;

        return $current_info;
    }

}