<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Output;

use SplFileObject as File;

class Csv implements StreamInterface
{
    /** @var File */
    private $handle;

    /** @var string */
    private $delimiter;

    public function __construct(string $fileName, string $delimiter)
    {
        $this->handle    = new File($fileName . '.csv', 'wr+');
        $this->delimiter = $delimiter;
    }

    public function addEntity(array $entity): void
    {
        $this->handle->fputcsv($entity, $this->delimiter);
    }

    public function getContent(): string
    {
        $this->handle->fpassthru();
        $this->handle->rewind();
        return $this->handle->fread($this->handle->getSize());
    }

    public function getFilename(): string
    {
        return $this->handle->getFilename();
    }
}
