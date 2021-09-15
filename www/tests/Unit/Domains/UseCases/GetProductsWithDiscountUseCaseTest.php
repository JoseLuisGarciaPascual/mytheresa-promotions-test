<?php
namespace Tests\Unit\Domains\UseCases;

use PHPUnit\Framework\TestCase;
use PromoTest\Domains\Promotions\DTOs\GetProductsWithDiscountUseCaseDTO;
use PromoTest\Domains\Promotions\DTOs\GetProductsWithDiscountUseCaseResponseDTO;
use PromoTest\Domains\Promotions\Model\Entities\Product;
use PromoTest\Domains\Promotions\Model\ValueObjects\Category;
use PromoTest\Domains\Promotions\Model\ValueObjects\Discount;
use PromoTest\Domains\Promotions\Model\ValueObjects\Price;
use PromoTest\Domains\Promotions\Repositories\ProductsRepositoryInterface;
use PromoTest\Domains\Promotions\UseCases\GetProductsWithDiscountUseCase;

class GetProductsWithDiscountUseCaseTest extends TestCase
{
    private $productsRepository;

    public function setUp(): void
    {
        $this->productsRepository = $this->createMock(ProductsRepositoryInterface::class);
    }

    private function createSut(): GetProductsWithDiscountUseCase
    {
        return new GetProductsWithDiscountUseCase(
            $this->productsRepository
        );
    }

    public function testWhenWeDontHaveAnyDiscount(): void
    {
        $dto = new GetProductsWithDiscountUseCaseDTO(
            'category',
            10000
        );

        $product = new Product(
            'sku',
            'name',
            new Category(1, 'category'),
            new Price(10000, 'EUR'),
            []
        );


        $this->productsRepository
            ->expects($this->once())
            ->method('getProductsByCategoryWithPriceLessThan')
            ->with(
                $dto->getCategory(),
                $dto->getPriceIsLessThan(),
                $this->isType('integer')
            )
            ->willReturn([$product]);

        $sut = $this->createSut();
        $response = $sut->__invoke($dto);

        $this->assertInstanceOf(GetProductsWithDiscountUseCaseResponseDTO::class, $response);

        foreach ($response->getProducts() as $productResponse) {
            $this->assertObjectHasAttribute('sku', $productResponse);
            $this->assertEquals($product->getSku(), $productResponse->sku);

            $this->assertObjectHasAttribute('name', $productResponse);
            $this->assertEquals($product->getName(), $productResponse->name);

            $this->assertObjectHasAttribute('category', $productResponse);
            $this->assertEquals($product->getCategory()->getName(), $productResponse->category);

            $this->assertObjectHasAttribute('price', $productResponse);

            $this->assertObjectHasAttribute('original', $productResponse->price);
            $this->assertEquals($product->getPrice()->getAmount(), $productResponse->price->original);

            $this->assertObjectHasAttribute('final', $productResponse->price);
            $this->assertEquals($product->getBestDiscountedPriceAmount(), $productResponse->price->final);

            $this->assertObjectHasAttribute('discount_percentage', $productResponse->price);
            $this->assertNull($productResponse->price->discount_percentage);

            $this->assertObjectHasAttribute('currency', $productResponse->price);
            $this->assertEquals($product->getPrice()->getCurrency(), $productResponse->price->currency);
        }
    }

    public function testWhenWeHaveDiscounts(): void
    {
        $dto = new GetProductsWithDiscountUseCaseDTO(
            'category',
            10000
        );

        $product = new Product(
            'sku',
            'name',
            new Category(1, 'category'),
            new Price(10000, 'EUR'),
            [new Discount(40)]
        );


        $this->productsRepository
            ->expects($this->once())
            ->method('getProductsByCategoryWithPriceLessThan')
            ->with(
                $dto->getCategory(),
                $dto->getPriceIsLessThan(),
                $this->isType('integer')
            )
            ->willReturn([$product]);

        $sut = $this->createSut();
        $response = $sut->__invoke($dto);

        $this->assertInstanceOf(GetProductsWithDiscountUseCaseResponseDTO::class, $response);

        foreach ($response->getProducts() as $productResponse) {
            $this->assertObjectHasAttribute('sku', $productResponse);
            $this->assertEquals($product->getSku(), $productResponse->sku);

            $this->assertObjectHasAttribute('name', $productResponse);
            $this->assertEquals($product->getName(), $productResponse->name);

            $this->assertObjectHasAttribute('category', $productResponse);
            $this->assertEquals($product->getCategory()->getName(), $productResponse->category);

            $this->assertObjectHasAttribute('price', $productResponse);

            $this->assertObjectHasAttribute('original', $productResponse->price);
            $this->assertEquals($product->getPrice()->getAmount(), $productResponse->price->original);

            $this->assertObjectHasAttribute('final', $productResponse->price);
            $this->assertEquals($product->getBestDiscountedPriceAmount(), $productResponse->price->final);

            $this->assertObjectHasAttribute('discount_percentage', $productResponse->price);
            $this->assertEquals($product->getBestDiscountPercentage() . '%', $productResponse->price->discount_percentage);

            $this->assertObjectHasAttribute('currency', $productResponse->price);
            $this->assertEquals($product->getPrice()->getCurrency(), $productResponse->price->currency);
        }
    }
}
