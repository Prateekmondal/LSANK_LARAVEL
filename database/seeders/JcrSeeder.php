<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jcr;
use App\Http\Controllers\JcrController;

class JcrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jcr = Jcr::create([
            'creator_id' => 20,
            'fieldName' => 'Test fieldname',
            'wellNo' => 'Test well no',
            'jobDate' => date('Y-m-d', strtotime('2025-01-01')),
            'jobNo' => 1,
            'workOrderDate' => date('Y-m-d', strtotime('2025-01-01')),
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

            'assembled_date' => date('Y-m-d', strtotime('2025-01-01')),
            'assembled_time' => date('H:i:s', strtotime('12:00')),
            'depOffice_date' => date('Y-m-d', strtotime('2025-01-01')),
            'depOffice_time' => date('H:i:s', strtotime('12:00')),
            'arrivalSite_date' => date('Y-m-d', strtotime('2025-01-01')),
            'arrivalSite_time' => date('H:i:s', strtotime('12:00')),
            'indented_date' => date('Y-m-d', strtotime('2025-01-01')),
            'indented_time' => date('H:i:s', strtotime('12:00')),
            'wellReadiness_date' => date('Y-m-d', strtotime('2025-01-01')),
            'wellReadiness_time' => date('H:i:s', strtotime('12:00')),
            'wellTaken_date' => date('Y-m-d', strtotime('2025-01-01')),
            'wellTaken_time' => date('H:i:s', strtotime('12:00')),
            'rigUP_date' => date('Y-m-d', strtotime('2025-01-01')),
            'rigUP_time' => date('H:i:s', strtotime('12:00')),
            'wellHandOver_date' => date('Y-m-d', strtotime('2025-01-01')),
            'wellHandOver_time' => date('H:i:s', strtotime('12:00')),
            'depSite_date' => date('Y-m-d', strtotime('2025-01-01')),
            'depSite_time' => date('H:i:s', strtotime('12:00')),
            'arrivalOffice_date' => date('Y-m-d', strtotime('2025-01-01')),
            'arrivalOffice_time' => date('H:i:s', strtotime('12:00')),
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
            'lastcirc_from' => date('Y-m-d H:i:s', strtotime('2025-01-01 12:00:00')),
            'lastcirc_to' => date('Y-m-d H:i:s', strtotime('2025-01-01 12:00:00')),

            'cableSize' =>'5/16',
            'insulation' =>'Good',
            'shoeDate' =>'2025-01-01',
            'weakPoint' => '7+2',
            'cableHeadSize' =>'1 11/16',
            'cableLength' =>6100.1,
            'initialLength' =>6150.5,
            'surfaceEquipment' =>'ok',
            'automobile' => 'Test',
            'wellCondition' => 'Test',
            'timeLoss' => 'Test',

            

            'attempted' => 123,
            'recovered' => 123,
            'missFire' => 123,
            'barrelLost' => 123,
            'emptyBarrel' => 123,
            'chargeUsed' => 123,

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

        $personnelIds = [
            [
                'user_id' => 1
            ],
            [
                'user_id' => 2
            ],
        ];

        $jcr->users()->sync($personnelIds);
        
        $logrecorded = [
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
            ];
        for($i = 0; $i > count($logrecorded); $i++){
            $jcr->logs()->create($logrecorded[$i]);
        }

        $explosive = [
            0=>[
                'explosive' => 'Test Explosive 1',
                'issued' => 123.50,
                'used' => 12.50,
                'returned' => 111.50,
            ],
            1=>[
                'explosive' => 'Test Explosive 2',
                'issued' => 124.50,
                'used' => 11.50,
                'returned' => 113.50,
            ],
            ];
        for($i = 0; $i >= count($explosive); $i++){
        $jcr->explosives()->create($explosive['0']);
        }
    }
}
