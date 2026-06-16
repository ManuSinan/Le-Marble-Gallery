<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePincodesAndAddToAddress extends Migration
{
    public function up()
    {
        Schema::create('pincodes', function (Blueprint $table) {
            $table->id();
            $table->string('pincode', 20)->unique();
            $table->string('area', 100)->nullable();
            $table->decimal('minimum_cart_amount', 10, 3)->default(0);
            $table->decimal('delivery_charge', 10, 3)->default(0);
            $table->decimal('delivery_cart_amount', 10, 3)->nullable();
            $table->timestamps();
        });

        Schema::table('address', function (Blueprint $table) {
            $table->unsignedBigInteger('pincode_id')->nullable()->after('location_id');
            $table->foreign('pincode_id')->references('id')->on('pincodes')->onDelete('set null');
        });

        Schema::table('address', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->dropForeign(['pincode_id']);
            $table->dropColumn('pincode_id');
        });
        Schema::table('address', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable(false)->change();
        });
        Schema::dropIfExists('pincodes');
    }
}
