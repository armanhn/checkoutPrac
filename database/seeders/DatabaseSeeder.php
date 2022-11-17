<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     ProductSeeder::class
        // ]);
        // \App\Models\User::factory(10)->create();

        \App\Models\Product::factory()->create([
            'name' => 'INSTRUCTOR TRAINING FULL COURSE',
            'price' => '835',
        ]);
        \App\Models\Product::factory()->create([
            'name' => '7 HRS DRIVING & 7 HRS OBSERVATION PLUS ROAD TEST',
            'price' => '470',
        ]);
        \App\Models\Product::factory()->create([
            'name' => 'PACKAGE(10 HOURS LESSON+ DPS ROAD TEST)',
            'price' => '560',
        ]);
        \App\Models\Product::factory()->create([
            'name' => 'ADULT EDUCATION - (6 HOURS)',
            'price' => '84',
        ]);
        \App\Models\Product::factory()->create([
            'name' => 'DPS ROAD TEST - (2ND TIME)(INCLUDES $3 PROCESSING FEE)',
            'price' => '48',
        ]);
        \App\Models\Product::factory()->create([
            'name' => 'DPS ROAD TEST - (1ST TIME)(INCLUDES $4 PROCESSING FEE)',
            'price' => '94',
        ]);
        \App\Models\Product::factory()->create([
            'name' => 'DRIVING LESSON - BEHIND THE WHEEL ADULT AND TEEN - (2 HOURS)',
            'price' => '94',
        ]);
        \App\Models\Product::factory()->create([
            'name' => 'TEEN CLASS ROOM AND DRIVING (32 HR. CLASS ROOM + 7HRS. DRIVING & 7 HRS. OBSERVATION)',
            'price' => '480',
        ]);
        \App\Models\Product::factory()->create([
            'name' => 'PRACTICE TEST(INCLUDING $3 TAX)',
            'price' => '48',
        ]);
    }
}
