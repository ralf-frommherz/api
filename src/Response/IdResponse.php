<?php

namespace Rf\ApiBundle\Response;


use Rf\ApiBundle\Dto\ApiDto;
use Rf\ApiBundle\Annotations\ApiPublic;
use Rf\ApiBundle\Dto\CallbackDto;
use Rf\ApiBundle\Dto\IdDto;

/**
 * Class IdResponse
 * @package Rf\ApiBundle\Response
 * @copyright Copyright (c) Ralf Frommherz
 */
class IdResponse extends ApiResponse
{
    /**
     * @ApiPublic
     * @var string $id
     */
    protected string $id;

    /**
     * @param IdDto $dto
     * @param int $status
     * @param array $headers
     */
    public function __construct(IdDto $dto, int $status = 200, array $headers = [])
    {
        parent::__construct('', $status, $headers);
        $this->id = $dto->getId();
    }

    /**
     * @param IdDto $dto
     * @return static
     */
    public static function createFromIdDto(IdDto $dto) : self
    {
        return new self($dto);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}