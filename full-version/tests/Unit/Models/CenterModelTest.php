<?php

namespace Tests\Unit\Models;

use App\Enums\CenterStatus;
use App\Models\Center;
use App\Models\Order;
use App\Models\Technician;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CenterModelTest extends TestCase
{
    use RefreshDatabase;

    private function makeCenter(array $attrs = []): Center
    {
        return Center::create(array_merge([
            'name'            => 'مركز اختبار',
            'commission_rate' => 10.00,
            'status'          => CenterStatus::Active,
            'total_collected' => 0,
            'platform_due'    => 0,
        ], $attrs));
    }

    // ── Schema ───────────────────────────────────────────────────
    public function test_centers_table_exists(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasTable('centers'));
    }

    public function test_centers_has_required_columns(): void
    {
        foreach (['name','commission_rate','status','platform_due','total_collected'] as $col) {
            $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('centers', $col), "Missing column: {$col}");
        }
    }

    public function test_centers_has_soft_deletes(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('centers', 'deleted_at'));
    }

    // ── CRUD ─────────────────────────────────────────────────────
    public function test_can_create_center(): void
    {
        $center = $this->makeCenter(['name' => 'مركز رائع']);
        $this->assertDatabaseHas('centers', ['name' => 'مركز رائع']);
        $this->assertInstanceOf(Center::class, $center);
    }

    public function test_can_update_center(): void
    {
        $center = $this->makeCenter();
        $center->update(['name' => 'مركز محدّث']);
        $this->assertDatabaseHas('centers', ['name' => 'مركز محدّث']);
    }

    public function test_soft_delete_does_not_remove_from_db(): void
    {
        $center = $this->makeCenter(['name' => 'مركز للحذف']);
        $center->delete();

        // Not in default query
        $this->assertNull(Center::find($center->id));
        // But still in DB
        $this->assertDatabaseHas('centers', ['id' => $center->id]);
        $this->assertSoftDeleted('centers', ['id' => $center->id]);
    }

    public function test_soft_deleted_center_can_be_restored(): void
    {
        $center = $this->makeCenter();
        $center->delete();
        $center->restore();

        $this->assertNotNull(Center::find($center->id));
        $this->assertNull(Center::withTrashed()->find($center->id)->deleted_at);
    }

    // ── Computed ─────────────────────────────────────────────────
    public function test_center_net_attribute_is_computed_correctly(): void
    {
        $center = $this->makeCenter(['total_collected' => 1000, 'platform_due' => 150]);
        $this->assertEquals(850.00, $center->center_net);
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function test_active_scope_excludes_suspended(): void
    {
        $this->makeCenter(['status' => CenterStatus::Active]);
        $this->makeCenter(['status' => CenterStatus::Suspended]);

        $this->assertEquals(1, Center::active()->count());
    }

    public function test_available_for_dispatch_excludes_auto_suspended(): void
    {
        $this->makeCenter(['status' => CenterStatus::Active, 'auto_suspended' => false]);
        $this->makeCenter(['status' => CenterStatus::Active, 'auto_suspended' => true]);

        $this->assertEquals(1, Center::availableForDispatch()->count());
    }

    // ── Relationships ─────────────────────────────────────────────
    public function test_center_has_many_technicians(): void
    {
        $center = $this->makeCenter();
        Technician::create([
            'center_id' => $center->id,
            'name'      => 'فني 1',
            'phone'     => '0501111111',
        ]);

        $this->assertCount(1, $center->fresh()->technicians);
    }

    // ── Status ───────────────────────────────────────────────────
    public function test_status_casts_to_enum(): void
    {
        $center = $this->makeCenter(['status' => CenterStatus::Active]);
        $this->assertInstanceOf(CenterStatus::class, $center->fresh()->status);
        $this->assertEquals(CenterStatus::Active, $center->fresh()->status);
    }
}
