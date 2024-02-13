<?php

namespace App\Dtos;

class GradeDTO
{
    public function __construct(public string $type, public mixed $value)
    {
    }
}
