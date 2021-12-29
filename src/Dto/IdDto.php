<?php

namespace Rf\ApiBundle\Dto;


use Ramsey\Uuid\Uuid;

/**
 * Class IdDto
 * @package Rf\ApiBundle\Dto
 * @author Ralf Frommherz
 */
class IdDto extends ApiDto
{
    /**
     * @var string
     */
    private string $id;

    /**
     *
     */
    public function __construct()
    {
        $this->id = Uuid::uuid6();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}