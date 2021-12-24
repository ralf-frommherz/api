<?php

namespace Rf\ApiBundle\Exception;


use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Class DtoValidationException
 * @package Rf\ApiBundle\Exception
 * @copyright Copyright (c) Ralf Frommherz
 */
class DtoValidationException extends ApiException
{
    private ConstraintViolationListInterface $violations;

    /**
     * @param ConstraintViolationListInterface $violations
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    #[Pure] public function __construct(ConstraintViolationListInterface $violations, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->violations = $violations;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}