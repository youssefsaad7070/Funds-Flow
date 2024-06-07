<?php

namespace Database\Seeders;

use App\Models\InvestmentOpportunity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentOpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $op1 = InvestmentOpportunity::create([
        //     'category_id' => 1,
        //     'business_id' => 2,
        //     'business_name' => 'SteelCompany',
        //     'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla. Integer tellus odio, iaculis a velit ut, egestas pellentesque felis. Nullam posuere quam ac quam cursus imperdiet. Nam eget porta risus. Sed hendrerit urna velit, vulputate malesuada elit pharetra sit amet.',
        //     'amount_needed'=> 250000,
        //     'remaining_amount' => 150000,
        //     'potential_risks' => '14%',
        //     'future_growth' => 'Consectetur urna',
        //     'products_or_services' => 'Aliquam justo',
        //     'returns_percentage' => '20%-40%',
        //     'company_valuation' => '28',
        //     'start_date' => now(),
        //     'end_date' => now(),
        //     'revenues' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla.',
        //     'net_profit' => 'Quisque et tincidunt mi. Etiam sed consectetur urna. Maecenas libero arcu, vestibulum sed ligula vitae, egestas tempor mi.',
        //     'profit_margin' => 'Sed dapibus mi dui. Aliquam justo justo, aliquam quis porttitor sed, pulvinar nec quam.',
        //     'cash_flow' => 'Etiam dignissim sem tellus, interdum placerat ex ultrices in. Ut pharetra diam eu luctus laoreet.',
        //     'ROI' => 'Aliquam sit amet mi sem. Vivamus quis imperdiet dui. Nam facilisis porta velit, eu congue nulla dictum et.',
        //     'photo' => 'OpportunityImages\Frame.png',
        //     'approved' => true
        // ]);

        // $op2 = InvestmentOpportunity::create([
        //     'category_id' => 2,
        //     'business_id' => 2,
        //     'business_name' => 'BuildingCompany',
        //     'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla. Integer tellus odio, iaculis a velit ut, egestas pellentesque felis. Nullam posuere quam ac quam cursus imperdiet. Nam eget porta risus. Sed hendrerit urna velit, vulputate malesuada elit pharetra sit amet.',
        //     'amount_needed'=> 350000,
        //     'remaining_amount' => 250000,
        //     'potential_risks' => '15%',
        //     'future_growth' => 'Consectetur urna',
        //     'products_or_services' => 'Aliquam justo',
        //     'returns_percentage' => '30%-40%',
        //     'company_valuation' => '28',
        //     'start_date' => now(),
        //     'end_date' => now(),
        //     'revenues' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla.',
        //     'net_profit' => 'Quisque et tincidunt mi. Etiam sed consectetur urna. Maecenas libero arcu, vestibulum sed ligula vitae, egestas tempor mi.',
        //     'profit_margin' => 'Sed dapibus mi dui. Aliquam justo justo, aliquam quis porttitor sed, pulvinar nec quam.',
        //     'cash_flow' => 'Etiam dignissim sem tellus, interdum placerat ex ultrices in. Ut pharetra diam eu luctus laoreet.',
        //     'ROI' => 'Aliquam sit amet mi sem. Vivamus quis imperdiet dui. Nam facilisis porta velit, eu congue nulla dictum et.',
        //     'photo' => 'OpportunityImages\Frame168.png',
        //     'approved' => true
        // ]);

        // $op3 = InvestmentOpportunity::create([
        //     'category_id' => 3,
        //     'business_id' => 2,
        //     'business_name' => 'MedicalCompany',
        //     'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla. Integer tellus odio, iaculis a velit ut, egestas pellentesque felis. Nullam posuere quam ac quam cursus imperdiet. Nam eget porta risus. Sed hendrerit urna velit, vulputate malesuada elit pharetra sit amet.',
        //     'amount_needed'=> 450000,
        //     'remaining_amount' => 350000,
        //     'potential_risks' => '16%',
        //     'future_growth' => 'Quisque vel',
        //     'products_or_services' => 'Aliquam justo',
        //     'returns_percentage' => '25%-40%',
        //     'company_valuation' => '28',
        //     'start_date' => now(),
        //     'end_date' => now(),
        //     'revenues' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla.',
        //     'net_profit' => 'Quisque et tincidunt mi. Etiam sed consectetur urna. Maecenas libero arcu, vestibulum sed ligula vitae, egestas tempor mi.',
        //     'profit_margin' => 'Sed dapibus mi dui. Aliquam justo justo, aliquam quis porttitor sed, pulvinar nec quam.',
        //     'cash_flow' => 'Etiam dignissim sem tellus, interdum placerat ex ultrices in. Ut pharetra diam eu luctus laoreet.',
        //     'ROI' => 'Aliquam sit amet mi sem. Vivamus quis imperdiet dui. Nam facilisis porta velit, eu congue nulla dictum et.',
        //     'photo' => 'OpportunityImages\Frame1689.png',
        //     'approved' => true
        // ]);

        $op1 = InvestmentOpportunity::create([
            'category_id' => 1,
            'business_id' => 2,
            'business_name' => 'SteelCompany',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla. Integer tellus odio, iaculis a velit ut, egestas pellentesque felis. Nullam posuere quam ac quam cursus imperdiet. Nam eget porta risus. Sed hendrerit urna velit, vulputate malesuada elit pharetra sit amet.',
            'amount_needed'=> 250000,
            'remaining_amount' => 250000,
            'potential_risks' => '14%',
            'future_growth' => 'Consectetur urna',
            'products_or_services' => 'Aliquam justo',
            'returns_percentage' => '20%-40%',
            'company_valuation' => '28',
            'start_date' => '2024-05-1',
            'end_date' => '2024-12-10',
            'revenues' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla.',
            'net_profit' => 'Quisque et tincidunt mi. Etiam sed consectetur urna. Maecenas libero arcu, vestibulum sed ligula vitae, egestas tempor mi.',
            'profit_margin' => 'Sed dapibus mi dui. Aliquam justo justo, aliquam quis porttitor sed, pulvinar nec quam.',
            'cash_flow' => 'Etiam dignissim sem tellus, interdum placerat ex ultrices in. Ut pharetra diam eu luctus laoreet.',
            'ROI' => 'Aliquam sit amet mi sem. Vivamus quis imperdiet dui. Nam facilisis porta velit, eu congue nulla dictum et.',
            'photo' => 'OpportunityImages\Frame.png',
            'approved' => true
        ]);

        $op2 = InvestmentOpportunity::create([
            'category_id' => 2,
            'business_id' => 2,
            'business_name' => 'BuildingCompany',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla. Integer tellus odio, iaculis a velit ut, egestas pellentesque felis. Nullam posuere quam ac quam cursus imperdiet. Nam eget porta risus. Sed hendrerit urna velit, vulputate malesuada elit pharetra sit amet.',
            'amount_needed'=> 350000,
            'remaining_amount' => 350000,
            'potential_risks' => '15%',
            'future_growth' => 'Consectetur urna',
            'products_or_services' => 'Aliquam justo',
            'returns_percentage' => '30%-40%',
            'company_valuation' => '28',
            'start_date' => '2024-05-1',
            'end_date' => '2024-12-10',
            'revenues' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla.',
            'net_profit' => 'Quisque et tincidunt mi. Etiam sed consectetur urna. Maecenas libero arcu, vestibulum sed ligula vitae, egestas tempor mi.',
            'profit_margin' => 'Sed dapibus mi dui. Aliquam justo justo, aliquam quis porttitor sed, pulvinar nec quam.',
            'cash_flow' => 'Etiam dignissim sem tellus, interdum placerat ex ultrices in. Ut pharetra diam eu luctus laoreet.',
            'ROI' => 'Aliquam sit amet mi sem. Vivamus quis imperdiet dui. Nam facilisis porta velit, eu congue nulla dictum et.',
            'photo' => 'OpportunityImages\Frame168.png',
            'approved' => true
        ]);

        $op3 = InvestmentOpportunity::create([
            'category_id' => 3,
            'business_id' => 2,
            'business_name' => 'MedicalCompany',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla. Integer tellus odio, iaculis a velit ut, egestas pellentesque felis. Nullam posuere quam ac quam cursus imperdiet. Nam eget porta risus. Sed hendrerit urna velit, vulputate malesuada elit pharetra sit amet.',
            'amount_needed'=> 450000,
            'remaining_amount' => 450000,
            'potential_risks' => '16%',
            'future_growth' => 'Quisque vel',
            'products_or_services' => 'Aliquam justo',
            'returns_percentage' => '25%-40%',
            'company_valuation' => '28',
            'start_date' => '2024-05-1',
            'end_date' => '2024-12-10',
            'revenues' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel eros egestas mauris suscipit fringilla.',
            'net_profit' => 'Quisque et tincidunt mi. Etiam sed consectetur urna. Maecenas libero arcu, vestibulum sed ligula vitae, egestas tempor mi.',
            'profit_margin' => 'Sed dapibus mi dui. Aliquam justo justo, aliquam quis porttitor sed, pulvinar nec quam.',
            'cash_flow' => 'Etiam dignissim sem tellus, interdum placerat ex ultrices in. Ut pharetra diam eu luctus laoreet.',
            'ROI' => 'Aliquam sit amet mi sem. Vivamus quis imperdiet dui. Nam facilisis porta velit, eu congue nulla dictum et.',
            'photo' => 'OpportunityImages\Frame1689.png',
            'approved' => true
        ]);
    }
}
