<?php

namespace Tests\Unit\Enums;

use App\Enums\CenterStatus;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

class EnumsTest extends TestCase
{
    // ── CenterStatus ─────────────────────────────────────────────
    public function test_center_status_has_correct_labels(): void
    {
        $this->assertSame('نشط', CenterStatus::Active->label());
        $this->assertSame('موقوف', CenterStatus::Suspended->label());
        $this->assertSame('قيد المراجعة', CenterStatus::Pending->label());
    }

    public function test_center_status_has_correct_colors(): void
    {
        $this->assertSame('success', CenterStatus::Active->color());
        $this->assertSame('danger', CenterStatus::Suspended->color());
        $this->assertSame('warning', CenterStatus::Pending->color());
    }

    public function test_center_status_values_returns_array(): void
    {
        $values = CenterStatus::values();
        $this->assertContains('active', $values);
        $this->assertContains('suspended', $values);
        $this->assertContains('pending', $values);
    }

    // ── OrderStatus ───────────────────────────────────────────────
    public function test_order_status_pending_allows_dispatch_for_super_admin(): void
    {
        $transitions = OrderStatus::Pending->allowedTransitions(UserRole::SuperAdmin);
        $this->assertContains(OrderStatus::Dispatched, $transitions);
        $this->assertContains(OrderStatus::Cancelled, $transitions);
    }

    public function test_order_status_completed_cannot_be_cancelled(): void
    {
        $transitions = OrderStatus::Completed->allowedTransitions(UserRole::SuperAdmin);
        $this->assertNotContains(OrderStatus::Cancelled, $transitions);
    }

    public function test_order_status_technician_cannot_dispatch(): void
    {
        $transitions = OrderStatus::Pending->allowedTransitions(UserRole::Technician);
        $this->assertEmpty($transitions);
    }

    public function test_order_status_values_returns_all_cases(): void
    {
        $values = OrderStatus::values();
        $this->assertCount(7, $values);
        $this->assertContains('pending', $values);
        $this->assertContains('completed', $values);
    }

    // ── UserRole ─────────────────────────────────────────────────
    public function test_super_admin_is_not_center_scoped(): void
    {
        $this->assertFalse(UserRole::SuperAdmin->isCenterScoped());
    }

    public function test_center_admin_is_center_scoped(): void
    {
        $this->assertTrue(UserRole::CenterAdmin->isCenterScoped());
    }

    public function test_technician_is_center_scoped(): void
    {
        $this->assertTrue(UserRole::Technician->isCenterScoped());
    }

    public function test_call_center_is_not_center_scoped(): void
    {
        $this->assertFalse(UserRole::CallCenter->isCenterScoped());
    }
}
