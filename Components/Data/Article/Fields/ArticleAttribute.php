<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Formatter\NumberFormatter;
use OmikronFactfinder\Components\Service\TranslationService;
use Shopware\Bundle\AttributeBundle\Service\ConfigurationStruct as AttributeConfig;
use Shopware\Bundle\AttributeBundle\Service\TypeMapping as Type;
use Shopware\Models\Article\Detail;
use Shopware_Components_Snippet_Manager as SnippetManager;

class ArticleAttribute implements FieldInterface
{
    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var AttributeConfig */
    private $attributeConfig;

    /** @var TranslationService */
    private $translationService;

    /** @var SnippetManager */
    private $snippetManager;

    /** @var string */
    private $label;

    public function __construct(
        AttributeConfig $attribute,
        NumberFormatter $numberFormatter,
        TranslationService $translationService,
        SnippetManager $snippetManager,
        string $label
    ) {
        $this->attributeConfig    = $attribute;
        $this->numberFormatter    = $numberFormatter;
        $this->translationService = $translationService;
        $this->snippetManager     = $snippetManager;
        $this->label              = $label;
    }

    public function getName(): string
    {
        return $this->label;
    }

    public function getValue(Detail $detail): string
    {
        $attributeObj = $detail->getAttribute();
        $translation  = $this->translationService->getArticleTranslation($detail->getId());
        $snippets     = $this->snippetManager->getNamespace('backend/omikron/factfinder');

        $getter = 'get' . ucfirst($this->attributeConfig->getColumnName());
        $value  = $translation['__attribute_' . $this->attributeConfig->getColumnName()] ?:
            (method_exists($attributeObj, $getter) ? $attributeObj->{$getter}() : '');

        switch ($this->attributeConfig->getColumnType()) {
            case Type::TYPE_BOOLEAN:
                return $value ? $snippets->get('freeTextFieldBooleanYes') : $snippets->get('freeTextFieldBooleanNo');
            case Type::TYPE_DATETIME:
                return $value instanceof \DateTime ? $value->format('Uv') : (string) $value;
            case Type::TYPE_FLOAT:
                return $this->numberFormatter->format((float) $value);
            default:
                return (string) $value;
        }
    }
}
