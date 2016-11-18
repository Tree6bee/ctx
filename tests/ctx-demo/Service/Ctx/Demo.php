<?php

namespace Tests\Tree6bee\Ctx\Service\Ctx;

use Tests\Tree6bee\Ctx\Basic\Ctx;

class Demo extends Ctx
{
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function init()
    {
        $this->middle = ' ';
    }

    public function setMessage($name)
    {
        $this->log = $this->prefix . $this->middle . $name;

        return true;
    }

    public function getMessage()
    {
        return $this->log;
    }
}
