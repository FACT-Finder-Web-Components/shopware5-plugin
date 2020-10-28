<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Bundle\AttributeBundle\Service\ConfigurationStruct as AttributeConfig;
use Shopware\Bundle\AttributeBundle\Service\TypeMapping as Type;
use Shopware\Models\Article\Detail;

class ArticleAttribute implements FieldInterface
{
    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var AttributeConfig */
    private $attributeConfig;

    /** @var string */
    private $label;

    public function __construct(AttributeConfig $attribute, NumberFormatter $numberFormatter, string $label)
    {
        $this->attributeConfig = $attribute;
        $this->numberFormatter = $numberFormatter;
        $this->label           = $label;
    }

    public function getName(): string
    {
        return $this->label;
    }

    public function getValue(Detail $detail): string
    {
        $attributeObj = $detail->getAttribute();
        $getter       = 'get' . ucfirst($this->attributeConfig->getColumnName());
        $value        = method_exists($attributeObj, $getter) ? $attributeObj->{$getter}() : '';

        switch ($this->attributeConfig->getColumnType()) {
            case Type::TYPE_BOOLEAN:
                return $value ? 'Yes' : 'No';
            case Type::TYPE_DATETIME:
                return $value instanceof \DateTime ? $value->format('Uv') : (string) $value;
            case Type::TYPE_FLOAT:
                return $this->numberFormatter->format((float) $value);
            default:
                return (string) $value;
        }
    }
}
