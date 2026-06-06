<?php

namespace Tests\Unit\Models;

use App\Enums\CenterStatus;
use App\Enums\OrderStatus;
use App\Models\Center;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

    private function makeCenter(): Center
    {
        return Center::create([
            'name'            => 'مركز اختبار',
            'commission_rate' => 10.00,
            'status'          => CenterStatus::Active,
        ]);
    }

    private function makeOrder(array $attrs = []): Order
    {
        return Order::create(array_merge([
            'reference_number'    => 'ORD-TEST-' . rand(1000, 9999),
            'customer_name'       => 'أحمد محمد',
            'customer_phone'      => '0501234567',
            'device_type_name'    => 'غسالة',
            'problem_description' => 'لا تعمل عند الشغيل',
            'address_text'        => 'حي النهضة، الرياض',
            'status'              => OrderStatus::Pending,
        ], $attrs));
    }

    // ── Schema ───────────────────────────────────────────────────
    public function test_orders_table_exists(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('orders'));
    }

    public function test_orders_has_soft_deletes(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('orders', 'deleted_at'));
    }

    public function test_orders_has_financial_columns(): void
    {
        foreach (['amount','commission_rate','commission_amount','is_financially_closed'] as $col) {
            $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('orders', $col), "Missing: {$col}");
        }
    }

    // ── CRUD ─────────────────────────────────────────────────────
    public function test_can_create_order(): void
    {
        $order = $this->makeOrder();
        $this->assertDatabaseHas('orders', ['customer_name' => 'أحمد محمد']);
    }

    public function test_soft_delete_works_on_order(): void
    {
        $order = $this->makeOrder();
        $order->delete();

        $this->assertNull(Order::find($order->id));
        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }

    public function test_order_restore_works(): void
    {
        $order = $this->makeOrder();
        $order->delete();
        $order->restore();

        $this->assertNotNull(Order::find($order->id));
    }

    // ── Status Cast ───────────────────────────────────────────────
    public function test_status_casts_to_enum(): void
    {
        $order = $this->makeOrder(['status' => OrderStatus::Pending]);
        $this->assertInstanceOf(OrderStatus::class, $order->fresh()->status);
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function test_by_status_scope_filters_correctly(): void
    {
        $this->makeOrder(['status' => OrderStatus::Pending]);
        $this->makeOrder(['status' => OrderStatus::Completed]);

        $this->assertEquals(1, Order::byStatus('pending')->count());
    }

    public function test_financially_closed_scope(): void
    {
        $this->makeOrder(['is_financially_closed' => false]);
        $this->makeOrder(['is_financially_closed' => true]);

        $this->assertEquals(1, Order::financiallyClosed()->count());
    }

    // ── Reference Number ─────────────────────────────────────────
    public function test_reference_number_is_unique(): void
    {
        $o1 = $this->makeOrder(['reference_number' => 'ORD-20260101-AAAA']);
        $o2 = $this->makeOrder(['reference_number' => 'ORD-20260101-BBBB']);
        $this->assertNotEquals($o1->reference_number, $o2->reference_number);
    }
}
