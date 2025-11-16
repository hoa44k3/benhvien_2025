<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Revenue;
use Carbon\Carbon;
class RevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            Revenue::create([
                'date' => Carbon::create(2024, 12, $i),
                'amount' => rand(5_000_000, 20_000_000),
                'note' => 'Doanh thu ngày ' . $i,
            ]);
        }
    }
}
