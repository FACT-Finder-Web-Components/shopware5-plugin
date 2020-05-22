<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Output\StreamInterface;

interface ExportServiceInterface
{
    public function generate(StreamInterface $stream): StreamInterface;
}
