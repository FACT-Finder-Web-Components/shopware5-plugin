<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\GenericField;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SingleFields implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var GenericField[] */
    private $singleFields;

    /** @var array */
    private $pluginConfig;

    public function __construct(array $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function getFields(): array
    {
        if (!$this->singleFields) {
            $this->singleFields = array_map(function (string $columnName) {
                return (clone $this->container->get(GenericField::class))->setColumnName($columnName);
            }, (array) $this->pluginConfig['ffSingleFields']);
        }

        return $this->singleFields;
    }
}
