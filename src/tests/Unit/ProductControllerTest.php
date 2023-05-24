<?php

declare(strict_types=1);

namespace Tests\Unit;

use MyApp\Controllers\ProductController;
use MyApp\Models\Products;

class ProductControllerTest extends AbstractUnitTest
//class UnitTest extends \PHPUnit\Framework\TestCase
{
    public function testProduct()
    {
        $product = new ProductController();
        $data = new Products();
        $data->name = "book";
        $data->price = 500;
        $data->qty = 10;
        $data->type = "Electronics";
        $this->assertTrue($product->validateName($data->name), "pass");
        $this->assertTrue($product->validateInt($data->price), "pass");
        $this->assertTrue($product->validateInt($data->qty), "pass");
        $this->assertTrue($product->validateCatageory($data->type), "pass");
    }
}
