<?php
namespace PromoTest\Infrastructure\Promotions\Controllers;


use PromoTest\Domains\Promotions\DTOs\GetProductsWithDiscountUseCaseDTO;
use PromoTest\Domains\Promotions\UseCases\GetProductsWithDiscountUseCase;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

class ProductController
{
    private GetProductsWithDiscountUseCase $useCase;

    public function __construct(
        GetProductsWithDiscountUseCase $useCase
    ) {
        $this->useCase = $useCase;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getParams();

        $category = null;
        if (array_key_exists('category', $params)) {
            $category = $params['category'];
        }

        $priceLessThan = null;
        if (array_key_exists('price_less_than', $params)) {
            $priceLessThan = $params['price_less_than'];
        }

        $useCaseDto = new GetProductsWithDiscountUseCaseDTO($category, $priceLessThan);
        $result = $this->useCase->__invoke($useCaseDto);

        return $response->withStatus(StatusCode::HTTP_OK)->withJson($result->getProducts());
    }
}
