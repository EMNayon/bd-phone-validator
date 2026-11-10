<?php

namespace Sanvalabs\BdPhone\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sanvalabs\BdPhone\Facades\BdPhone;

class PhoneNumberTest extends TestCase
{
    public static function validNumbers(): array
    {
        return [
            ['01712345678'],
            ['+8801712345678'],
            ['8801712345678'],
            ['+880 1712-345678'],
            ['017 1234 5678'],
        ];
    }

    #[Test]
    #[DataProvider('validNumbers')]
    public function it_normalizes_valid_numbers(string $number): void
    {
        $this->assertTrue(BdPhone::isValid($number));
        $this->assertSame('01712345678', BdPhone::normalize($number));
    }

    public static function invalidNumbers(): array
    {
        return [
            ['0171234567'],     // 10 digits
            ['017123456789'],   // 12 digits
            ['01212345678'],    // 012 is not an operator
            ['01112345678'],    // 011 is not an operator
            ['+911712345678'],  // not Bangladesh
            ['hello'],
            [''],
        ];
    }

    #[Test]
    #[DataProvider('invalidNumbers')]
    public function it_rejects_invalid_numbers(string $number): void
    {
        $this->assertFalse(BdPhone::isValid($number));
        $this->assertNull(BdPhone::normalize($number));
    }

    #[Test]
    public function it_detects_the_operator(): void
    {
        $this->assertSame('Grameenphone', BdPhone::operator('01712345678'));
        $this->assertSame('Grameenphone', BdPhone::operator('01312345678'));
        $this->assertSame('Banglalink', BdPhone::operator('01912345678'));
        $this->assertSame('Robi', BdPhone::operator('+8801812345678'));
        $this->assertSame('Airtel', BdPhone::operator('01612345678'));
        $this->assertSame('Teletalk', BdPhone::operator('01512345678'));
        $this->assertNull(BdPhone::operator('123'));
    }

    #[Test]
    public function it_formats_to_international(): void
    {
        $this->assertSame('+8801712345678', BdPhone::international('017-1234-5678'));
        $this->assertNull(BdPhone::international('123'));
    }
}