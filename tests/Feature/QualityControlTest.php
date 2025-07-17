<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ProductionBatch;
use App\Models\QualityControl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QualityControlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_user_can_create_quality_control()
    {
        $user = User::factory()->create();
        $batch = ProductionBatch::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('quality-controls.store'), [
            'production_batch_id' => $batch->id,
            'tester_id' => $user->id,
            'defects_found' => 'None',
            'status' => 'passed',
            'tested_at' => now()->format('Y-m-d H:i:s'),
            'notes' => 'All good',
        ]);

        $response->assertRedirect(route('quality-controls.index'));
        $this->assertDatabaseHas('quality_controls', [
            'production_batch_id' => $batch->id,
            'tester_id' => $user->id,
            'status' => 'passed',
        ]);
    }

    /** @test */
    public function validation_fails_with_missing_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post(route('quality-controls.store'), []);
        $response->assertSessionHasErrors(['production_batch_id', 'tester_id', 'status']);
    }

    /** @test */
    public function unauthorized_user_cannot_create_quality_control()
    {
        $response = $this->post(route('quality-controls.store'), []);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function can_view_quality_control_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('quality-controls.index'));
        $response->assertStatus(200);
        $response->assertSee('Quality Controls');
    }

    // Add more tests for edit, update, delete, filtering, etc.
} 