<?php

namespace Sanvalabs\BdPhone;

class PhoneNumber
{
    /**
     * @param  array<string, string>  $operators  prefix => operator name, e.g. ['017' => 'Grameenphone']
     */
    public function __construct(protected array $operators) {}

    /**
     * "+880 1712-345678" => "01712345678", or null if the number is invalid.
     */
    public function normalize(string $number): ?string
    {
        $digits = preg_replace('/\D/', '', $number);

        // Drop the country code: 8801712345678 => 01712345678
        if (str_starts_with($digits, '880')) {
            $digits = substr($digits, 2);
        }

        if (! preg_match('/^01\d{9}$/', $digits)) {
            return null;
        }

        return isset($this->operators[substr($digits, 0, 3)]) ? $digits : null;
    }

    public function isValid(string $number): bool
    {
        return $this->normalize($number) !== null;
    }

    /**
     * "01712345678" => "Grameenphone"
     */
    public function operator(string $number): ?string
    {
        $normalized = $this->normalize($number);

        return $normalized ? $this->operators[substr($normalized, 0, 3)] : null;
    }

    /**
     * "01712345678" => "+8801712345678"
     */
    public function international(string $number): ?string
    {
        $normalized = $this->normalize($number);

        return $normalized ? '+88'.$normalized : null;
    }
}