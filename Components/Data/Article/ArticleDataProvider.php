<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Entity\ExportArticle;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use Psr\Container\ContainerInterface;

class ArticleDataProvider implements DataProviderInterface
{
    /** @var ArticleDetails */
    private $articleDetails;

    /** @var ContainerInterface */
    private $container;

    public function __construct(ArticleDetails $articleDetails, ContainerInterface $container)
    {
        $this->articleDetails = $articleDetails;
        $this->container      = $container;
    }

    public function getEntities(): iterable
    {
        yield from []; // init generator: Prevent errors in case of an empty product collection
        foreach ($this->articleDetails as $detail) {
            yield $this->createEntity($detail);
        }
    }

    private function createEntity($detail): ExportEntityInterface
    {
        $entity = $this->container->get(ExportArticle::class);
        $entity->setDetail($detail);
        return $entity;
    }
}
