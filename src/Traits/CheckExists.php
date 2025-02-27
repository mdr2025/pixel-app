<?php

namespace PixelApp\Traits;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

trait CheckExists
{
    protected function checkIfExistsDependOnQuery(string $table , string $column , string $columnFilter , mixed $value):Exists
    {
        return Rule::exists($table, $column)->where($columnFilter, $value);
    }

}
