<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function seeder(){
        $this->seed([CategorySeeder::class,ProductSeeder::class]);
    }
    public function testOneToMany(): void
    {
        $this->seeder();

        $product = Product::find('1');
        self::assertNotNull($product);

        $category = $product->category;
        self::assertNotNull($category);
        self::assertEquals("FOOD",$category->id);
    }

    public function testHasOneOfMany()
    {
        $this->seeder();
        $category = Category::find("FOOD");
        self::assertNotNull($category);

        $cheapestProduct = $category->cheapestProduct;
        self::assertNotNull($cheapestProduct);
        self::assertEquals("1",$cheapestProduct->id);

        $mostExpensiveProduct = $category->mostExpensiveProduct;
        self::assertNotNull($mostExpensiveProduct);
        self::assertEquals("2",$mostExpensiveProduct->id);
    }

     public function testOneToOnePolymorphic()
    {
        $this->seed([CustomerSeeder::class,CategorySeeder::class,ProductSeeder::class ,ImageSeeder::class]);

        $product = Product::find("1");
        self::assertNotNull($product);

        $image = $product->image;
        self::assertNotNull($image);

        self::assertEquals("www.ivriel.my.id/image/2.jpg",$image->url);
    }
    
}
