<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvestmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inv1 = Investment::create([
            'opportunity_id' => 1,
            'investor_id' => 1,
            'amount' => 100000
        ]);

        $inv2 = Investment::create([
            'opportunity_id' => 2,
            'investor_id' => 1,
            'amount' => 150000
        ]);

        $inv3 = Investment::create([
            'opportunity_id' => 3,
            'investor_id' => 1,
            'amount' => 175000
        ]);
    }
}
