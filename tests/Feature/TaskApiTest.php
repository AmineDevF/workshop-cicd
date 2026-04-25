<?php
namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_tasks(): void {
        Task::factory()->count(3)->create();
        $this->getJson('/api/tasks')
             ->assertStatus(200)
             ->assertJsonCount(3);
    }

    /** @test */
    public function it_returns_empty_array_when_no_tasks(): void {
        $this->getJson('/api/tasks')
             ->assertStatus(200)
             ->assertJson([]);
    }

    /** @test */
    public function it_can_create_a_task(): void {
        $this->postJson('/api/tasks', ['title' => 'Test task'])
             ->assertStatus(201)
             ->assertJsonFragment(['title' => 'Test task']);

        $this->assertDatabaseHas('tasks', ['title' => 'Test task']);
    }

    /** @test */
    public function it_validates_title_is_required(): void {
        $this->postJson('/api/tasks', [])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function it_can_show_a_task(): void {
        $task = Task::factory()->create();
        $this->getJson("/api/tasks/{$task->id}")
             ->assertStatus(200)
             ->assertJsonFragment(['id' => $task->id]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_task(): void {
        $this->getJson('/api/tasks/999')
             ->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_task(): void {
        $task = Task::factory()->create(['title' => 'Ancien titre']);
        $this->putJson("/api/tasks/{$task->id}", [
            'title'     => 'Nouveau titre',
            'completed' => true,
        ])
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Nouveau titre', 'completed' => true]);
    }

    /** @test */
    public function it_can_delete_a_task(): void {
        $task = Task::factory()->create();
        $this->deleteJson("/api/tasks/{$task->id}")
             ->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function health_endpoint_returns_ok(): void {
        $this->getJson('/api/health')
             ->assertStatus(200)
             ->assertJsonFragment(['status' => 'ok']);
    }
}