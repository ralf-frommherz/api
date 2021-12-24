<?php

namespace Rf\ApiBundle\Subscriber;


use Rf\ApiBundle\Response\ApiResponse;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Serializes the response if ApiResponse is used
 *
 * Class ApiResponseSubscriber
 * @package Rf\ApiBundle\Subscriber
 * @copyright Copyright (c) Ralf Frommherz
 */
class ApiResponseSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
            ResponseEvent::class => 'onKernelResponse'
        ];
    }

    /**
     * Serializes the api response objects
     * @param ResponseEvent $event
     */
    #[NoReturn] public function onKernelResponse(ResponseEvent $event) : void
    {
        $response = $event->getResponse();
        if(!$response instanceof ApiResponse) {
            return;
        }

        $response = new Response(
            $this->serializer->serialize($response, 'json'),
            $response->getStatusCode(),
            [
                'Content-Type' => 'application/json'
            ]
        );
        $event->setResponse($response);
    }
}