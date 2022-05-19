<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\FieldRoles;

class Mapper
{
    public function map(array $fieldRoles): array
    {
        $getRole = $this->getOrEmptyString($fieldRoles);

        return [
            'brand'                 => $getRole('brand'),
            'campaignProductNumber' => $getRole('productNumber'),
            'deeplink'              => $getRole('deeplink'),
            'description'           => $getRole('description'),
            'displayProductNumber'  => $getRole('productNumber'),
            'ean'                   => $getRole('ean'),
            'imageUrl'              => $getRole('imageUrl'),
            'masterArticleNumber'   => $getRole('masterId'),
            'price'                 => $getRole('price'),
            'productName'           => $getRole('productName'),
            'trackingProductNumber' => $getRole('productNumber'),
        ];
    }

    private function getOrEmptyString(array $fieldRoles): callable
    {
        return function (string $key) use ($fieldRoles) {
            return $fieldRoles[$key] ?? '';
        };
    }
}
