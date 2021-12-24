<?php

namespace Rf\ApiBundle\Dto;


use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CallbackDto
 * @package Rf\ApiBundle\Dto
 * @author Ralf Frommherz <ralf@frommherz.me>
 */
abstract class CallbackDto extends IdDto
{
    /**
     * @Assert\NotBlank
     * @Assert\Url
     * @var string
     */
    private string $callback;

    /**
     * @return string
     */
    public function getCallback(): string
    {
        return $this->callback;
    }

    /**
     * @param string $callback
     */
    public function setCallback(string $callback): void
    {
        $this->callback = $callback;
    }
}