<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use App\Jobs\CreateTenantJob;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Str;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure tenant ID is lowercase
        $data['id'] = strtolower($data['id'] ?? '');
        // dd(gettype($data['id']));
        
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Create the tenant in the database
        $tenant = static::getModel()::create($data);

        // Create provided domains or auto-configure one if none provided
        if (isset($data['domains']) && !empty($data['domains'])) {
            foreach ($data['domains'] as $d) {
                $domainValue = is_array($d) && isset($d['domain']) ? $d['domain'] : ($d ?? null);
                if ($domainValue) {
                    try {
                        Domain::create([
                            'domain' => $domainValue,
                            'tenant_id' => $tenant->id,
                        ]);
                    } catch (\Exception $e) {
                        // ignore duplicates or failures here; admin can adjust later
                        continue;
                    }
                }
            }
        } else {
            // Generate subdomain based on tenant ID and current hostname
            $hostname = request()->getHost();
            // Remove port if present
            $hostname = preg_replace('/:.*/', '', $hostname);
            
            // Remove www. prefix if present
            $hostname = preg_replace('/^www\./', '', $hostname);

            // Remove subdomain if present
            $hostname = preg_replace('/^[a-zA-Z0-9\-_]+\./', '', $hostname);
            
            // Create subdomain: tenantid.hostname
            $subdomain = $tenant->id . '.' . $hostname;
            
            // Create domain entry
            Domain::create([
                'domain' => $subdomain,
                'tenant_id' => $tenant->id,
            ]);
        }

        // Dispatch the background job to run migrations and seeders
        CreateTenantJob::dispatch($tenant->id, $data);

        return $tenant;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tenant created successfully! Migrations and seeders are running in the background.';
    }
}
