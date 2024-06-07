<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use App\Models\Business;
use App\Models\Investor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Joe Saad',
            'email' => 'test@test.com',
            'role' => 'investor',
            'photo' => 'DefaultUserImages/Male.jpg',
            'id_card_photo' => 'DefaultUserImages/id.jpg',
            'password' => Hash::make('123456789'),
        ]);

        $user1->assignRole('investor');
        $user1->markEmailAsVerified();
        $user1I = Investor::create([
            'user_id' => $user1->id,
            'name' => 'Joe Saad',
            'email' => 'test@test.com',
            'gender' => 'Male',
            'national_id' => '14562369874521',
            'nationality' => 'Egyptian',
            'age' => '23'
        ]);
/////////////////////////////////////////////////////////////////
        $user2 = User::create([
            'name' => 'Haded Ezz',
            'email' => 'hadedezz@gmail.com',
            'photo' => 'DefaultUserImages/Business.jpg',
            'id_card_photo' => 'DefaultUserImages/id.jpg',
            'role' => 'business',
            'password' => Hash::make('123456789'),
        ]);

        $user2->assignRole('business');
        $user2->markEmailAsVerified();
        $user2B = Business::create([
            'user_id' => $user2->id,
            'name' => 'Haded Ezz',
            'email' => 'hadedezz@gmail.com',
            'tax_card_number' => '123456789452',
            'description' => 'Proin sit amet nulla justo. Curabitur non suscipit nisl, ut dictum dui. Interdum et malesuada fames ac ante ipsum primis in faucibus. Pellentesque mollis orci sed dapibus pulvinar. Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
        ]);
///////////////////////////////////////////////////////////////
        $user3 = User::create([
            'name' => 'Youssef Saad',
            'email' => 'youssefmohamed07999@gmail.com',
            'role' => 'admin',
            'photo' => 'DefaultUserImages/YoussefSaad.jpg',
            'id_card_photo' => 'DefaultUserImages/id.jpg',
            'password' => Hash::make('01275601016'),
        ]);

        $user3->assignRole('admin');
        $user3->markEmailAsVerified();
        $user3A = Admin::create([
            'user_id' => $user3->id,
            'name' => 'Youssef Saad',
            'email' => 'youssefmohamed07999@gmail.com',
        ]);
        
    }
}
