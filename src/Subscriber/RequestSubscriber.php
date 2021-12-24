<?php

namespace Rf\ApiBundle\Subscriber;


use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RequestSubscriber
 * @package Rf\ApiBundle\Subscriber
 * @copyright Copyright (c) Ralf Frommherz
 */
class RequestSubscriber implements EventSubscriberInterface
{
    #[ArrayShape([KernelEvents::CONTROLLER => "string"])] public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::CONTROLLER => 'convertJsonStringToArray',
        ];
    }

    /**
     * @throws JsonException
     */
    public function convertJsonStringToArray(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getContentType() !== 'json' || !$request->getContent()) {
            return;
        }

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $request->request->replace(is_array($data) ? $data : []);
    }
}