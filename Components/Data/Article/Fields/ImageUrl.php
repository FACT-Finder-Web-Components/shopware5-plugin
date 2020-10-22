<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Bundle\MediaBundle\MediaServiceInterface;
use Shopware\Models\Article\Detail;
use Shopware\Models\Article\Image;

class ImageUrl implements FieldInterface
{
    /** @var MediaServiceInterface */
    private $mediaService;

    /** @var string */
    private $imageSize;

    public function __construct(MediaServiceInterface $mediaService, string $imageSize)
    {
        $this->mediaService = $mediaService;
        $this->imageSize    = $imageSize;
    }

    public function getName(): string
    {
        return 'ImageUrl';
    }

    public function getValue(Detail $detail): string
    {
        /** @var Image $image */
        $image = $detail->getArticle()->getImages()->filter($this->isMain())->first();
        $thumb = $image ? $image->getMedia()->getThumbnails()[$this->imageSize] ?? '' : '';
        return (string) $this->mediaService->getUrl($thumb);
    }

    private function isMain(): callable
    {
        return function (Image $image): bool {
            return $image->getMain() === 1;
        };
    }
}
