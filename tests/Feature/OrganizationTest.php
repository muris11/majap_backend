<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\OrganizationStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_api_returns_name_in_description()
    {
        // Create a batch
        $batch = Batch::create([
            'name' => 'Batch 1',
            'year' => 2024,
            'is_active' => true,
        ]);

        // Create an organization member
        OrganizationStructure::create([
            'batch_id' => $batch->id,
            'position' => 'Ketua',
            'name' => 'Budi Santoso',
            'description' => 'Mahasiswa Teladan',
            'level' => 1,
            'order' => 1,
        ]);

        // Call the API
        $response = $this->getJson('/api/v1/organization');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Budi Santoso')
            ->assertJsonPath('data.0.description', 'Budi Santoso - Mahasiswa Teladan');
    }

    public function test_organization_api_returns_name_as_description_when_description_is_null()
    {
        // Create a batch
        $batch = Batch::create([
            'name' => 'Batch 1',
            'year' => 2024,
            'is_active' => true,
        ]);

        // Create an organization member without description
        OrganizationStructure::create([
            'batch_id' => $batch->id,
            'position' => 'Wakil Ketua',
            'name' => 'Siti Aminah',
            'description' => null,
            'level' => 2,
            'order' => 1,
        ]);

        // Call the API
        $response = $this->getJson('/api/v1/organization');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Siti Aminah')
            ->assertJsonPath('data.0.description', 'Siti Aminah');
    }
}
