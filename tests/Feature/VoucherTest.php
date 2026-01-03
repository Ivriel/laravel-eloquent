<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;

class VoucherTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateVoucher(): void
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->voucher_code = "446424434";
        $voucher->save();

        self::assertNotNull($voucher->id);
    }

      public function testCreateVoucherUUID(): void
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->save();

        self::assertNotNull($voucher->id);
        self::assertNotNull($voucher->voucher_code);
    }

    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);

        $voucher  = Voucher::where('name','=','Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::where('name','=','Sample Voucher')->first();
        self::assertNull($voucher);

          $voucher = Voucher::withTrashed()->where('name','=','Sample Voucher')->first();
        self::assertNotNull($voucher);
    }

    public function testLocalScope()
    {
      $voucher = new Voucher();
      $voucher->name = "Sample Voucher";
      $voucher->is_active = true;
      $voucher->save();

      $total = Voucher::active()->count();
      self::assertEquals(1,$total);

      
      $total = Voucher::nonActive()->count();
      self::assertEquals(0,$total);
    }
}
