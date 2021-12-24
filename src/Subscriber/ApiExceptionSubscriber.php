<?php

namespace Rf\ApiBundle\Subscriber;


use Rf\ApiBundle\Exception\DtoValidationException;
use Rf\ApiBundle\Response\ErrorResponse;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Class ApiExceptionSubscriber
 * @package Rf\ApiBundle\Subscriber
 * @copyright Copyright (c) Ralf Frommherz
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    #[ArrayShape([KernelEvents::EXCEPTION => "string"])] public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'processException'
        ];
    }

    /**
     * Handles the api exception response
     *
     * @param ExceptionEvent $event
     */
    public function processException(ExceptionEvent $event): void
    {
        if($event->getThrowable() instanceof DtoValidationException) {
            $this->handleDtoValidationException($event);
        }
    }

    /**
     * @param ExceptionEvent $event
     */
    #[NoReturn] private function handleDtoValidationException(ExceptionEvent $event): void
    {
        /** @var DtoValidationException $dtoValidationException */
        $dtoValidationException = $event->getThrowable();
        $violations = $dtoValidationException->getViolations();

        $errorResponse = new ErrorResponse();
        $errorResponse->setStatusCode(Response::HTTP_BAD_REQUEST);

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $errorResponse->addError(
                $violation->getMessage(),
                [
                    'propertyPath' => $violation->getPropertyPath(),
                    'parameters' => $violation->getParameters()
                ]
            );
        }

        $event->setResponse($errorResponse);
    }
}