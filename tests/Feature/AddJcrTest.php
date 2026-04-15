<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AddJcrTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_addjcr_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $user->assignRole('field_officer');

        $response = $this
            ->actingAs($user)
            ->get('/jcr/create');

        $response->assertOk();
    }

    public function test_users_can_add_new_jcr(): void
    {
        $user = User::factory()->create();
        $user->assignRole('field_officer');

        $response = $this
            ->actingAs($user)
            ->post('/jcr', [
            'fieldName' => 'Test fieldname',
            'wellNo' => 'Test well no',
            'jobDate' => date('d.m.Y', strtotime('01.01.2025')),
            'jobNo' => 1,
            'workOrderDate' => date('d.m.Y', strtotime('01.01.2025')),
            'indentNo' => 'LG000123456',
            'rigNo' => 'Test-123',
            'kb' => 10.2,
            'gl' => 8.25,
            'unitNo' => 'XX-11-Y-0000',
            'logging_unit_type' => 'departmental',
            'loggingType' => 'Test logging',
            'logType' => 'Test log',
            'wellOwner' => 'Test well owner',
            'mastVanNo' => '1234/5678',
            'lvNo' => 'Test LMV',
            'wellType' => 'Test well',
            'rigType' => 'Test rig',

            'assembled_date' => date('d.m.Y', strtotime('01.01.2025')),
            'assembled_time' => date('h:i', strtotime('12:00')),
            'depOffice_date' => date('d.m.Y', strtotime('01.01.2025')),
            'depOffice_time' => date('h:i', strtotime('12:00')),
            'arrivalSite_date' => date('d.m.Y', strtotime('01.01.2025')),
            'arrivalSite_time' => date('h:i', strtotime('12:00')),
            'indented_date' => date('d.m.Y', strtotime('01.01.2025')),
            'indented_time' => date('h:i', strtotime('12:00')),
            'wellReadiness_date' => date('d.m.Y', strtotime('01.01.2025')),
            'wellReadiness_time' => date('h:i', strtotime('12:00')),
            'wellTaken_date' => date('d.m.Y', strtotime('01.01.2025')),
            'wellTaken_time' => date('h:i', strtotime('12:00')),
            'rigUP_date' => date('d.m.Y', strtotime('01.01.2025')),
            'rigUP_time' => date('h:i', strtotime('12:00')),
            'wellHandOver_date' => date('d.m.Y', strtotime('01.01.2025')),
            'wellHandOver_time' => date('h:i', strtotime('12:00')),
            'depSite_date' => date('d.m.Y', strtotime('01.01.2025')),
            'depSite_time' => date('h:i', strtotime('12:00')),
            'arrivalOffice_date' => date('d.m.Y', strtotime('01.01.2025')),
            'arrivalOffice_time' => date('h:i', strtotime('12:00')),
            'preparationTime' => 1.5000,
            'postProceTime' => 1.5000,

            'depthDriller' => '1',
            'depthLogger' =>'1',
            'casingSize' =>'1',
            'casingShoeDriller' =>'1',
            'casingShoeLogger' =>'1',
            'floatCollar' =>'1',
            'bitSize' =>'1',
            'tubingSize' =>'1',
            't_shoe_Packer' =>'1',
            's_nippletopexp' =>'1',
            'THP' =>'1',
            'maxDevAt' =>'1',
            'distTo_FroKms' =>'1',

            'rm' =>'1.0',
            'rmtemp' =>'20',
            'rmf' =>'1',
            'rmftemp' =>'1',
            'rmc' =>'1',
            'rmctemp' =>'1',
            'bht' =>'1',
            'bhtdepth' =>'1',
            'spgr' =>'1',
            'viscosity' =>'1',
            'mudType' => 'TEST MUD',
            'waterloss' =>'1',
            'ph' =>'1',
            'oilpercnt' =>'1',
            'kcl_barytes' =>'1',
            'salinity' =>'1',
            'lastcirc' => '',
            'lastcirc_from' => date('d-m-Y H:i', strtotime('01-01-2025 12:00')),
            'lastcirc_to' => date('d-m-Y H:i', strtotime('01-01-2025 12:00')),

            'cableSize' =>'5/16',
            'insulation' =>'Good',
            'shoeDate' =>'01.01.2025',
            'weakPoint' => '7+2',
            'cableHeadSize' =>'1 11/16',
            'cableLength' =>6100.1,
            'initialLength' =>6150.5,
            'surfaceEquipment' =>'ok',
            'automobile' => 'Test',
            'wellCondition' => 'Test',
            'timeLoss' => 'Test',

            'personnel' => [
                0 => [
                    'user_id' => 1
                ],
            ],
            [
                0 => [
                    'user_id' => 2
                ],
            ],

            'logrecorded' => [
                0 => [
                    'runNo' => 1,
                    'logRecorded' => 'Test',
                    'bottomDepth' => 123.05,
                    'topDepth' => 123.10,
                    'toolNo' => 'Test',
                    'logQuality' => 'Test',
                    'bottomShotDepth' => 123.01,
                    'topShotDepth' => 123.02,
                    'charge' => 'Test',
                    'chargeNo' => 10,
                    'primaChord' => 'Test',
                    'primaChordQty' => 12.01,
                    'fuse' => 'Test',
                    'fuseNo' => 1,
                    'fMf' => 'Test',
                ],
                1 => [
                    'runNo' => 2,
                    'logRecorded' => 'Test',
                    'bottomDepth' => 123.05,
                    'topDepth' => 123.10,
                    'toolNo' => 'Test',
                    'logQuality' => 'Test',
                    'bottomShotDepth' => 123.01,
                    'topShotDepth' => 123.02,
                    'charge' => 'Test',
                    'chargeNo' => 10,
                    'primaChord' => 'Test',
                    'primaChordQty' => 12.01,
                    'fuse' => 'Test',
                    'fuseNo' => 1,
                    'fMf' => 'Test',
                ],
            ],

            'attempted' => 123,
            'recovered' => 123,
            'missFire' => 123,
            'barrelLost' => 123,
            'emptyBarrel' => 123,
            'chargeUsed' => 123,

            'explosive' => [
                0 => [
                    'explosive' => 'Test Explosive 1',
                    'issued' => 123.50,
                    'used' => 12.50,
                    'returned' => 111.50,
                ],
                1 => [
                    'explosive' => 'Test Explosive 2',
                    'issued' => 124.50,
                    'used' => 11.50,
                    'returned' => 113.50,
                ],
            ],

            'permitType' => 'Test',
            'permitNo' => 'Test',
            'permitWork' => 1,
            'elecLockout' => 123,
            'elecLockoutNo' => 'Test',
            'safetyMeeting' => 1,
            'jobCloseMeeting' => 1,
            'nearMiss' => 1,
            'nearMissDesc' => 'Test',
            'jobStatus' => 'Test',
            'remarks' => 'Test',
            'objective' => 'Test',
            'observations' => 'Test',
            'contingents' => 'Test',
            'final_submit' => 1,
        ]);

        $response->assertStatus(201);
    }
}
