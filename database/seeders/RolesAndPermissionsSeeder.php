<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create Permissions with categories
        $permissions = [
            // Data Entry
            ['name' => 'view data entry', 'category' => 'Data Entry'],
            ['name' => 'edit data entry', 'category' => 'Data Entry'],
            ['name' => 'validate data entry', 'category' => 'Data Entry'],
            ['name' => 'approve data entry', 'category' => 'Data Entry'],
            ['name' => 'reject data entry', 'category' => 'Data Entry'],
            ['name' => 'delete data entry', 'category' => 'Data Entry'],

            // Reports
            ['name' => 'view reports', 'category' => 'Reports'],
            ['name' => 'edit reports', 'category' => 'Reports'],
            ['name' => 'validate reports', 'category' => 'Reports'],
            ['name' => 'approve reports', 'category' => 'Reports'],
            ['name' => 'reject reports', 'category' => 'Reports'],

            // Project
            ['name' => 'view project data', 'category' => 'Project'],
            ['name' => 'edit project data', 'category' => 'Project'],

            // Users
            ['name' => 'manage users', 'category' => 'Users'],

            // Admin
            ['name' => 'view organization data', 'category' => 'Admin'],
            ['name' => 'edit organization data', 'category' => 'Admin'],
            ['name' => 'view central admin data', 'category' => 'Admin'],
            ['name' => 'edit central admin data', 'category' => 'Admin'],
        ];

        // Create Permissions with categories
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'category' => $permission['category'],  // Assign category
            ]);
        }

        // Create Roles
        $roles = [
            'data-entry-operator',
            'data-entry-supervisor',
            'data-entry-validator',
            'project-admin',
            'central-admin',
            'observer',
        ];

        // Assign permissions to roles
        $rolePermissions = [
            'data-entry-operator' => [
                'view data entry', 
                'edit data entry'
            ],
            'data-entry-supervisor' => [
                'view data entry', 
                'edit data entry', 
                'validate data entry'
            ],
            'data-entry-validator' => [
                'view data entry', 
                'validate data entry', 
                'approve data entry'
            ],
            'project-admin' => [
                'view project data', 
                'edit project data', 
                'view reports'
            ],
            'central-admin' => [
                'view central admin data', 
                'edit central admin data', 
                'view project data', 
                'approve data entry'
            ],
            'observer' => [
                'view reports', 
                'view organization data'
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $role) {
            $roleInstance = Role::create(['name' => $role]);

            if (isset($rolePermissions[$role])) {
                $roleInstance->givePermissionTo($rolePermissions[$role]);
            }
        }
    }
}
