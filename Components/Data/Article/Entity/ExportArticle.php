<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Entity;

use OmikronFactfinder\Components\Data\Article\FieldProvider;
use OmikronFactfinder\Components\Data\Article\Fields\FieldInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Service\TranslationService;
use Shopware\Models\Article\Detail;

class ExportArticle implements ExportEntityInterface
{
    /** @var FieldProvider */
    protected $fieldProvider;

    /** @var Detail */
    protected $detail;

    /** @var TranslationService */
    private $translationService;

    public function __construct(FieldProvider $fieldProvider, TranslationService $translationService)
    {
        $this->fieldProvider      = $fieldProvider;
        $this->translationService = $translationService;
    }

    public function setDetail(Detail $detail)
    {
        $this->detail = $detail;
    }

    public function getId(): int
    {
        return (int) $this->detail->getId();
    }

    public function toArray(): array
    {
        $article      = $this->detail->getArticle();
        $translations = $this->translationService->getArticleTranslation($article->getId());

        $data = [
            'ProductNumber' => (string) $this->detail->getNumber(),
            'Master'        => (string) $article->getMainDetail()->getNumber(),
            'Name'          => (string) ($translations['name'] ?? $article->getName()),
            'EAN'           => (string) $this->detail->getEan(),
            'Weight'        => (float) $this->detail->getWeight(),
            'Description'   => (string) ($translations['descriptionLong'] ?? $article->getDescriptionLong()),
            'Short'         => (string) ($translations['description'] ?? $article->getDescription()),
            'Brand'         => (string) $article->getSupplier()->getName(),
            'HasVariants'   => $this->detail->getKind() === 1 && count($article->getDetails()) > 1 ? 1 : 0,
        ];

        return array_reduce($this->fieldProvider->getFields(), function (array $fields, FieldInterface $field) {
            return $fields + [$field->getName() => $field->getValue($this->detail)];
        }, $data);
    }
}
