<?php

namespace OmikronFactfinder\Components\Data;

interface ExportEntityInterface
{
    public function getId(): int;

    public function toArray(): array;
}
