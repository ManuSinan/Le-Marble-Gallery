<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'validity_date')) {
                $table->dateTime('validity_date')->nullable();
            }
            if (!Schema::hasColumn('orders', 'architect_name')) {
                $table->string('architect_name', 100)->nullable();
            }
            if (!Schema::hasColumn('orders', 'project_type')) {
                $table->string('project_type', 50)->nullable();
            }
            if (!Schema::hasColumn('orders', 'cutting_charge')) {
                $table->float('cutting_charge', 10, 3)->default(0);
            }
            if (!Schema::hasColumn('orders', 'transportation_charge')) {
                $table->float('transportation_charge', 10, 3)->default(0);
            }
            if (!Schema::hasColumn('orders', 'installation_charge')) {
                $table->float('installation_charge', 10, 3)->default(0);
            }
            if (!Schema::hasColumn('orders', 'manual_discount')) {
                $table->float('manual_discount', 10, 3)->default(0);
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'area_sqft')) {
                $table->float('area_sqft', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('order_items', 'thickness')) {
                $table->string('thickness', 50)->nullable();
            }
            if (!Schema::hasColumn('order_items', 'finish_type')) {
                $table->string('finish_type', 100)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'validity_date',
                'architect_name',
                'project_type',
                'cutting_charge',
                'transportation_charge',
                'installation_charge',
                'manual_discount'
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'area_sqft',
                'thickness',
                'finish_type'
            ]);
        });
    }
};
