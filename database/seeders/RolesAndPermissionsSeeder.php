<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Create roles ──────────────────────────────────────────
        $admin = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['type' => 'private', 'created_by' => 'user']
        );

        $salesman = Role::firstOrCreate(
            ['name' => 'Salesman'],
            ['type' => 'private', 'created_by' => 'user']
        );

        // ── 2. Build the full permission key list from config ────────
        $allPerms = Config::get('permissions', []);
        $allKeys  = [];

        foreach ($allPerms as $group => $actions) {
            foreach ($actions as $label => $routes) {
                $allKeys[] = $group . '-' . $label;
            }
        }

        // ── 3. Admin  → ALL permissions ──────────────────────────────
        Permission::where('role_id', $admin->id)->delete();

        foreach ($allKeys as $key) {
            Permission::create([
                'role_id'    => $admin->id,
                'permission' => $key,
            ]);
        }

        // ── 4. Salesman → limited permissions ────────────────────────
        //    Orders, Enquiries, Product view/stock, basic reports
        $salesmanPerms = [
            'Enquiry-View',
            'Enquiry-Update',
            'Order-View',
            'Order-Update',
            'Product-Stock',
            'Product-View',
            'Report-Business Overview',
            'Report-Products',
        ];

        Permission::where('role_id', $salesman->id)->delete();

        foreach ($salesmanPerms as $key) {
            Permission::create([
                'role_id'    => $salesman->id,
                'permission' => $key,
            ]);
        }

        // ── 5. Clear cached permissions ──────────────────────────────
        Cache::forget('permissions');

        $this->command->info("✓ Admin  (id={$admin->id})  → " . count($allKeys) . ' permissions');
        $this->command->info("✓ Salesman (id={$salesman->id}) → " . count($salesmanPerms) . ' permissions');
    }
}
