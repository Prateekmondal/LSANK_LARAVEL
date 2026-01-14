<?php

namespace Tests\Unit;

use App\Models\TimeRegister;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TimeRegisterPolicyTest extends TestCase
{

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

        $tr = TimeRegister::factory()->create();

        $this->assertTrue($admin->can('update', $tr));

        $head = User::factory()->create();
        $head->assignRole('Head_Logging_Services');

        $this->assertTrue($head->can('update', $tr));
    }

    public function test_creator_can_update_before_final_submission()
    {
        $user = User::factory()->create();
        $tr = TimeRegister::factory()->create(['created_by' => $user->id, 'is_final_submitted' => false]);

        $this->assertTrue($user->can('update', $tr));

        $tr->is_final_submitted = true;
        $tr->save();

        $this->assertFalse($user->can('update', $tr));
    }

    public function test_staff_and_tsg_cannot_update_or_create()
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        $tsg = User::factory()->create();
        $tsg->assignRole('Technical_Support_Group');

        $tr = TimeRegister::factory()->create();

        $this->assertFalse($staff->can('update', $tr));
        $this->assertFalse($tsg->can('update', $tr));

        $this->assertFalse($staff->can('create', TimeRegister::class));
        $this->assertFalse($tsg->can('create', TimeRegister::class));
    }
}
