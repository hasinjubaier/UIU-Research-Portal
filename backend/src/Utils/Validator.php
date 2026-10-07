<?php

namespace App\Utils;

use App\Exceptions\ValidationException;

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    public function validate(): array
    {
        foreach ($this->rules as $field => $fieldRules) {
            $rulesList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);
            $value = $this->data[$field] ?? null;

            foreach ($rulesList as $rule) {
                $params = [];
                if (strpos($rule, ':') !== false) {
                    [$ruleName, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $rule;
                }

                $this->applyRule($field, $value, $ruleName, $params);
            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException('Validation failed', $this->errors);
        }

        return $this->data;
    }

    public function fails(): bool
    {
        try {
            $this->validate();
            return false;
        } catch (ValidationException $e) {
            return true;
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function applyRule(string $field, mixed $value, string $rule, array $params): void
    {
        switch ($rule) {
            case 'required':
                if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                    $this->addError($field, "The {$field} field is required.");
                }
                break;

            case 'email':
                if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "The {$field} must be a valid email address.");
                }
                break;

            case 'min':
                $min = (int)($params[0] ?? 0);
                if (is_string($value) && mb_strlen($value) < $min) {
                    $this->addError($field, "The {$field} must be at least {$min} characters.");
                } elseif (is_numeric($value) && $value < $min) {
                    $this->addError($field, "The {$field} must be at least {$min}.");
                } elseif (is_array($value) && count($value) < $min) {
                    $this->addError($field, "The {$field} must contain at least {$min} items.");
                }
                break;

            case 'max':
                $max = (int)($params[0] ?? 0);
                if (is_string($value) && mb_strlen($value) > $max) {
                    $this->addError($field, "The {$field} may not be greater than {$max} characters.");
                } elseif (is_numeric($value) && $value > $max) {
                    $this->addError($field, "The {$field} may not be greater than {$max}.");
                } elseif (is_array($value) && count($value) > $max) {
                    $this->addError($field, "The {$field} may not contain more than {$max} items.");
                }
                break;

            case 'in':
                if ($value !== null && $value !== '' && !in_array($value, $params)) {
                    $this->addError($field, "The {$field} must be one of: " . implode(', ', $params));
                }
                break;

            case 'numeric':
                if ($value !== null && $value !== '' && !is_numeric($value)) {
                    $this->addError($field, "The {$field} must be a number.");
                }
                break;

            case 'integer':
                if ($value !== null && $value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $this->addError($field, "The {$field} must be an integer.");
                }
                break;

            case 'array':
                if ($value !== null && !is_array($value)) {
                    $this->addError($field, "The {$field} must be an array.");
                }
                break;

            case 'confirmed':
                $confirmationField = $field . '_confirmation';
                $confirmationValue = $this->data[$confirmationField] ?? null;
                if ($value !== $confirmationValue) {
                    $this->addError($field, "The {$field} confirmation does not match.");
                }
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
}
