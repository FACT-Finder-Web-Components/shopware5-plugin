<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Output;

use SplFileObject as File;

class Csv implements StreamInterface
{
    /** @var File */
    private $handle;

    public function __construct(File $handle)
    {
        $this->handle = $handle;
    }

    public function addEntity(array $entity): void
    {
        $this->handle->fputcsv($entity);
    }

    public function getContent(): string
    {
        $this->handle->rewind();
        return $this->handle->fread($this->handle->getSize());
    }

    public function getFilename(): string
    {
        return $this->handle->getFilename();
    }
}
