<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        AppSetting::create([
            'name' => 'Company Name',
            'phone' => '123-456-7890',
            'email' => 'info@example.com',
            'address' => '123 Main St, City',
            'google_map_url' => 'https://maps.google.com/example',
            'user_id' => 1,
            'logo' => "https://i.postimg.cc/FzL0bSm3/pngtree-building-and-construction-logo-design-template-image-317780.jpg"
        ]);
    }
}
