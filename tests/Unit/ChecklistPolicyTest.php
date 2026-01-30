<?php

namespace Tests\Unit;

use App\Models\ExplosiveChecklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ChecklistPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        collect([
            'super-admin',
            'Head_Logging_Services',
            'Location Manager',
            'operation_incharge',
            'party_chief',
            'Field_Officer',
            'Technical_Support_Group',
            'staff',
        ])->each(function ($r) {
            Role::firstOrCreate(['name' => $r]);
        });
    }

    public function test_super_admin_and_head_can_update()
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $checklist = ExplosiveChecklist::factory()->create();

        $this->assertTrue($admin->can('update', $checklist));

        $head = User::factory()->create();
        $head->assignRole('Head_Logging_Services');

        $this->assertTrue($head->can('update', $checklist));
    }

    public function test_creator_can_edit_draft()
    {
        $user = User::factory()->create();
        $checklist = ExplosiveChecklist::factory()->create(['status' => 'draft', 'creator_id' => $user->id]);

        $this->assertTrue($user->can('update', $checklist));
    }

    public function test_field_officer_can_edit_draft_when_involved()
    {
        $user = User::factory()->create();
        $user->assignRole('Field_Officer');

        $checklist = ExplosiveChecklist::factory()->create(['status' => 'draft', 'creator_id' => $user->id]);
        $this->assertTrue($user->can('update', $checklist));

        // Not involved
        $other = User::factory()->create();
        $other->assignRole('Field_Officer');
        $checklist2 = ExplosiveChecklist::factory()->create(['status' => 'draft']);

        $this->assertFalse($other->can('update', $checklist2));
    }

    public function test_staff_and_tsg_cannot_update_or_create()
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        $tsg = User::factory()->create();
        $tsg->assignRole('Technical_Support_Group');

        $checklist = ExplosiveChecklist::factory()->create();

        $this->assertFalse($staff->can('update', $checklist));
        $this->assertFalse($tsg->can('update', $checklist));

        $this->assertFalse($staff->can('create', ExplosiveChecklist::class));
        $this->assertFalse($tsg->can('create', ExplosiveChecklist::class));
    }
}
