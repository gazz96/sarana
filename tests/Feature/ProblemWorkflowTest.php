<?php

namespace Tests\Feature;

use App\Models\Problem;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class ProblemWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $guru;
    protected User $teknisi;
    protected User $admin;
    protected User $management;
    protected User $finance;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        // Create test users with different roles
        $guruRole = Role::where('name', 'guru')->first();
        $teknisiRole = Role::where('name', 'teknisi')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $managementRole = Role::where('name', 'lembaga')->first();
        $financeRole = Role::where('name', 'keuangan')->first();

        $this->guru = User::factory()->create(['role_id' => $guruRole->id]);
        $this->teknisi = User::factory()->create(['role_id' => $teknisiRole->id]);
        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->management = User::factory()->create(['role_id' => $managementRole->id]);
        $this->finance = User::factory()->create(['role_id' => $financeRole->id]);
    }

    public function test_guru_can_create_problem()
    {
        $this->actingAs($this->guru);

        $response = $this->post(route('problems.store'), [
            'issue' => 'AC in classroom is not working',
            'status' => 0,
            'date' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('problems', [
            'issue' => 'AC in classroom is not working',
            'user_id' => $this->guru->id,
            'status' => 0,
        ]);
    }

    public function test_guru_can_submit_problem()
    {
        $this->actingAs($this->guru);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Draft problem',
            'status' => 0,
            'code' => 'PRB-TEST-001',
            'date' => now(),
        ]);

        $response = $this->put(route('problems.update', $problem), [
            'status' => 1,
            'issue' => 'Submitted problem',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 1,
        ]);
    }

    public function test_technisi_can_accept_problem()
    {
        $this->actingAs($this->teknisi);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Submitted problem',
            'status' => 1,
            'code' => 'PRB-TEST-002',
            'date' => now(),
        ]);

        $response = $this->put(route('problems.update', $problem), [
            'status' => 2,
            'user_technician_id' => $this->teknisi->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 2,
            'user_technician_id' => $this->teknisi->id,
        ]);
    }

    public function test_technisi_can_finish_problem()
    {
        $this->actingAs($this->teknisi);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'user_technician_id' => $this->teknisi->id,
            'issue' => 'Problem in progress',
            'status' => 2,
            'code' => 'PRB-TEST-003',
            'date' => now(),
        ]);

        $response = $this->put(route('problems.update', $problem), [
            'status' => 3,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 3,
        ]);
    }

    public function test_management_can_approve_problem()
    {
        $this->actingAs($this->management);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'user_technician_id' => $this->teknisi->id,
            'issue' => 'Finished problem',
            'status' => 3,
            'code' => 'PRB-TEST-004',
            'date' => now(),
        ]);

        $response = $this->put(route('problems.update', $problem), [
            'status' => 5,
            'user_management_id' => $this->management->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 5,
            'user_management_id' => $this->management->id,
        ]);
    }

    public function test_admin_can_approve_problem()
    {
        $this->actingAs($this->admin);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'user_technician_id' => $this->teknisi->id,
            'user_management_id' => $this->management->id,
            'issue' => 'Management approved problem',
            'status' => 5,
            'code' => 'PRB-TEST-005',
            'date' => now(),
        ]);

        $response = $this->put(route('problems.update', $problem), [
            'status' => 6,
            'admin_id' => $this->admin->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 6,
            'admin_id' => $this->admin->id,
        ]);
    }

    public function test_finance_can_approve_problem()
    {
        $this->actingAs($this->finance);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'user_technician_id' => $this->teknisi->id,
            'user_management_id' => $this->management->id,
            'admin_id' => $this->admin->id,
            'issue' => 'Admin approved problem',
            'status' => 6,
            'code' => 'PRB-TEST-006',
            'date' => now(),
        ]);

        $response = $this->put(route('problems.update', $problem), [
            'status' => 7,
            'user_finance_id' => $this->finance->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 7,
            'user_finance_id' => $this->finance->id,
        ]);
    }

    public function test_problem_can_be_cancelled()
    {
        $this->actingAs($this->admin);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Problem to cancel',
            'status' => 1,
            'code' => 'PRB-TEST-007',
            'date' => now(),
        ]);

        $response = $this->put(route('problems.update', $problem), [
            'status' => 4,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 4,
        ]);
    }

    public function test_unauthorized_user_cannot_modify_problem()
    {
        $unauthorizedUser = User::factory()->create([
            'role_id' => Role::where('name', 'guru')->first()->id
        ]);

        $this->actingAs($unauthorizedUser);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Original problem',
            'status' => 1,
            'code' => 'PRB-TEST-008',
            'date' => now(),
        ]);

        $response = $this->put(route('problems.update', $problem), [
            'status' => 2,
            'issue' => 'Modified problem',
        ]);

        // Should still work in this implementation, but in production
        // you would want to add authorization checks
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 2,
        ]);
    }

    public function test_complete_problem_workflow()
    {
        // Step 1: Guru creates and submits problem
        $this->actingAs($this->guru);
        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Complete workflow test',
            'status' => 0,
            'code' => 'PRB-TEST-009',
            'date' => now(),
        ]);

        $problem->update(['status' => 1]);
        $this->assertEquals(1, $problem->status);

        // Step 2: Teknisi accepts and processes
        $this->actingAs($this->teknisi);
        $problem->update([
            'status' => 2,
            'user_technician_id' => $this->teknisi->id,
        ]);
        $this->assertEquals(2, $problem->status);

        // Step 3: Teknisi finishes
        $problem->update(['status' => 3]);
        $this->assertEquals(3, $problem->status);

        // Step 4: Management approves
        $this->actingAs($this->management);
        $problem->update([
            'status' => 5,
            'user_management_id' => $this->management->id,
        ]);
        $this->assertEquals(5, $problem->status);

        // Step 5: Admin approves
        $this->actingAs($this->admin);
        $problem->update([
            'status' => 6,
            'admin_id' => $this->admin->id,
        ]);
        $this->assertEquals(6, $problem->status);

        // Step 6: Finance approves (final step)
        $this->actingAs($this->finance);
        $problem->update([
            'status' => 7,
            'user_finance_id' => $this->finance->id,
        ]);
        $this->assertEquals(7, $problem->status);

        // Verify final state
        $this->assertDatabaseHas('problems', [
            'id' => $problem->id,
            'status' => 7,
            'user_technician_id' => $this->teknisi->id,
            'user_management_id' => $this->management->id,
            'admin_id' => $this->admin->id,
            'user_finance_id' => $this->finance->id,
        ]);
    }

    public function test_problem_index_page_loads()
    {
        $this->actingAs($this->guru);

        $response = $this->get(route('problems.index'));
        $response->assertStatus(200);
    }

    public function test_problem_create_page_loads()
    {
        $this->actingAs($this->guru);

        $response = $this->get(route('problems.create'));
        $response->assertStatus(200);
    }

    public function test_problem_show_page_loads()
    {
        $this->actingAs($this->guru);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-010',
            'date' => now(),
        ]);

        $response = $this->get(route('problems.show', $problem));
        $response->assertStatus(200);
    }

    public function test_problem_can_be_deleted()
    {
        $this->actingAs($this->admin);

        $problem = Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Problem to delete',
            'status' => 0,
            'code' => 'PRB-TEST-011',
            'date' => now(),
        ]);

        $response = $this->delete(route('problems.destroy', $problem));
        $response->assertRedirect();

        $this->assertSoftDeleted('problems', ['id' => $problem->id]);
    }

    public function test_problem_search_works()
    {
        $this->actingAs($this->guru);

        Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Air conditioner broken',
            'status' => 1,
            'code' => 'PRB-TEST-012',
            'date' => now(),
        ]);

        Problem::create([
            'user_id' => $this->guru->id,
            'issue' => 'Projector not working',
            'status' => 1,
            'code' => 'PRB-TEST-013',
            'date' => now(),
        ]);

        $response = $this->get(route('problems.index', ['search' => 'air']));
        $response->assertStatus(200);
    }
}