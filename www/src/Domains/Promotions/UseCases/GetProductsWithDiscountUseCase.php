<?php
namespace PromoTest\Domains\Promotions\UseCases;

use PromoTest\Domains\Promotions\DTOs\GetProductsWithDiscountUseCaseDTO;
use PromoTest\Domains\Promotions\DTOs\GetProductsWithDiscountUseCaseResponseDTO;
use PromoTest\Domains\Promotions\Repositories\ProductsRepositoryInterface;

class GetProductsWithDiscountUseCase
{

    private const MAX_PRODUCTS_PER_QUERY = 5;

    private ProductsRepositoryInterface $productsRepository;

    public function __construct(
        ProductsRepositoryInterface $productsRepository
    )
    {
        $this->productsRepository = $productsRepository;
    }

    public function __invoke(GetProductsWithDiscountUseCaseDTO $useCaseDTO): GetProductsWithDiscountUseCaseResponseDTO
    {
        $products = $this->productsRepository->getProductsByCategoryWithPriceLessThan(
            $useCaseDTO->getCategory(),
            $useCaseDTO->getPriceIsLessThan(),
            self::MAX_PRODUCTS_PER_QUERY
        );

        $responseDto = new GetProductsWithDiscountUseCaseResponseDTO();
        foreach ($products as $product) {
            $responseDto->addProduct(
                (object)[
                    "sku" => $product->getSku(),
                    "name" => $product->getName(),
                    "category" => $product->getCategory()->getName(),
                    "price" => (object)[
                        "original" => $product->getPrice()->getAmount(),
                        "final" => $product->getBestDiscountedPriceAmount(),
                        //NOTE: We should not be dealing here with the logic to add a % symbol. This is a frontend concern.
                        "discount_percentage" => is_null($product->getBestDiscountPercentage()) ?
                            $product->getBestDiscountPercentage() : (string)$product->getBestDiscountPercentage() . '%',
                        "currency" => $product->getPrice()->getCurrency()
                    ]
                ]
            );
        }

        return $responseDto;
    }
}
