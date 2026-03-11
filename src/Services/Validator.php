<?php
namespace App\Services;

class Validator
{
    public static function sanitize(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    public static function validateInt($value): ?int
    {
        $filtered = filter_var($value, FILTER_VALIDATE_INT);
        return ($filtered !== false && $filtered > 0) ? $filtered : null;
    }
}