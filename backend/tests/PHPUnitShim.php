<?php

namespace PHPUnit\Framework;

class TestCase
{
    private ?string $expectedException = null;

    public function __construct(?string $name = null)
    {
    }

    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    public function expectException(string $exception): void
    {
        $this->expectedException = $exception;
    }

    public function getExpectedException(): ?string
    {
        return $this->expectedException;
    }

    public function assertEquals($expected, $actual, string $message = ''): void
    {
        if ($expected != $actual) {
            throw new \AssertionError($message ?: sprintf("Failed asserting that %s matches expected %s", var_export($actual, true), var_export($expected, true)));
        }
    }

    public function assertTrue($condition, string $message = ''): void
    {
        if (!$condition) {
            throw new \AssertionError($message ?: "Failed asserting that condition is true.");
        }
    }

    public function assertFalse($condition, string $message = ''): void
    {
        if ($condition) {
            throw new \AssertionError($message ?: "Failed asserting that condition is false.");
        }
    }

    public function assertNotEmpty($value, string $message = ''): void
    {
        if (empty($value)) {
            throw new \AssertionError($message ?: "Failed asserting that value is not empty.");
        }
    }

    public function assertIsArray($value, string $message = ''): void
    {
        if (!is_array($value)) {
            throw new \AssertionError($message ?: "Failed asserting that value is an array.");
        }
    }

    public function assertGreaterThan($expected, $actual, string $message = ''): void
    {
        if ($actual <= $expected) {
            throw new \AssertionError($message ?: "Failed asserting that {$actual} is greater than {$expected}.");
        }
    }

    public function assertFileExists(string $filename, string $message = ''): void
    {
        if (!file_exists($filename)) {
            throw new \AssertionError($message ?: "Failed asserting that file '{$filename}' exists.");
        }
    }
}
