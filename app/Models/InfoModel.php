<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 22.03.2016
 * Time: 18:37
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoModel extends Model
{

    public $info_date_format = 'd.m.Y';
    public $info_null_string = '-';
    public $info_methods = [];
    public $reflection_class;

    public function class_name() {
        if (!$this->reflection_class) {
            $this->reflection_class = new \ReflectionClass($this);
        }

        return $this->reflection_class->getShortName();
    }

    public function dates_info($camel_case = true) {
        foreach ($this->getDates() as $date_attribute_name) {
            $value = $this->getAttribute($date_attribute_name);
            $name = $camel_case ? camel_case($date_attribute_name) : $date_attribute_name;

            $info[$name] = $value !== null ? $value->format($this->info_date_format) : $this->info_null_string;
        }

        return $info;
    }

    public function attribute_info($camel_case = true) {
        $info = [];
        $dates = $this->getDates();

        foreach (array_keys($this->getAttributes()) as $attribute_name) {
            if (!in_array($attribute_name, $dates)) {
                $value = $this->getAttribute($attribute_name);
                $name = $camel_case ? camel_case($attribute_name) : $attribute_name;

                $info[$name] = $value !== null ? $value : $this->info_null_string;
            }
        }

        return $info;
    }

    public function method_info($camel_case = true) {
        $info = [];

        foreach ($this->info_methods as $method_name) {
            $name = $camel_case ? camel_case($method_name) : $method_name;
            $info[$name] = $this->$method_name();
        }

        return $info;
    }

    public function to_info($current_info = []) {
        $attributes = $this->attribute_info();

        $members = array_merge($attributes, $this->dates_info());
        $info = array_merge($members, $this->method_info());

        $current_info[$this->class_name()] = $info;

        return $current_info;
    }

}