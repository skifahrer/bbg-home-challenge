<?php

namespace App\DTO;

interface Dto
{
    public static function createFromArray(array $data): self;
    public function toArray(): array;
}
