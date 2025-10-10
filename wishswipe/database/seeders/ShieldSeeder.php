<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['guard_name' => 'web']
        );

        $panelUser = Role::firstOrCreate(
            ['name' => 'panel_user'],
            ['guard_name' => 'web']
        );

        // Define all resources
        $resources = [
            'category',
            'product',
            'conversation',
            'message',
            'user',
            'role',
        ];

        // Define permission actions
        $actions = [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
        ];

        // Create permissions for each resource
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}_{$resource}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create page permissions
        $pages = [
            'Dashboard',
            'SwipingPage',
            'MyListings',
            'ConversationsPage',
        ];

        foreach ($pages as $page) {
            Permission::firstOrCreate([
                'name' => 'page_' . $page,
                'guard_name' => 'web',
            ]);
        }

        // Assign ALL permissions to super_admin
        $superAdmin->givePermissionTo(Permission::all());

        // Assign limited permissions to panel_user
        $panelUserPermissions = [
            // Products - users can manage their own
            'view_any_product',
            'view_product',
            'create_product',
            'update_product',
            'delete_product',
            
            // Categories - read only
            'view_any_category',
            'view_category',
            
            // Conversations - users can view their own
            'view_any_conversation',
            'view_conversation',
            'create_conversation',
            'update_conversation',
            
            // Messages - users can manage their own
            'view_any_message',
            'view_message',
            'create_message',
            
            // Pages
            'page_Dashboard',
            'page_SwipingPage',
            'page_MyListings',
            'page_ConversationsPage',
        ];

        foreach ($panelUserPermissions as $permission) {
            $perm = Permission::where('name', $permission)->first();
            if ($perm) {
                $panelUser->givePermissionTo($perm);
            }
        }
    }
}