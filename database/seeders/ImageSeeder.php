<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $image = new Image;
        $image->url = 'www.ivriel.my.id/image/1.jpg';
        $image->imageable_id = 'IVRIEL';
        $image->imageable_type = 'customer';
        $image->save();

        $image = new Image;
        $image->url = 'www.ivriel.my.id/image/2.jpg';
        $image->imageable_id = '1';
        $image->imageable_type = 'product';
        $image->save();
    }
}
