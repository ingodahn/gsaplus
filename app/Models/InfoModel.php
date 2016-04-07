<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Adds an info method to each eloquent model (all models inherit from this one).
 *
 * The returned info is an array that contains preformatted values. It (or parts of it)
 * may be passed on to a view.
 */
class InfoModel extends Model
{

    // dynamic attributes - related accessors are called to obtain the values
    protected $dynamic_attributes = [];

    // output key names in camelCase?
    // class specific -> override the method to set a different date format
    public static function info_camel_case() {
        return true;
    }

    // standard date format (used to convert a date to a string)
    // class specific -> override the method to set a different date format
    public static function info_date_format() {
        return 'd.m.Y';
    }

    /**
     * Returns an array containing a string representation of each date.
     *
     * Format [ <date attribute name> => <string representation of date> ]
     *
     * @return array an array containing a string representation of each date
     */
    protected function date_info() {
        $info = [];

        foreach ($this->getDates() as $date_attribute_name) {
            if (!in_array($date_attribute_name, $this->hidden)) {
                $value = $this->getAttribute($date_attribute_name);
                $name = static::info_camel_case() ? camel_case($date_attribute_name) : $date_attribute_name;

                if ($value !== null) {
                    $info[$name] = $value->format(static::info_date_format());
                }
            }
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
    protected function attribute_info() {
        $info = [];
        $dates = $this->getDates();

        foreach (array_keys($this->attributesToArray()) as $attribute_name) {
            if (!in_array($attribute_name, $dates) && $attribute_name !== 'id') {
                $value = $this->getAttribute($attribute_name);
                $name = static::info_camel_case() ? camel_case($attribute_name) : $attribute_name;

                if ($value !== null) {
                    $info[$name] = $value;
                }
            }
        }

        return $info;
    }

    /**
     * Returns an array containing the results of some accessors. $this->dynamic_attributes
     * lists the accessors to call (i.e. their names).
     *
     * @return an array containing a string representation of each attribute value
     */
    protected function accessor_info() {
        $info = [];

        foreach ($this->dynamic_attributes as $dynamic_attribute) {
            $name = static::info_camel_case() ? camel_case($dynamic_attribute) : $dynamic_attribute;
            $info[$name] = $this->getAttribute($dynamic_attribute);
        }

        return $info;
    }

    /**
     * Adds an array containing preformatted values (to the given array). It
     * (or parts of it) may be passed on to a view. A new array is instantiated
     * if no target array is given.
     *
     * The path can be used to specify a target location.
     *
     * For example:
     * to_info($some_array, 'document.entry')
     *
     * will add the information under the specified path:
     * [ 'document' => [ 'entry' => [... information ...] ] ]
     *
     * All original entries are retained.
     *
     * The array is build up by calling the methods
     * - attribute_info(...)
     *      (adds a string representation for each attributes value)
     * - date_info(...)
     *      (adds a string representation for each date)
     * - accessor_info(...)
     *      (adds the return values of a set of predefined accessors)
     *
     * Please refer to the documentation of those methods to determine the
     * included information.
     *
     * Related information can also be included. A list of relationships
     * can be passed as an argument. Nested relationships should use
     * dot notation (e.g. 'assignments.comment').
     *
     * For example:
     * $patient([], null, ['assignments']) will create a new array containing
     * the generated info and a list of the patients assignments.
     *
     * @param array $info
     *             a base array
     *        string $path
     *             a target location (where the generated information should be
     *             inserted) - this has to be different from null
     *        array $relations_paths
     *             the relationships which should be processed (sub infos
     *             will be created and inserted)
     *
     * @return array an array containing generated information (describing this
     *          instance) - all original entries are retained
     */
    protected function to_info($info = [], $path = null, $relations_paths = []) {
        // collect information about this instance
        $collected_info = array_merge($this->attribute_info(),
            $this->date_info(),
            $this->accessor_info());

        // add collected information
        $info = array_add($info, $path, $collected_info);

        // calculate the path for further insertions (which require a key)
        $add_path = $path === null ? '' : $path .'.';

        // process specified relation paths
        foreach($relations_paths as $relation_path) {
            $relations_on_path = explode(".", $relation_path, 2);

            $is_recursive = sizeof($relations_on_path) >= 2;

            if ($is_recursive) {
                $relation_name = $relations_on_path[0];
                $remaining_relation_path = [$relations_on_path[1]];
            } else {
                $relation_name = $relation_path;
                $remaining_relation_path = [];
            }

            // get the referenced model instance
            $target = $this->getRelationValue($relation_name);
            // where to place the generated sub info
            $attribute_path = static::info_camel_case() ?
                $add_path.camel_case($relation_name) :
                $add_path.$relation_name;

            if ($target && (get_class($target) === Collection::class)) {
                // if the relation is of type "One to Many" or "Many to Many"
                // -> include a list of sub infos
                for ($i = 0; $i < $target->count(); $i++) {
                    $info = $target->get($i)->to_info($info, $attribute_path. '.' .$i, $remaining_relation_path);
                }
            } else if ($is_recursive) {
                // first process sub paths
                $info = $target ?
                        $target->to_info($info, $attribute_path, $remaining_relation_path) : $info;
            } else {
                // at last: add info
                $info = $target ? array_add($info, $attribute_path, $target->to_info()) : $info;
            }
        }

       return $info;
    }

    public function info_with(...$relation_paths) {
        return $this->to_info([], null, $relation_paths);
    }

    public function info() {
        return $this->to_info();
    }

}