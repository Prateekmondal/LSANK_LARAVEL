<?php

namespace Tests\Unit;

use App\Models\Jcr;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JcrPolicyTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure roles exist
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

        // Run any necessary seeders or migrations
        Artisan::call('config:clear');
    }

    public function test_super_admin_can_update_jcr()
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $jcr = Jcr::factory()->create();

        $this->assertTrue($user->can('update', $jcr));
    }

    public function test_head_logging_services_can_update_jcr()
    {
        $user = User::factory()->create();
        $user->assignRole('Head_Logging_Services');

        $jcr = Jcr::factory()->create();

        $this->assertTrue($user->can('update', $jcr));
    }

    public function test_location_manager_can_update_jcr()
    {
        $user = User::factory()->create();
        $user->assignRole('Location Manager');

        $jcr = Jcr::factory()->create();

        $this->assertTrue($user->can('update', $jcr));
    }

    public function test_party_chief_can_edit_once()
    {
        $user = User::factory()->create();
        $user->assignRole('party_chief');

        $jcr = Jcr::factory()->create([
            'party_chief_edited' => false,
            'party_chief_id' => $user->id,
        ]);

        $this->assertTrue($user->can('update', $jcr));

        $jcr->party_chief_edited = true;
        $jcr->save();

        $this->assertFalse($user->can('update', $jcr));
    }

    public function test_operation_incharge_can_edit_once()
    {
        $user = User::factory()->create();
        $user->assignRole('operation_incharge');

        $jcr = Jcr::factory()->create([
            'operation_incharge_edited' => false,
            'operation_incharge_id' => $user->id,
        ]);

        $this->assertTrue($user->can('update', $jcr));

        $jcr->operation_incharge_edited = true;
        $jcr->save();

        $this->assertFalse($user->can('update', $jcr));
    }

    public function test_field_officer_can_edit_draft_only_when_involved()
    {
        $user = User::factory()->create();
        $user->assignRole('Field_Officer');

        // Draft JCR where the user is involved via personnel
        $draftJcr = Jcr::factory()->create([
            'status' => Jcr::STATUS_DRAFT,
            'personnel' => [
                ['user_id' => $user->id]
            ],
        ]);

        $this->assertTrue($user->can('update', $draftJcr));

        // Draft JCR where the user is NOT involved
        $draftNotInvolved = Jcr::factory()->create([
            'status' => Jcr::STATUS_DRAFT,
            'personnel' => [],
        ]);

        $this->assertFalse($user->can('update', $draftNotInvolved));

        // Final/Submitted JCR should not be editable by Field Officer even if involved
        $finalJcr = Jcr::factory()->create([
            'status' => Jcr::STATUS_PENDING_PARTY_CHIEF,
            'personnel' => [
                ['user_id' => $user->id]
            ],
        ]);

        $this->assertFalse($user->can('update', $finalJcr));
    }

    public function test_staff_and_technical_support_group_cannot_update()
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        $tsg = User::factory()->create();
        $tsg->assignRole('Technical_Support_Group');

        $jcr = Jcr::factory()->create();

        $this->assertFalse($staff->can('update', $jcr));
        $this->assertFalse($tsg->can('update', $jcr));
    }

    public function test_staff_cannot_create_jcr()
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        $this->assertFalse($staff->can('create', Jcr::class));
    }

    public function test_field_officer_can_create_jcr()
    {
        $user = User::factory()->create();
        $user->assignRole('Field_Officer');

        $this->assertTrue($user->can('create', Jcr::class));
    }

    public function test_technical_support_group_cannot_create_jcr()
    {
        $tsg = User::factory()->create();
        $tsg->assignRole('Technical_Support_Group');

        $this->assertFalse($tsg->can('create', Jcr::class));
    }
}
