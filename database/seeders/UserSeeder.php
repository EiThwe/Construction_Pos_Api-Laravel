<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            "name" => "Tech Area",
            "phone" => "09955099985",
            "birth_date" => Carbon::now()->toDateString(),
            "join_date" =>  Carbon::now()->toDateString(),
            "gender" => "ကျား",
            "role" => "admin",
            "position" => "မန်နေဂျာ",
            "address" => "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Soluta fugit impedit magnam eos dicta nesciunt rem facilis laudantium alias minima dolorem consequuntur hic explicabo, ipsam at? Deserunt perspiciatis pariatur accusantium!            ",
            "salary" => 3000000,
            "password" => Hash::make("123123"),
        ]);
    }
}
