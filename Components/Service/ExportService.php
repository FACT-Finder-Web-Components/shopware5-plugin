<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Data\Article\FieldProvider;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Filter\FilterInterface;
use OmikronFactfinder\Components\Output\StreamInterface;

class ExportService implements ExportServiceInterface
{
    /** @var DataProviderInterface */
    private $dataProvider;

    /** @var FilterInterface */
    private $filter;

    /** @var FieldProvider */
    private $fieldProvider;

    public function __construct(
        DataProviderInterface $dataProvider,
        FilterInterface $filter,
        FieldProvider $fieldProvider
    ) {
        $this->dataProvider  = $dataProvider;
        $this->filter        = $filter;
        $this->fieldProvider = $fieldProvider;
    }

    public function generate(StreamInterface $stream): void
    {
        $columns     = $this->fieldProvider->getColumns();
        $emptyRecord = array_combine($columns, array_fill(0, count($columns), ''));
        $stream->addEntity($columns);
        foreach ($this->dataProvider->getEntities() as $entity) {
            $entityData = array_merge($emptyRecord, array_intersect_key($entity->toArray(), $emptyRecord));
            $stream->addEntity($this->prepare($entityData));
        }
    }

    private function prepare(array $data): array
    {
        return array_map([$this->filter, 'filterValue'], $data);
    }
}
