<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function seeder()
    {
        $this->seed([CustomerSeeder::class,WalletSeeder::class]);
    }
    public function testOneToOne(): void
    {
        $this->seeder();

        $customer = Customer::find("IVRIEL");
        self::assertNotNull($customer);

        $wallet = $customer->wallet;
        self::assertNotNull($wallet);

        self::assertEquals(1000000,$wallet->amount);
    }

    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = "IVRIEL";
        $customer->name = "Ivriel";
        $customer->email = "ivriel@gmail.com";
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1000000;

        $customer->wallet()->save($wallet);

        self::assertNotNull($wallet->customer_id);
    }

    public function testOneToManyQuery()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = true;
        $category->save();

        $product = new Product();
        $product->id = "1";
        $product->name = "Product 1";
        $product->description = "Description 1";

        $category->products()->save($product);

        self::assertNotNull($product->category_id);
    }

    public function testHasOneThrough() 
    {
           $this->seed([CustomerSeeder::class,WalletSeeder::class,VirtualAccountSeeder::class]);

        $customer = Customer::find("IVRIEL");
        self::assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        self::assertNotNull($virtualAccount);
        self::assertEquals("BCA",$virtualAccount->bank);
    }

    public function testManyToMany(){
        $this->seed([CustomerSeeder::class,CategorySeeder::class,ProductSeeder::class]);

        $customer = Customer::find("IVRIEL");
        self::assertNotNull($customer);

        $customer->likeProducts()->attach("1");

        $products = $customer->likeProducts;
        self::assertCount(1,$products);

        self::assertEquals("1",$products[0]->id);
    }

    public function testManyToManyDetach()
    {
        $this->testManyToMany();

        $customer = Customer::find("IVRIEL");
        $customer->likeProducts()->detach("1");

        $products = $customer->likeProducts;
        self::assertCount(0,$products);
    }

    public function testPivotAttribute()
    {
        $this->testManyToMany();
        
        $customer = Customer::find("IVRIEL");
        $products = $customer->likeProducts;

        foreach ($products as $product) {
           $pivot = $product->pivot;
           self::assertNotNull($pivot);
           self::assertNotNull($pivot->customer_id);
           self::assertNotNull($pivot->product_id);
           self::assertNotNull($pivot->created_at);
        }
    }

    public function testPivotAttributeCondition()
    {
          $this->testManyToMany();
        
        $customer = Customer::find("IVRIEL");
        $products = $customer->likeProductsLastWeek;

        foreach ($products as $product) {
           $pivot = $product->pivot;  // objek model LIKE
           self::assertNotNull($pivot);
           self::assertNotNull($pivot->customer_id);
           self::assertNotNull($pivot->product_id);
           self::assertNotNull($pivot->created_at);

           self::assertNotNull($pivot->customer);
           self::assertNotNull($pivot->product);
        }
    }

    public function testOneToOnePolymorphic()
    {
        $this->seed([CustomerSeeder::class,ImageSeeder::class]);

        $customer = Customer::find("IVRIEL");
        self::assertNotNull($customer);

        $image = $customer->image;
        self::assertNotNull($image);

        self::assertEquals("www.ivriel.my.id/image/1.jpg",$image->url);
    }
}
