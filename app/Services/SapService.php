<?php

namespace App\Services;

use App\Models\Jcr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class SapService
{
    /**
     * SAP Base URL - Configure this in your .env file
     * SAP_API_URL=https://your-sap-instance.com/api
     */
    private $sapApiUrl;
    private $sapApiKey;

    public function __construct()
    {
        $this->sapApiUrl = config('services.sap.api_url', env('SAP_API_URL'));
        $this->sapApiKey = config('services.sap.api_key', env('SAP_API_KEY'));
    }

    /**
     * Push JCR data to SAP and get document number
     *
     * @param Jcr $jcr
     * @return array ['success' => bool, 'document_number' => string|null, 'message' => string]
     */
    public function pushJcrToSap(Jcr $jcr): array
    {
        try {
            // Validate JCR is ready for SAP push
            if (!$jcr->canPushToSap()) {
                return [
                    'success' => false,
                    'document_number' => null,
                    'message' => 'JCR must be approved and signed by Operation Incharge before pushing to SAP.',
                ];
            }

            // Check if already pushed
            if ($jcr->isPushedToSap()) {
                return [
                    'success' => false,
                    'document_number' => $jcr->sap_document_number,
                    'message' => 'This JCR has already been pushed to SAP.',
                ];
            }

            // Prepare JCR data for SAP
            $sapPayload = $this->prepareSapPayload($jcr);

            // Send to SAP
            $response = $this->sendToSap($sapPayload);

            if ($response['success']) {
                // Extract document number from SAP response
                $documentNumber = $response['document_number'] ?? null;
                
                if ($documentNumber) {
                    // Update JCR with SAP document number and timestamp
                    $jcr->update([
                        'sap_document_number' => $documentNumber,
                        'sap_pushed_at' => now(),
                        'sap_status' => 'pushed',
                    ]);

                    Log::info("JCR {$jcr->id} successfully pushed to SAP with document number: {$documentNumber}");

                    return [
                        'success' => true,
                        'document_number' => $documentNumber,
                        'message' => "JCR pushed to SAP successfully. Document Number: {$documentNumber}",
                    ];
                } else {
                    return [
                        'success' => false,
                        'document_number' => null,
                        'message' => 'SAP response received but document number not found.',
                    ];
                }
            } else {
                // Log failed attempt
                $jcr->update(['sap_status' => 'failed']);
                
                Log::warning("JCR {$jcr->id} failed to push to SAP: " . $response['message']);

                return [
                    'success' => false,
                    'document_number' => null,
                    'message' => "SAP Push Failed: " . $response['message'],
                ];
            }
        } catch (Exception $e) {
            Log::error("Exception while pushing JCR {$jcr->id} to SAP: " . $e->getMessage());

            return [
                'success' => false,
                'document_number' => null,
                'message' => "Error: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Prepare JCR data for SAP in the format SAP expects
     *
     * @param Jcr $jcr
     * @return array
     */
    private function prepareSapPayload(Jcr $jcr): array
    {
        // Load related data
        $jcr->load(['users', 'logs', 'explosives', 'creator', 'partyChief', 'operationIncharge', 'timeRegister']);

        return [
            'jcr_id' => $jcr->id,
            'well_number' => $jcr->wellNo,
            'field_name' => $jcr->fieldName,
            'job_date' => $jcr->jobDate?->format('Y-m-d'),
            'job_number' => $jcr->jobNo,
            'work_order_date' => $jcr->workOrderDate?->format('Y-m-d'),
            'indent_number' => $jcr->indentNo,
            'rig_number' => $jcr->rigNo,
            'unit_number' => $jcr->unitNo,
            'logging_type' => $jcr->loggingType,
            'log_type' => $jcr->logType,
            'well_owner' => $jcr->wellOwner,
            'well_type' => $jcr->wellType,
            'rig_type' => $jcr->rigType,
            
            // Time information
            'assembled_datetime' => $jcr->assembled_date . ' ' . $jcr->assembled_time,
            'departure_office_datetime' => $jcr->depOffice_date . ' ' . $jcr->depOffice_time,
            'arrival_site_datetime' => $jcr->arrivalSite_date . ' ' . $jcr->arrivalSite_time,
            'indented_datetime' => $jcr->indented_date . ' ' . $jcr->indented_time,
            'well_readiness_datetime' => $jcr->wellReadiness_date . ' ' . $jcr->wellReadiness_time,
            'well_taken_datetime' => $jcr->wellTaken_date . ' ' . $jcr->wellTaken_time,
            'rig_up_datetime' => $jcr->rigUP_date . ' ' . $jcr->rigUP_time,
            'well_handover_datetime' => $jcr->wellHandOver_date . ' ' . $jcr->wellHandOver_time,
            'departure_site_datetime' => $jcr->depSite_date . ' ' . $jcr->depSite_time,
            'arrival_office_datetime' => $jcr->arrivalOffice_date . ' ' . $jcr->arrivalOffice_time,
            
            // Personnel
            'personnel' => $jcr->users->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ])->toArray(),
            
            // Signatures
            'creator_signature' => $jcr->creator_signature,
            'creator_signed_at' => $jcr->creator_signed_at,
            'party_chief_signature' => $jcr->party_chief_signature,
            'party_chief_signed_at' => $jcr->party_chief_signed_at,
            'operation_incharge_signature' => $jcr->operation_incharge_signature,
            'operation_incharge_signed_at' => $jcr->operation_incharge_signed_at,
            
            // Status
            'status' => $jcr->status,
            'remarks' => $jcr->remarks,
            'job_status' => $jcr->jobStatus,
        ];
    }

    /**
     * Send data to SAP API
     *
     * @param array $payload
     * @return array
     */
    private function sendToSap(array $payload): array
    {
        try {
            // If no SAP API URL configured, generate a mock document number for testing
            if (!$this->sapApiUrl) {
                Log::warning('SAP_API_URL not configured. Using mock document number for testing.');
                return $this->getMockSapResponse($payload);
            }

            // Use curl or HTTP client to send to SAP
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->sapApiUrl . '/jcr/push');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->sapApiKey,
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                return [
                    'success' => false,
                    'message' => "cURL Error: {$error}",
                ];
            }

            if ($httpCode !== 200 && $httpCode !== 201) {
                return [
                    'success' => false,
                    'message' => "SAP API returned HTTP {$httpCode}",
                ];
            }

            $data = json_decode($response, true);

            if (isset($data['success']) && $data['success']) {
                return [
                    'success' => true,
                    'document_number' => $data['document_number'] ?? null,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Unknown SAP error',
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate mock SAP response for testing (when SAP_API_URL is not configured)
     *
     * @param array $payload
     * @return array
     */
    private function getMockSapResponse(array $payload): array
    {
        // Generate a realistic SAP document number format
        // Format: SAP-YYYYMMDD-XXXXX (e.g., SAP-20260205-12345)
        $documentNumber = 'SAP-' . now()->format('Ymd') . '-' . Str::random(5);

        return [
            'success' => true,
            'document_number' => $documentNumber,
        ];
    }
}
