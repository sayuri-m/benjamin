<?php
namespace Ebanx\Benjamin\Services\Validators;

abstract class BaseValidator
{
    protected $config;

    private $errors = array();

    abstract public function validate();

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    protected function addAllErrors($errors = array())
    {
        $this->errors = array_merge($this->errors, $errors);
    }

    private function addError($message)
    {
        $this->errors[] = $message;
    }
}