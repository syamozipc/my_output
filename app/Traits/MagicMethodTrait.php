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
            // 値が設定されていない場合はエラーになるので、null合体演算子でエラーを防ぐ
            return $this->{$name} ?? null;
        } else {
            return $this->params[$name];
        }
    }
}   