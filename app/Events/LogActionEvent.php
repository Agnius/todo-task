<?php

namespace App\Events;


class LogActionEvent extends Event
{
    public $class;

    public $action;

    public $value;

    public function __construct($class, $action, $value)
    {
        $this->class = $class;
        $this->action = $action;
        $this->value = $value;
    }
}