<?php

namespace Rf\ApiBundle\Response;


use Rf\ApiBundle\Annotations\ApiPublic;

/**
 * Class ErrorResponse
 * @package Rf\ApiBundle\Response
 * @copyright Copyright (c) Ralf Frommherz
 */
class ErrorResponse extends ApiResponse
{
    /**
     * @ApiPublic
     * @var array
     */
    private array $errors = [];

    /**
     * @param string $message
     * @param array $context
     */
    public function addError(string $message, array $context = []) : void
    {
        $this->errors[] = [
            'message' => $message,
            'context' => $context
        ];
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}