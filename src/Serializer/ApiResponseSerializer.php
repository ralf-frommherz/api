<?php

namespace Rf\ApiBundle\Serializer;


use Rf\ApiBundle\Annotations\ApiPublic;
use Rf\ApiBundle\Response\ApiResponse;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class ApiResponseSerializer
 * @package Rf\ApiBundle\Serializer
 * @copyright Copyright (c) Ralf Frommherz
 */
class ApiResponseSerializer implements NormalizerInterface
{
    private Reader $reader;
    private const GETTER_PREFIXES = ['get', 'is'];

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Only ass properties with @ApiPublic to the result
     *
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     * @return array
     * @throws ReflectionException
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $reflectionClass = new ReflectionClass($object);
        $values = [];

        foreach ($reflectionClass->getProperties() as $property) {
            if(!$this->reader->getPropertyAnnotation($property, ApiPublic::class)) {
                continue;
            }

            foreach (self::GETTER_PREFIXES as $getterPrefix) {
                $getter = $getterPrefix.ucfirst($property->getName());

                if($reflectionClass->hasMethod($getter)) {
                    $values[$property->getName()] = $reflectionClass->getMethod($getter)->invoke($object);
                }
            }
        }

        return $values;
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof ApiResponse;
    }
}