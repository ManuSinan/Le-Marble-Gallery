<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SimpleBookOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_sign_in_prompt_for_guests(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Order your books');
        $response->assertSee('Choose your class');
        $response->assertSee('Search by title or code');
        $response->assertSee('Sign in');
    }

    public function test_authenticated_user_can_place_an_order_from_class_and_subject_form(): void
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::create([
            'name' => 'Test Parent',
            'username' => '9876543211',
            'mobile' => '9876543211',
            'password' => Hash::make('secret123'),
            'role_id' => 1,
            'status' => 'active',
            'mobile_verified' => 1,
            'email_verified' => 0,
        ]);

        $product = Product::with(['category.parent'])->firstOrFail();
        $subject = $product->category;
        $class = $subject->parent;

        $this->actingAs($user);

        $response = $this->post(route('quick-order.store'), [
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'city' => 'Chennai',
            'address' => '12 Test Street',
            'quantity' => 2,
            'note' => 'Deliver after 5 PM',
        ]);

        $response->assertRedirect(route('home', ['class' => $class->id, 'subject' => $subject->id]));
        $response->assertSessionHas('order_success');

        $order = Order::firstOrFail();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'address_name' => 'Test Parent',
            'address_mobile' => '9876543211',
            'address_location' => 'Chennai',
            'status' => 'placed',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'status' => 'placed',
        ]);

        $this->assertDatabaseHas('order_status', [
            'order_id' => $order->id,
            'status' => 'placed',
        ]);
    }
}
