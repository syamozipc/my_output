<?php
namespace App\Traits;

trait MagicMethodTrait {

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        } else {
            $this->params[$name] = $value;
        }

        return $this;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        } else {
            return $this->params[$name];
        }
    }
}   