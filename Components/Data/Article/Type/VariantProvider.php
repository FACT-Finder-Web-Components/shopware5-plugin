<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

class VariantProvider extends BaseArticle
{
    /** @var array */
    private $data;

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
                'ProductNumber' => (string) $this->detail->getNumber(),
                'Availability'  => (int) $this->detail->getActive(),
            ] + $this->data + parent::toArray();
    }
}
