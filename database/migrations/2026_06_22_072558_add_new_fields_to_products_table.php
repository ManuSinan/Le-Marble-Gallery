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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'model_name')) {
                $table->string('model_name', 255)->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'finish_colour')) {
                $table->string('finish_colour', 255)->nullable()->after('product_code');
            }
            if (!Schema::hasColumn('products', 'product_type')) {
                $table->string('product_type', 255)->nullable()->after('finish_colour');
            }
            if (!Schema::hasColumn('products', 'installation_type')) {
                $table->string('installation_type', 255)->nullable()->after('product_type');
            }
            if (!Schema::hasColumn('products', 'compatibility_notes')) {
                $table->text('compatibility_notes')->nullable()->after('installation_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'model_name',
                'finish_colour',
                'product_type',
                'installation_type',
                'compatibility_notes'
            ]);
        });
    }
};
