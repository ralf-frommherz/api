<?php

namespace Rf\ApiBundle\ArgumentResolver;


use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionException;
use Rf\ApiBundle\Dto\ApiDto;
use Rf\ApiBundle\Exception\DtoValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FetchArgumentResolver
 * @package Rf\ApiBundle\ArgumentResolver
 * @author Ralf Frommherz
 */
class FetchArgumentResolver implements ArgumentValueResolverInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     * @throws ReflectionException
     */
    #[Pure] public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $reflectionClass = new ReflectionClass($argument->getType());
        return $reflectionClass->isSubclassOf(ApiDto::class);
    }

    /**
     * Resolves the fetch request dto
     *
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return void
     * @throws ReflectionException
     */
    #[NoReturn] public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $reflectionClass = new ReflectionClass($argument->getType());
        $dto = $reflectionClass->newInstance();

        foreach ($request->request->all() as $key => $value) {
            if($propertyAccessor->isWritable($dto, $key)) {
                $propertyAccessor->setValue($dto, $key, $value);
            }
        }

        $violations = $this->validator->validate($dto);

        if($violations->count() > 0) {
            throw new DtoValidationException($violations);
        }

        yield $dto;
    }
}