<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = "Ivriel";
        $person->last_name = "Gunawan";
        $person->save();

        self::assertEquals("IVRIEL Gunawan",$person->full_name);
        $person->full_name = "Ivriel Dei";
        $person->save();

        self::assertEquals("IVRIEL",$person->first_name);
        self::assertEquals("Dei",$person->last_name);
    }
     public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = "Ivriel";
        $person->last_name = "Gunawan";
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);

        self::assertInstanceOf(Carbon::class,$person->created_at);
        self::assertInstanceOf(Carbon::class,$person->updated_at);
    }

    public function testCustomCasts()
    {
        $person = new Person();
        $person->first_name = "Ivriel";
        $person->last_name = "Gunawan";
        $person->address = new Address("Jl. Mawar","Bandar Lampung","Indonesia","12345");
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);

        self::assertInstanceOf(Carbon::class,$person->created_at);
        self::assertInstanceOf(Carbon::class,$person->updated_at);

        self::assertEquals("Jl. Mawar",$person->address->street);
        self::assertEquals("Bandar Lampung",$person->address->city);
        self::assertEquals("Indonesia",$person->address->country);
        self::assertEquals("12345",$person->address->postal_code);
    }
}
