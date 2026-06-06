<?php

namespace Tests\Unit\Services;

use App\Enums\CenterStatus;
use App\Models\Center;
use App\Models\PlatformSetting;
use App\Services\CenterService;
use App\Repositories\CenterRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CenterServiceTest extends TestCase
{
    use RefreshDatabase;

    private CenterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CenterService(new CenterRepository());
    }

    private function makeCenter(array $attrs = []): Center
    {
        return Center::create(array_merge([
            'name'            => 'مركز اختبار',
            'commission_rate' => 10.00,
            'status'          => CenterStatus::Pending,
        ], $attrs));
    }

    // ── Create ───────────────────────────────────────────────────
    public function test_create_sets_joined_at(): void
    {
        $center = $this->service->create([
            'name'            => 'مركز جديد',
            'commission_rate' => 15.00,
        ]);

        $this->assertNotNull($center->joined_at);
        $this->assertDatabaseHas('centers', ['name' => 'مركز جديد']);
    }

    // ── Toggle ───────────────────────────────────────────────────
    public function test_toggle_status_from_active_to_suspended(): void
    {
        $center = $this->makeCenter(['status' => CenterStatus::Active]);
        $result = $this->service->toggleStatus($center);

        $this->assertEquals(CenterStatus::Suspended, $result->status);
    }

    public function test_toggle_status_from_suspended_to_active(): void
    {
        $center = $this->makeCenter(['status' => CenterStatus::Suspended]);
        $result = $this->service->toggleStatus($center);

        $this->assertEquals(CenterStatus::Active, $result->status);
    }

    public function test_toggle_resets_auto_suspended_flag(): void
    {
        $center = $this->makeCenter(['status' => CenterStatus::Suspended, 'auto_suspended' => true]);
        $result = $this->service->toggleStatus($center);

        $this->assertFalse($result->auto_suspended);
    }

    // ── Auto-Suspension ───────────────────────────────────────────
    public function test_auto_suspension_triggers_when_threshold_exceeded(): void
    {
        PlatformSetting::setValue('auto_suspension_threshold', '5000');
        $center = $this->makeCenter([
            'status'       => CenterStatus::Active,
            'platform_due' => 6000.00,
        ]);

        $this->service->checkAutoSuspension($center);
        $center->refresh();

        $this->assertEquals(CenterStatus::Suspended, $center->status);
        $this->assertTrue($center->auto_suspended);
    }

    public function test_auto_suspension_does_not_trigger_below_threshold(): void
    {
        PlatformSetting::setValue('auto_suspension_threshold', '5000');
        $center = $this->makeCenter([
            'status'       => CenterStatus::Active,
            'platform_due' => 1000.00,
        ]);

        $this->service->checkAutoSuspension($center);
        $center->refresh();

        $this->assertEquals(CenterStatus::Active, $center->status);
    }

    // ── Delete ───────────────────────────────────────────────────
    public function test_delete_soft_deletes_center(): void
    {
        $center = $this->makeCenter();
        $this->service->delete($center);

        $this->assertSoftDeleted('centers', ['id' => $center->id]);
        $this->assertNull(Center::find($center->id));
    }
}
