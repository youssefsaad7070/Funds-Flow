<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $arrayOfPermissionNames = [
            'posts create', 'posts view', 'posts edit', 'posts delete',
            'settings create', 'settings view', 'settings edit', 'settings delete',
            'categories create', 'categories view', 'categories edit', 'categories delete',
            'users create', 'users view', 'users edit', 'users delete'
        ];

        $arrayOfInvestorPermissions = [
            'posts view'
        ];
        $arrayOfBusinessPermissions = [
            'posts create','posts view', 'posts edit', 'posts delete',
        ];

        // $iPermissions = collect($arrayOfInvestorPermissions)->map(function($iPermission){
        //     return [
        //         'name' => $iPermission,
        //         'guard_name' => 'web'
        //     ];
        // });
        // $bPermissions = collect($arrayOfBusinessPermissions)->map(function($bPermission){
        //     return [
        //         'name' => $bPermission,
        //         'guard_name' => 'web'
        //     ];
        // });
        
        // Permission::insert($iPermissions->toArray());

        $permissions = collect($arrayOfPermissionNames)->map(function($permission){
            return [
                'name' => $permission,
                'guard_name' => 'web'
            ];
        });
        Permission::insert($permissions->toArray());

        $role = Role::create(['name' => 'investor'])
        ->givePermissionTo($arrayOfInvestorPermissions);

        $role = Role::create(['name' => 'business'])
        ->givePermissionTo($arrayOfBusinessPermissions);

        $role = Role::create(['name' => 'admin'])
        ->givePermissionTo($arrayOfPermissionNames);
    }
}
