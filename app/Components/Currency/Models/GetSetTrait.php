<?php

namespace App\Components\Currency\Models;

use Exception;

trait GetSetTrait
{
    public function rules()
    {
        return [];
    }

    public function get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new Exception("Свойства $name нет");
    }

    public function set($name, $value)
    {
        if (!property_exists($this, $name)) {
            return $this;
            //throw new Exception("Свойства $name нет");
        }

        $rules = $this->rules();
        if (isset($rules[$name])) {
            $res = false;
            try {
                $res = call_user_func($rules[$name], $value);
            } catch (Exception $e) {
            }

            if (!$res) {
                throw new Exception("Поле $name неверно заполнено");
            }
        }

        $this->$name = $value;
        return $this;
    }

    public function fill($attrs)
    {
        foreach ($attrs as $name => $value) {
            $this->set($name, $value);
        }
    }

    public function getAttributes()
    {
        return get_object_vars($this);
    }

    public function __get($prop)
    {
        return $this->get($prop);
    }

    public function __set($prop, $value)
    {
        return $this->set($prop, $value);
    }
}
