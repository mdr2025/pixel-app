<?php 

namespace PixelApp\Helpers;

class AlphabeticalNumberGeneratingHelpers
{
    
    public static function generateAlphabeticalNumber($model, string $type)
    {
        $tenantPrefix =  tenant()->abbreviation
                        ?? (
                            str_word_count(tenant()->name) > 1
                            ? strtok(tenant()->name, ' ') : tenant()->name);

            
        $numbers = app($model)
            ->whereNotNull('number')
            ->pluck('number');
 
        $last = $numbers->map(function ($number) { // Extract letter and number parts to get the last number
            preg_match('/([A-Z]+)(\d{5})$/', $number, $matches);
            if (!empty($matches)) {
                return [
                    'letter' => $matches[1],
                    'number' => (int) $matches[2],
                ];
            }
            return ['letter' => 'A', 'number' => 0];
        })->sort(function ($a, $b) {
            // Sort first by letter (lexicographically), then by number
            return strcmp($a['letter'], $b['letter']) ?: $a['number'] <=> $b['number'];
        })->last();

        $letterPart = $last['letter'] ?? 'A';
        $numberPart = $last['number'] ?? 0;

        $numberPart++;

        if ($numberPart > 99999) {
            $numberPart = 1;
            $letterPart = self::incrementLetter($letterPart);
        }

        $formattedNumber = sprintf('%05d', $numberPart);
        $upperTenantPrefix = strtoupper($tenantPrefix);

        return "{$upperTenantPrefix} / {$type} / {$letterPart}{$formattedNumber}";
    }

    // public static function generateNumber($model, string $type)
    // {
    //     $tenantPrefix = tenant()->abbreviation
    //         ?? (str_word_count(tenant()->name) > 1
    //             ? strtok(tenant()->name, ' ')
    //             : tenant()->name);

    //     $numbers = app($model)
    //         ->whereNotNull('number')
    //         ->pluck('number');

    //     $lastNumber = $numbers->map(function ($number) {
    //         preg_match('/(\d{5})$/', $number, $matches);
    //         return isset($matches[1]) ? (int) $matches[1] : 0;
    //     })->max();

    //     $numberPart = $lastNumber + 1;

    //     if ($numberPart > 99999) {
    //         throw new \Exception("Maximum number limit (99999) has been reached.");
    //     }

    //     $formattedNumber = sprintf('%05d', $numberPart);

    //     return "{$tenantPrefix} / {$type} / {$formattedNumber}";
    // }


    /**
     * Increment letter sequence (A → Z, then AA → ZZ, etc.)
     */
    function incrementLetter($letter)
    {
        $length = strlen($letter);
        $carry = true;
        $result = '';

        for ($i = $length - 1; $i >= 0; $i--) {
            if ($carry) {
                if ($letter[$i] === 'Z') {
                    $result = 'A' . $result;
                } else {
                    $result = chr(ord($letter[$i]) + 1) . $result;
                    $carry = false;
                }
            } else {
                $result = $letter[$i] . $result;
            }
        }

        return $carry ? 'A' . $result : $result; // Add 'A' if all letters were 'Z'
    }

}