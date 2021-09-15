<?php
namespace Tests\Unit\Domains\Model\Entities;

use PHPUnit\Framework\TestCase;
use PromoTest\Domains\Promotions\Model\Entities\Product;
use PromoTest\Domains\Promotions\Model\ValueObjects\Category;
use PromoTest\Domains\Promotions\Model\ValueObjects\Discount;
use PromoTest\Domains\Promotions\Model\ValueObjects\Price;

class ProductsTest extends TestCase
{
    public function testGetBestDiscountPercentage(): void
    {
        $lowerDiscount = new Discount(10);
        $midDiscount = new Discount(30);
        $higherDiscount = new Discount(50);
        $product = new Product(
            'sku',
            'name',
            new Category(1,'category'),
            new Price(
                10000,
                'EUR'
            ),
            [
                $lowerDiscount,
                $midDiscount,
                $higherDiscount
            ]
        );

        $this->assertEquals($higherDiscount->getDiscountPercentage(), $product->getBestDiscountPercentage());
    }

    public function testGetBestDiscountedPriceAmountNoDiscount(): void
    {
        $price = new Price(
            10000,
            'EUR'
        );

        $product = new Product(
            'sku',
            'name',
            new Category(1,'category'),
            $price,
            []
        );

        $this->assertNull($product->getBestDiscountPercentage());

        $this->assertEquals($price->getAmount(), $product->getBestDiscountedPriceAmount());
    }

    public function testGetBestDiscountedPriceAmount(): void
    {
        $discount = new Discount(40);

        $price = new Price(
            10000,
            'EUR'
        );

        $product = new Product(
            'sku',
            'name',
            new Category(1,'category'),
            $price,
            [$discount]
        );

        $expectedDiscountedPrice = 6000;
        $this->assertEquals($expectedDiscountedPrice, $product->getBestDiscountedPriceAmount());
    }
}
