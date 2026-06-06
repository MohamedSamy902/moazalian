<?php

namespace Tests\Unit\Services;

use App\Enums\CenterStatus;
use App\Enums\OrderStatus;
use App\Models\Center;
use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Services\FinancialClosureService;
use App\Services\OrderService;
use App\Repositories\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OrderService(
            new OrderRepository(),
            new FinancialClosureService()
        );
    }

    private function makeCenter(): Center
    {
        return Center::create([
            'name'            => 'مركز اختبار',
            'commission_rate' => 10.00,
            'status'          => CenterStatus::Active,
        ]);
    }

    // ── Create ───────────────────────────────────────────────────
    public function test_create_generates_reference_number(): void
    {
        $order = $this->service->create([
            'customer_name'       => 'أحمد',
            'customer_phone'      => '0501234567',
            'device_type_name'    => 'غسالة',
            'problem_description' => 'خراب تام من التكييف',
            'address_text'        => 'الرياض',
        ], 1);

        $this->assertStringStartsWith('ORD-', $order->reference_number);
    }

    public function test_create_sets_status_to_pending(): void
    {
        $order = $this->service->create([
            'customer_name'       => 'أحمد',
            'customer_phone'      => '0501234567',
            'device_type_name'    => 'غسالة',
            'problem_description' => 'لا يعمل بتاتاً عند الشغيل',
            'address_text'        => 'الرياض',
        ], 1);

        $this->assertEquals(OrderStatus::Pending, $order->status);
    }

    public function test_create_logs_status_change(): void
    {
        $order = $this->service->create([
            'customer_name'       => 'خالد',
            'customer_phone'      => '0509876543',
            'device_type_name'    => 'ثلاجة',
            'problem_description' => 'الكمبروسر متعطل كلياً',
            'address_text'        => 'جدة',
        ], 1);

        $this->assertDatabaseHas('order_status_logs', [
            'order_id'   => $order->id,
            'to_status'  => 'pending',
        ]);
    }

    // ── Dispatch ─────────────────────────────────────────────────
    public function test_dispatch_changes_status_to_dispatched(): void
    {
        $center = $this->makeCenter();
        $order  = Order::create([
            'reference_number'    => 'ORD-TEST-0001',
            'customer_name'       => 'أحمد',
            'customer_phone'      => '050',
            'device_type_name'    => 'غسالة',
            'problem_description' => 'لا تعمل بالمرة',
            'address_text'        => 'الرياض',
            'status'              => OrderStatus::Pending,
        ]);

        $dispatched = $this->service->dispatch($order, $center->id, 1);

        $this->assertEquals(OrderStatus::Dispatched, $dispatched->status);
        $this->assertEquals($center->id, $dispatched->center_id);
    }

    public function test_dispatch_fails_if_not_pending(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $center = $this->makeCenter();
        $order  = Order::create([
            'reference_number'    => 'ORD-TEST-0002',
            'customer_name'       => 'أحمد',
            'customer_phone'      => '050',
            'device_type_name'    => 'غسالة',
            'problem_description' => 'لا تعمل بالمرة',
            'address_text'        => 'الرياض',
            'status'              => OrderStatus::Completed,
        ]);

        $this->service->dispatch($order, $center->id, 1);
    }

    // ── Soft Delete ──────────────────────────────────────────────
    public function test_delete_soft_deletes_order(): void
    {
        $order = Order::create([
            'reference_number'    => 'ORD-TEST-0003',
            'customer_name'       => 'سارة',
            'customer_phone'      => '055',
            'device_type_name'    => 'مكيف',
            'problem_description' => 'لا يبرد لانه تالف',
            'address_text'        => 'جدة',
            'status'              => OrderStatus::Pending,
        ]);

        $this->service->delete($order);

        $this->assertSoftDeleted('orders', ['id' => $order->id]);
        $this->assertNull(Order::find($order->id));
    }
}
