<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $modules = config('menu');

        $crudActions = ['list', 'create', 'update', 'delete'];

        $extraActions = [
            'view-hpp',
            'view-purchase-price',
            'view-service',
        ];

        foreach ($modules as $prefix => $items) {

            foreach ($items as $module => $label) {

                if ($prefix === 'reports') {
                    $actions = ['view'];
                } elseif ($prefix === 'master') {
                    $actions = $crudActions;
                } else {
                    $actions = array_merge($crudActions, $extraActions);
                }

                foreach ($actions as $action) {

                    Permission::firstOrCreate([
                        'name'       => "{$prefix}.{$module}.{$action}",
                        'module'     => "{$prefix}.{$module}",
                        'action'     => $action,
                        'guard_name' => 'web',
                    ]);
                }
            }
        }
    }
}
