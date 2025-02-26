<?php

namespace PixelApp\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class CaseInsensitiveFilter implements Filter
{

    private string $type;
    private bool $caseSensitive;
    /**
     * @param string $type accept: 'startWith', 'endWith', 'includes', 'exact' | default: 'includes'
     * @param bool $caseSensitive
     */
    function __construct(string $type = "includes", bool $caseSensitive = false)
    {
        $this->type = $type;
        $this->caseSensitive = $caseSensitive;
    }

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        if ($this->caseSensitive) {
            return $query->whereRaw("$property LIKE ?", [$this->prepareValue($value)]);
        }
        return $query->whereRaw("$property LIKE LOWER(?)", [$this->prepareValue($value)]);
    }

    /**
     * @param string $value
     * 
     * @return string
     */
    private function prepareValue(string $value): string
    {
        switch ($this->type) {
            case "exact":
                return "{$value}";
            case "startWith":
                return "{$value}%";
            case "endWith":
                return "%{$value}";
            case "includes":
            default:
                return "%{$value}%";
        }
    }
}
