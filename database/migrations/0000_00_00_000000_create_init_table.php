<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('key', 100)->index();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 100)->index();
            $table->string('type', 10)->default('public');
            $table->string('created_by', 10)->default('user');
            $table->timestamps();
        });
 
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned();
            $table->string('permission', 100)->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 100);
            $table->string('mobile', 15)->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->string('username', 100)->unique()->index();
            $table->string('password', 255);
            $table->string('otp', 10)->nullable();
            $table->rememberToken();
            $table->string('api_token', 255)->nullable();
            $table->string('fcm', 255)->nullable();
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->string('status', 10)->default('active');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('local_name', 100)->nullable(); 
            $table->timestamps();
        });


        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();  
            $table->string('name', 100);
            $table->string('local_name', 100)->nullable(); 
            $table->bigInteger('state_id')->unsigned()->nullable();
            $table->float('minimum_cart_amount', 10, 3);
            $table->float('delivery_charge', 10, 3);
            $table->float('delivery_cart_amount', 10, 3)->nullable();
            $table->timestamps();
            $table->foreign('state_id')->references('id')->on('states');
        });

 
        Schema::create('address', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 100);
            $table->bigInteger('user_id')->unsigned()->index();
            $table->string('mobile', 15)->nullable();
            $table->text('line_1')->nullable();
            $table->text('line_2')->nullable();
            $table->text('line_3')->nullable();
            $table->bigInteger('location_id')->unsigned();
            $table->string('type', 10)->default('Other');
            $table->boolean('default')->default(0);

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations');
        });

        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('type', 25);
            $table->string('local_type', 25)->nullable();
            $table->string('name', 25);
            $table->string('local_name', 25)->nullable();
            $table->float('stepper', 10, 2)->default(1);
            $table->timestamps();
        });


        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 100);
            $table->string('slug', 255);
            $table->string('local_name', 100)->nullable();
            $table->text('image')->nullable();  
            $table->bigInteger('priority')->default(0);
            $table->timestamps();
        });
        

        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 100);
            $table->string('slug', 255);
            $table->text('image')->nullable();  
            $table->bigInteger('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('banner_sliders', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 100);
            $table->text('image')->nullable();  
            $table->bigInteger('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 100);
            $table->timestamps();
        });

        Schema::create('variants', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 100);
            $table->string('local_name', 100)->nullable();
            $table->timestamps();
        });


        Schema::create('variant_options', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->bigInteger('variant_id')->unsigned();
            $table->string('value', 100);
            $table->string('local_value', 100)->nullable();
            $table->timestamps();

            $table->foreign('variant_id')->references('id')->on('variants');
        });


        Schema::create('attribute_variants', function (Blueprint $table) {
            $table->bigInteger('attribute_id')->unsigned();
            $table->bigInteger('variant_id')->unsigned();

            $table->foreign('attribute_id')->references('id')->on('attributes');
            $table->foreign('variant_id')->references('id')->on('variants');
        });



        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 100);
            $table->string('slug', 255);
            $table->string('local_name', 100)->nullable();
            $table->text('description')->nullable(); 
            $table->text('local_description')->nullable(); 
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->bigInteger('attribute_id')->unsigned()->nullable();
            $table->string('combination_key', 255)->nullable();

            $table->string('product_code', 255)->nullable();
            $table->string('stock_status', 10)->default('limited');
            $table->float('stock_available', 10, 2)->nullable();
            $table->float('minimum_quantity', 10, 2);
            
            $table->text('image')->nullable();  
            $table->text('gallery_image_1')->nullable();  
            $table->text('gallery_image_2')->nullable();  
            $table->text('gallery_image_3')->nullable();  


            $table->bigInteger('unit_id')->unsigned();
            $table->float('price', 10, 3);
            $table->float('selling_price', 10, 3); 
            $table->text('keywords')->nullable(); 

            $table->longText('magic_search')->nullable(); 

            $table->bigInteger('priority')->default(0);
            $table->string('status', 10)->default('active');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('attribute_id')->references('id')->on('attributes');
            $table->foreign('unit_id')->references('id')->on('units');
            
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE products ADD FULLTEXT(magic_search)');
        }



        Schema::create('product_variants', function (Blueprint $table) {  
            $table->bigInteger('product_id')->unsigned(); 
            $table->string('combination_key', 255);
            $table->bigInteger('variant_id')->unsigned();
            $table->bigInteger('variant_option_id')->unsigned();
 
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('variant_id')->references('id')->on('variants');
            $table->foreign('variant_option_id')->references('id')->on('variant_options');
        });

 
        Schema::create('taxs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 100);
            $table->string('local_name', 100)->nullable();
            $table->string('short', 10);
            $table->string('local_short', 30)->nullable();;
            $table->float('percentage', 5, 2);
            $table->timestamps(); 
        });

        Schema::create('product_taxs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('tax_id')->unsigned();
            $table->string('type', 25);
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('tax_id')->references('id')->on('taxs');
        });


        Schema::create('favourites', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });
 

        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();   
            $table->bigInteger('user_id')->unsigned();
            $table->string('address_type', 10);
            $table->string('address_name', 100);
            $table->string('address_mobile', 15);
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->text('address_line_3')->nullable();
            $table->string('address_location', 100);
            $table->string('address_local_location', 100)->nullable();
            $table->text('discount_code')->nullable();
            $table->float('total_amount', 10, 3);
            $table->float('delivery_charge', 10, 3)->nullable();    
            $table->float('discount_amount', 10, 3);
            $table->float('canceled_amount', 10, 3);
            $table->float('final_amount', 10, 3);
            $table->text('note')->nullable(); 
            $table->string('status', 10)->default('placed');
            $table->bigInteger('assign_user_id')->unsigned()->nullable();
            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('assign_user_id')->references('id')->on('users');
         });



        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();  
            $table->bigInteger('order_id')->unsigned();
            $table->string('product_name', 100)->nullable();
            $table->string('local_product_name', 100)->nullable();
            $table->text('product_image')->nullable();
            $table->bigInteger('product_id')->unsigned();

            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned()->nullable();

            $table->string('product_code', 255)->nullable();
            $table->bigInteger('unit_id')->unsigned();
            $table->string('unit_type', 25);
            $table->string('local_unit_type', 25)->nullable();
            $table->float('stepper', 10, 2);
            $table->string('unit_name', 25);
            $table->string('local_unit_name', 25)->nullable();
            $table->float('quantity', 10, 2);
            $table->float('price', 10, 3);
            $table->float('selling_price', 10, 3)->nullable(); 
            $table->float('final_price', 10, 3);            
            $table->string('status', 20)->default('placed');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('unit_id')->references('id')->on('units');

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('brand_id')->references('id')->on('brands'); 
        });


 
        Schema::create('order_status', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();   
            $table->string('status', 20)->default('placed');
            $table->bigInteger('order_id')->unsigned();
            $table->text('private_note')->nullable(); 
            $table->text('public_note')->nullable(); 
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('states');
        Schema::dropIfExists('address');
        Schema::dropIfExists('units');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('banner_sliders');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('variants');
        Schema::dropIfExists('variant_options');
        Schema::dropIfExists('attribute_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('taxs');
        Schema::dropIfExists('product_taxs');
        Schema::dropIfExists('favourites');   
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_status');
    }
}
