<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Adds an info method which gathers information about the model.
 */
class InfoModel extends Model
{

    // dynamic attributes - related accessors are called to obtain the values
    protected $dynamic_attributes = [];

    // output key names in camelCase?
    // class specific setting -> override method to use snake case
    public static function info_camel_case() {
        return true;
    }

    // standard date format (dates are converted to strings)
    // class specific setting -> override method to set different date format
    public static function info_date_format() {
        return 'd.m.Y';
    }

    /**
     * Formats every date and returns the resulting array.
     *
     * format: [ <date attribute name> => <string representation of date>, ... ]
     *
     * @return array an array that contains a string representation of each date
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
     * Collects the attributes values and returns the resulting array.
     *
     * format: [ <attribute name> => <string representation of value>, ... ]
     *
     * Dates aren't listed - please use method date_info to obtain a list of date strings.
     *
     * @return an array that contains the models attribute names and their values (excluding dates)
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
     * Collects the dynamic attributes values and returns the resulting array.
     *
     * format: [ <name of dynamic attribute> => <value>, ... ]
     *
     * $this->dynamic_attributes lists the accessors to call (i.e. their names).
     *
     * @return an array that contains the dynamic attributes values
     */
    protected function accessor_info() {
        $info = [];

        foreach ($this->dynamic_attributes as $dynamic_attribute) {
            $name = static::info_camel_case() ? camel_case($dynamic_attribute) : $dynamic_attribute;
            $value = $this->getAttribute($dynamic_attribute);

            $info[$name] = $value !== null &&
                            is_a($value, 'Jenssegers\Date\Date') ? $value->format('d.m.Y') : $value;
        }

        return $info;
    }

    /**
     * Collects information about this instance and a specific set of
     * relations. The information is returned as a (nested) associative
     * array. It (or parts of it) may be passed on to a view.
     *
     * The method allows to merge the result with a different array - the
     * original array won't be modified (the merge result will be returned).
     *
     * The path can be used to specify a merge location.
     *
     * For example:
     * to_info($some_array, 'document.entry')
     *
     * will add the information under the specified path:
     * [ 'document' => [ 'entry' => [... information ...] ] ]
     *
     * The array is build up by calling the methods
     * - attribute_info(...)
     *      (returns each attributes value)
     * - date_info(...)
     *      (returns a string representation for each date)
     * - accessor_info(...)
     *      (returns the dynamic attribute values)
     *
     * Please refer to the documentation of those methods to determine the
     * included information.
     *
     * Related information can also be included. A list of relation paths
     * specifies the relations to process. Nested relations should use
     * dot notation (e.g. 'assignments.comment').
     *
     * For example:
     * to_info([], null, ['assignments']) will also return information on
     * each assignment (if $this is an instance of Patient).
     *
     * @param array $info
     *             a base array
     *        string $path
     *             a merge location (where the generated information should be
     *             added)
     *        array $relations_paths
     *             the relation paths which should be processed (sub infos
     *             will be created and inserted)
     *
     * @return array information about this instance and a specific set of
     * relations
     */
    public function to_info($info = [], $path = null, $relations_paths = []) {
        // collect information about this instance
        $collected_info = array_merge($this->attribute_info(),
            $this->date_info(),
            $this->accessor_info());

        if ($path === null) {
            // add collected information
            foreach ($collected_info as $key => $value) {
                $info = array_add($info, $key, $value);
            }
        } else {
            $info = array_add($info, $path, $collected_info);
        }

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