<?php

namespace Sanvalabs\BdPhone\Tests;

use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Sanvalabs\BdPhone\Rules\ValidBdPhone;

class ValidBdPhoneTest extends TestCase
{
    #[Test]
    public function it_passes_a_valid_number(): void
    {
        $validator = Validator::make(['phone' => '01712345678'], ['phone' => new ValidBdPhone]);

        $this->assertTrue($validator->passes());
    }

    #[Test]
    public function it_fails_an_invalid_number_with_a_message(): void
    {
        $validator = Validator::make(['phone' => '12345'], ['phone' => new ValidBdPhone]);

        $this->assertTrue($validator->fails());
        $this->assertSame(
            'The phone must be a valid Bangladeshi mobile number.',
            $validator->errors()->first('phone'),
        );
    }

    #[Test]
    public function it_can_allow_only_some_operators(): void
    {
        $rule = new ValidBdPhone(['Grameenphone', 'Robi']);

        $this->assertTrue(Validator::make(['phone' => '01712345678'], ['phone' => $rule])->passes());

        $validator = Validator::make(['phone' => '01912345678'], ['phone' => $rule]);

        $this->assertTrue($validator->fails());
        $this->assertSame(
            'The phone must be a Grameenphone, Robi number.',
            $validator->errors()->first('phone'),
        );
    }

    #[Test]
    public function it_shows_bangla_messages_when_locale_is_bn(): void
    {
        app()->setLocale('bn');

        $validator = Validator::make(['phone' => '12345'], ['phone' => new ValidBdPhone]);

        $this->assertSame(
            'phone একটি সঠিক বাংলাদেশি মোবাইল নম্বর হতে হবে।',
            $validator->errors()->first('phone'),
        );
    }

    #[Test]
    public function it_uses_operators_from_config(): void
    {
        config(['bd-phone.operators' => ['017' => 'Grameenphone']]);
        $this->app->forgetInstance(\Sanvalabs\BdPhone\PhoneNumber::class);

        $this->assertTrue(Validator::make(['phone' => '01712345678'], ['phone' => new ValidBdPhone])->passes());
        $this->assertTrue(Validator::make(['phone' => '01912345678'], ['phone' => new ValidBdPhone])->fails());
    }
}