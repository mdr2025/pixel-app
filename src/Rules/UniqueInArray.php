<?php

namespace PixelApp\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueInArray implements Rule
{
    protected $fieldName;
    protected $customMessage;

    public function __construct($fieldName = null, $customMessage = null)
    {
        $this->fieldName = $fieldName;
        $this->customMessage = $customMessage;
    }

    public function passes($attribute, $value)
    {
        // Extract the array key from the attribute "tempIncident.customCauses.0.cause_name" -> "0"
        $parts = explode('.', $attribute);
        $arrayKey = $parts[count($parts) - 2] ?? null;

        if ($arrayKey === null) {
            return true;
        }

        // Get the parent array data
        $parentPath = str_replace('.' . $arrayKey . '.' . $this->fieldName, '', $attribute);
        $parentData = data_get(request()->all(), $parentPath, []);

        if (!is_array($parentData)) {
            return true;
        }

        // Count occurrences of this value in the array
        $count = 0;
        foreach ($parentData as $item) {
            if (isset($item[$this->fieldName]) && $item[$this->fieldName] === $value) {
                $count++;
            }
        }

        // Allow only one occurrence
        return $count <= 1;
    }

    public function message()
    {
        if ($this->customMessage) {
            return $this->customMessage;
        }

        $readableFieldName = $this->humanText();
        return "This {$readableFieldName} has already been used in the same request.";
    }

    protected function humanText()
    {
        if (!$this->fieldName) {
            return 'field';
        }

        $readable = str_replace('_', ' ', $this->fieldName);
        $readable = preg_replace('/([a-z])([A-Z])/', '$1 $2', $readable);

        return ucfirst($readable);
    }
}
