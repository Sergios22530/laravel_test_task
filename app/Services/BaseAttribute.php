<?php

namespace App\Services;

use Illuminate\Http\Request;
/**
 * @property ?Request $request
 */
abstract class BaseAttribute
{
    public ?Request $request = null;

    abstract public function getEloquentAttributes() : array;

    public function setRequest(?Request $request = null): self
    {
        $this->request = $request;
        return $this;
    }
}
