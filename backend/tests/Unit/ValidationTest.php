<?php

namespace Tests\Unit;

use App\Exceptions\ValidationException;
use App\Utils\Validator;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    public function testRequiredRulePasses(): void
    {
        $data = ['email' => 'test@uiu.ac.bd'];
        $validator = Validator::make($data, ['email' => 'required|email']);
        $validated = $validator->validate();

        $this->assertEquals('test@uiu.ac.bd', $validated['email']);
    }

    public function testRequiredRuleFailsWhenEmpty(): void
    {
        $this->expectException(ValidationException::class);
        $data = ['email' => ''];
        Validator::make($data, ['email' => 'required'])->validate();
    }

    public function testEmailRuleFailsInvalidEmail(): void
    {
        $this->expectException(ValidationException::class);
        $data = ['email' => 'not-an-email'];
        Validator::make($data, ['email' => 'email'])->validate();
    }
}
