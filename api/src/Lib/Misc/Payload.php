<?php

declare(strict_types = 1);

namespace App\Lib\Misc;

use JsonSerializable;
use App\Enums\StatusCodes;
use App\Enums\ReasonPhrases;

class Payload implements JsonSerializable
{

    public function __construct(
        private StatusCodes $statusCode = StatusCodes::HTTP_200,
        private array $data = []
    ) {
    }

    public function getStatusCode(): StatusCodes
    {
        return $this->statusCode;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        return [
            'meta' => [
                'code' => $this->statusCode->value,
                'text' => ReasonPhrases::fromStatusCode($this->statusCode),
            ],
            'data' => $this->data,
        ];
    }

}