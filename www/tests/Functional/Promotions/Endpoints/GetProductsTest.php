<?php
namespace Tests\Functional\Promotions\Endpoints;

use Tests\Functional\FunctionalTestCase;

class GetProductsTest extends FunctionalTestCase
{
    const TEST_CATEGORIES = [
        [
            'id' => 1,
            'name' => 'boots'
        ],
        [
            'id' => 2,
            'name' => 'sandals'
        ],
        [
            'id' => 3,
            'name' => 'sneakers'
        ],
    ];

    CONST TEST_PRODUCTS = [
        [
            'id' => 1,
            'sku' => '000001',
            'name' => 'BV Lean leather ankle boots',
            'category_id' => self::TEST_CATEGORIES[0]['id']
        ],
        [
            'id' => 2,
            'sku' => '000002',
            'name' => 'BV Lean leather ankle boots',
            'category_id' => self::TEST_CATEGORIES[0]['id']
        ],
        [
            'id' => 3,
            'sku' => '000003',
            'name' => 'Ashlington leather ankle boots',
            'category_id' => self::TEST_CATEGORIES[0]['id']
        ],
        [
            'id' => 4,
            'sku' => '000004',
            'name' => 'Naima embellished suede sandals',
            'category_id' => self::TEST_CATEGORIES[1]['id']
        ],
        [
            'id' => 5,
            'sku' => '000005',
            'name' => 'Nathane leather sneakers',
            'category_id' => self::TEST_CATEGORIES[2]['id']
        ],
        [
            'id' => 6,
            'sku' => '000006',
            'name' => 'Nathane leather sneakers',
            'category_id' => self::TEST_CATEGORIES[2]['id']
        ],
    ];

    const TEST_PRICES = [
        [
            'product_id' => self::TEST_PRODUCTS[0]['id'],
            'amount' => 89000,
            'currency' => 'EUR',
            'start_at' => '2021-09-01',
            'end_at' => '2022-09-01'
        ],
        [
            'product_id' => self::TEST_PRODUCTS[1]['id'],
            'amount' => 99000,
            'currency' => 'EUR',
            'start_at' => '2021-09-01',
            'end_at' => '2022-09-01'
        ],
        [
            'product_id' => self::TEST_PRODUCTS[2]['id'],
            'amount' => 71000,
            'currency' => 'EUR',
            'start_at' => '2021-09-01',
            'end_at' => '2022-09-01'
        ],
        [
            'product_id' => self::TEST_PRODUCTS[3]['id'],
            'amount' => 79500,
            'currency' => 'EUR',
            'start_at' => '2021-09-01',
            'end_at' => '2022-09-01'
        ],
        [
            'product_id' => self::TEST_PRODUCTS[4]['id'],
            'amount' => 59000,
            'currency' => 'EUR',
            'start_at' => '2021-09-01',
            'end_at' => '2022-09-01'
        ],
        [
            'product_id' => self::TEST_PRODUCTS[5]['id'],
            'amount' => 69000,
            'currency' => 'EUR',
            'start_at' => '2021-09-01',
            'end_at' => '2022-09-01'
        ],
    ];

    const TEST_DISCOUNTS = [
        [
            'product_id' => null,
            'category_id' => self::TEST_CATEGORIES[0]['id'],
            'percentage' => 30,
            'start_at' => '2021-09-01',
            'end_at' => '2022-09-01'
        ],
        [
            'product_id' => self::TEST_PRODUCTS[2]['id'],
            'category_id' => null,
            'percentage' => 30,
            'start_at' => '2021-09-01',
            'end_at' => '2022-09-01'
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        foreach (self::TEST_CATEGORIES as $testCategory) {
            $this->dbConnection->insert(
                'categories',
                $testCategory
            );
        }

        foreach (self::TEST_PRODUCTS as $testProduct) {
            $this->dbConnection->insert(
                'products',
                $testProduct
            );
        }

        foreach (self::TEST_PRICES as $testPrice) {
            $this->dbConnection->insert(
                'prices',
                $testPrice
            );
        }

        foreach (self::TEST_DISCOUNTS as $testDiscount) {
            $this->dbConnection->insert(
                'discounts',
                $testDiscount
            );
        }
    }

    public function testWhenWeDontSendCategoryAndPriceLessThanFilterWeGetAllPrices(): void
    {
        $response = $this->request(
            'GET',
            '/products',
        );

        $this->assertEquals(200, $response->getStatusCode());
        $products = json_decode($response->getBody(), true);

        $this->assertCount(5, $products, 'Incorrect number of retrieved products');

        // With more time this can be a JsonSchema validation so we can do a full check of structure and data types
        foreach ($products as $product) {
            $this->assertArrayHasKey('sku', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('category', $product);
            $this->assertArrayHasKey('price', $product);

            $this->assertArrayHasKey('original', $product['price']);
            $this->assertArrayHasKey('final', $product['price']);
            $this->assertArrayHasKey('discount_percentage', $product['price']);
            $this->assertArrayHasKey('currency', $product['price']);
        }
    }

    public function testWhenWeSendCategoryButNotPriceLessThanFilterWeGetPrices(): void
    {
        $response = $this->request(
            'GET',
            '/products?category=' . self::TEST_CATEGORIES[1]['name'],
        );

        $this->assertEquals(200, $response->getStatusCode());
        $products = json_decode($response->getBody(), true);

        $this->assertCount(1, $products, 'Incorrect number of retrieved products');

        $this->assertEquals($products[0]['sku'], self::TEST_PRODUCTS[3]['sku']);
    }

    public function testWhenWeDontSendCategoryButSendPriceLessThanFilterWeGetPrices(): void
    {
        $response = $this->request(
            'GET',
            '/products?price_less_than=' . self::TEST_PRICES[5]['amount'],
        );

        $this->assertEquals(200, $response->getStatusCode());
        $products = json_decode($response->getBody(), true);

        $this->assertCount(2, $products, 'Incorrect number of retrieved products');

        foreach ($products as $product) {
            $this->assertTrue(in_array($product['sku'], [self::TEST_PRODUCTS[4]['sku'], self::TEST_PRODUCTS[5]['sku']]));
        }
    }

    public function testWhenWeSendCategoryAndPriceLessThanFilterWeGetPrices(): void
    {
        $response = $this->request(
            'GET',
            '/products?category=' . self::TEST_CATEGORIES[0]['name'] . '&price_less_than=' . self::TEST_PRICES[2]['amount'],
        );

        $this->assertEquals(200, $response->getStatusCode());
        $products = json_decode($response->getBody(), true);

        $this->assertCount(1, $products, 'Incorrect number of retrieved products');

        $this->assertEquals($products[0]['sku'], self::TEST_PRODUCTS[2]['sku']);
    }
}
