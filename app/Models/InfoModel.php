<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 22.03.2016
 * Time: 18:37
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    // an array containing each relations name
    public $relation_methods = [];
    // whether to include the main attributes values /
    // properties of x to many relations (like a collection count)
    public $info_relation_attributes = true;

    /**
     * Maps relation names to attribute names. The generated info
     * will contain a value for each relation - an attribute of the
     * referenced model (if the type is a "One to One" relation).
     *
     * A function may be specified if the relation type is "One to Many".
     * The function will be called on the collection.
     *
     * For example:
     * $patient->to_info() will generate a description of the
     * assignment.
     * Returning ['therapist' => 'name'] will add the
     * therapists name to each info.
     * Returning ['assignments' => 'collection_info'] will add the number
     * of assignments to each info (the return value of the method
     * $this->collection_info).
     *
     * This method should be overwritten if such information should
     * be included. Returning an empty won't add any relationship
     * specific information.
     */
    protected function info_relation_map() {
        return [];
    }

    /**
     * Converts a collection (the value of a relation of type "One To Many")
     * to a single value. Each info contains a key / value pair for such a
     * relation: ['relation_name' => 'return_value']
*                (e.g. ['assignments' => 12] - for a concrete patient)
     *
     * @param Collection $collection
     *          referenced elements (values)
     * @param $relation_name
     *          the relations name
     * @return a string
     */
    protected function collection_info(Collection $collection, $relation_name) {
        return $collection->count();
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
    protected function attribute_info() {
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
    protected function method_info() {
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
     * - method_info(...)
     *      (adds the return values of a set of predefined method calls)
     *
     * Please refer to the documentation of those methods to determine the
     * included information.
     *
     * Related information can also be included. A list of relationships
     * can be passed as an argument.
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
     *        array $relations
     *             the relationships which should be processed (sub infos
     *             will be created and inserted)
     *
     * @return array an array containing generated information (describing this
     *          instance) - all original entries are retained
     */
    public function to_info($info = [], $path = null, $relations = []) {
        // collect information about this instance
        $collected_info = array_merge($this->attribute_info(),
                                $this->date_info(),
                                $this->method_info());

        // add the collected information to the target array
        $info = array_add($info, $path, $collected_info);

        // calculate the path for further insertions (which require a key)
        $add_path = $path === null ? '' : $path .'.';

        $relations_to_proceed = $relations;

        if ($this->info_relation_attributes === true) {
            $relations_to_proceed = array_merge($relations, array_keys($this->info_relation_map()));
        }

        // process specified relationships
        foreach($relations_to_proceed as $relation_name) {
            // get referenced model instance
            $target = $this->getRelationValue($relation_name);
            // where to place the generated sub info / the key - value pair?
            $attribute_path = $this->info_camel_case ?
                                $add_path.camel_case($relation_name) :
                                $add_path.$relation_name;
            // maps the relations name to its main attribute (if key exists)
            $map = $this->info_relation_map();

            if ($target && (get_class($target) === Collection::class)) {
                if (in_array($relation_name, $relations)) {
                    // if the relation is of type "One to Many" or "Many to Many"
                    // -> include a list of sub infos
                    for ($i = 0; $i < $target->count(); $i++) {
                        $info = $target->get($i)->to_info($info, $attribute_path. '.' .$i);
                    }
                } else {
                    // the method that should be called on the collection
                    $method_name = array_key_exists($relation_name, $map) ? $map[$relation_name] : null;

                    if ($method_name) {
                        // relation shouldn't be processed -> call method to obtain value
                        $info = array_add($info, $attribute_path, $this->{$method_name}($target, $relation_name));
                    }
                }
            } else {
                if (in_array($relation_name, $relations)) {
                    // the relation should be processed and is of type "One to One"
                    // -> add the single info
                    $info = $target ? $target->to_info($info, $attribute_path)
                        : array_add($info, $attribute_path, $this->info_null_string);
                } else {
                    // relation shouldn't be processed -> add the main attributes value

                    // the attributes name whose value should be stored
                    $main_attribute = array_key_exists($relation_name, $map) ? $map[$relation_name] : null;

                    // store the attributes value - or the null string, if the attribute, the
                    // link or the mapping is null
                    if ($main_attribute && $target && $target->{$main_attribute}) {
                        $info = array_add($info, $attribute_path, $target->{$main_attribute});
                    } else {
                        $info = array_add($info, $attribute_path, $this->info_null_string);
                    }
                }
            }
        }

        return $info;
    }

}