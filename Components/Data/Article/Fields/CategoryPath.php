<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Service\TranslationService;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Models\Article\Detail;
use Shopware\Models\Category\Category;

class CategoryPath implements FieldInterface
{
    /** @var string */
    private $fieldName;

    /** @var TranslationService */
    private $translationService;

    /** @var ContextServiceInterface */
    private $contextService;

    public function __construct(
        TranslationService $translationService,
        ContextServiceInterface $contextService,
        string $fieldName = 'CategoryPath'
    ) {
        $this->fieldName          = $fieldName;
        $this->contextService     = $contextService;
        $this->translationService = $translationService;
    }

    public function getName(): string
    {
        return $this->fieldName;
    }

    public function getValue(Detail $detail): string
    {
        $article        = $detail->getArticle();
        $categoryName   = $this->categoryName($article->getAllCategories());
        $rootCategoryId = $this->contextService->getShopContext()->getShop()->getCategory()->getId();

        return implode(
            '|',
            $article->getCategories()
                    ->filter(function (Category $category) use ($rootCategoryId) {
                        return (bool) strstr($category->getPath(), "|$rootCategoryId|");
                    })
                    ->map(function (Category $category) use ($categoryName) {
                        return implode('/', array_map($categoryName, $this->getPath($category)));
                    })->toArray()
        );
    }

    private function categoryName(array $allCategories): callable
    {
        $names = array_reduce($allCategories, function (array $result, Category $category) {
            $translation = $this->translationService->getCategoryTranslation($category->getId());
            return $result + [$category->getId() => $translation['description'] ?: $category->getName()];
        }, []);

        return function (int $categoryId) use ($names): string {
            return rawurlencode($names[$categoryId] ?? '');
        };
    }

    private function getPath(Category $category): array
    {
        return array_slice(array_reverse(explode('|', $category->getId() . $category->getPath())), 2);
    }
}
