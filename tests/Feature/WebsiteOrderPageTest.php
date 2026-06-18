<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebsiteOrderPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_list_page_uses_new_website_ui(): void
    {
        [$user, $order] = $this->createOrderForUser();

        $this->actingAs($user);

        $response = $this->get(route('website.order'));

        $response->assertOk();
        $response->assertSee('Your orders');
        $response->assertSee('Track current and past material orders');
        $response->assertSee('Order ' . $order->id . '-' . $order->created_at->format('dmy'));
    }

    public function test_order_detail_page_uses_new_website_ui(): void
    {
        [$user, $order] = $this->createOrderForUser();

        $this->actingAs($user);

        $response = $this->get(route('website.order.detail', [
            'order' => $order->id . '-' . $order->created_at->format('dmy'),
        ]));

        $response->assertOk();
        $response->assertSee('Ordered items');
        $response->assertSee('Order updates');
        $response->assertSee('Payment summary');
        $response->assertSee('Contact information');
    }

    protected function createOrderForUser(): array
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::create([
            'name' => 'Order Page User',
            'username' => '9876543221',
            'mobile' => '9876543221',
            'password' => Hash::make('secret123'),
            'role_id' => 1,
            'status' => 'active',
            'mobile_verified' => 1,
            'email_verified' => 0,
        ]);

        $product = Product::with(['category.parent'])->firstOrFail();
        $subject = $product->category;
        $class = $subject->parent;

        $this->actingAs($user)->post(route('quick-order.store'), [
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'city' => 'Kochi',
            'address' => '22 Reader Street',
            'quantity' => 2,
            'note' => 'Front desk delivery',
        ])->assertRedirect();

        $order = Order::latest('id')->firstOrFail();

        return [$user, $order];
    }
}
