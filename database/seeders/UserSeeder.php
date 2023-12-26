<?php

namespace Database\Seeders;

use App\Models\User;
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
            "birth_date" => "01-12-2000",
            "join_date" => "22-12-2022",
            "gender" => "male",
            "role" => "admin",
            "address" => "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Soluta fugit impedit magnam eos dicta nesciunt rem facilis laudantium alias minima dolorem consequuntur hic explicabo, ipsam at? Deserunt perspiciatis pariatur accusantium!            ",
            "salary" => 3000000,
            "password" => Hash::make("123123"),
        ]);
    }
}
