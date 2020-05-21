<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Filter\FilterInterface;
use OmikronFactfinder\Components\Output\StreamInterface;

class ExportService implements ExportServiceInterface
{
    /** @var DataProviderInterface */
    private $dataProvider;

    /** @var FilterInterface */
    private $filter;

    /** @var array */
    private $columns;

    public function __construct(DataProviderInterface $dataProvider, FilterInterface $filter, array $columns)
    {
        $this->dataProvider = $dataProvider;
        $this->filter       = $filter;
        $this->columns      = $columns;
    }

    public function generate(StreamInterface $stream): void
    {
        $emptyRecord = array_combine($this->columns, array_fill(0, count($this->columns), ''));
//        $stream->addEntity($this->columns);
        foreach ($this->dataProvider->getEntities() as $entity) {
            $entityData = array_merge($emptyRecord, array_intersect_key($entity->toArray(), $emptyRecord));
            var_dump($entityData);
//            $stream->addEntity($entityData);
        }
    }

    private function prepare(array $data): array
    {
        return array_map([$this->filter, 'filterValue'], $data);
    }
}
