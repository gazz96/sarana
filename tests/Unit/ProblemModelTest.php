<?php

namespace Tests\Unit;

use App\Models\Problem;
use App\Models\User;
use App\Models\Good;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ProblemModelTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $technician;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $guruRole = \App\Models\Role::where('name', 'guru')->first();
        $teknisiRole = \App\Models\Role::where('name', 'teknisi')->first();
        $adminRole = \App\Models\Role::where('name', 'admin')->first();

        $this->user = User::factory()->create(['role_id' => $guruRole->id]);
        $this->technician = User::factory()->create(['role_id' => $teknisiRole->id]);
        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
    }

    public function test_problem_can_be_created()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-001',
            'date' => now(),
        ]);

        $this->assertDatabaseHas('problems', [
            'code' => 'PRB-TEST-001',
            'issue' => 'Test problem',
            'status' => 0,
        ]);

        $this->assertEquals('Test problem', $problem->issue);
        $this->assertEquals(0, $problem->status);
        $this->assertEquals('PRB-TEST-001', $problem->code);
    }

    public function test_problem_has_status_constants()
    {
        $this->assertIsArray(Problem::$STATUS);
        $this->assertCount(8, Problem::$STATUS);
        $this->assertEquals('DRAFT', Problem::$STATUS[0]);
        $this->assertEquals('DIAJUKAN', Problem::$STATUS[1]);
        $this->assertEquals('PROSES', Problem::$STATUS[2]);
    }

    public function test_problem_belongs_to_user()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-002',
            'date' => now(),
        ]);

        $this->assertInstanceOf(User::class, $problem->user);
        $this->assertEquals($this->user->id, $problem->user->id);
        $this->assertEquals($this->user->name, $problem->user->name);
    }

    public function test_problem_can_have_technician()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'user_technician_id' => $this->technician->id,
            'issue' => 'Test problem',
            'status' => 2,
            'code' => 'PRB-TEST-003',
            'date' => now(),
        ]);

        $this->assertInstanceOf(User::class, $problem->technician);
        $this->assertEquals($this->technician->id, $problem->technician->id);
    }

    public function test_problem_can_have_items()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-004',
            'date' => now(),
        ]);

        $location = Location::factory()->create();
        $good = Good::factory()->create(['location_id' => $location->id]);

        $problem->items()->create([
            'good_id' => $good->id,
            'quantity' => 2,
            'price' => 50000,
        ]);

        $this->assertCount(1, $problem->items);
        $this->assertEquals(50000, $problem->items->first()->price);
    }

    public function test_problem_status_workflow()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-005',
            'date' => now(),
        ]);

        // Draft to Submitted
        $problem->update(['status' => 1]);
        $this->assertEquals(1, $problem->status);

        // Submitted to In Progress (assigned to technician)
        $problem->update([
            'status' => 2,
            'user_technician_id' => $this->technician->id
        ]);
        $this->assertEquals(2, $problem->status);
        $this->assertEquals($this->technician->id, $problem->user_technician_id);

        // In Progress to Finished
        $problem->update(['status' => 3]);
        $this->assertEquals(3, $problem->status);

        // Finished to Management Approved
        $problem->update([
            'status' => 5,
            'user_management_id' => $this->admin->id
        ]);
        $this->assertEquals(5, $problem->status);
    }

    public function test_problem_can_be_cancelled()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem',
            'status' => 1,
            'code' => 'PRB-TEST-006',
            'date' => now(),
        ]);

        $problem->update(['status' => 4]);
        $this->assertEquals(4, $problem->status);
        $this->assertEquals('DIBATALKAN', Problem::$STATUS[4]);
    }

    public function test_problem_code_is_unique()
    {
        Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem 1',
            'status' => 0,
            'code' => 'PRB-UNIQUE-001',
            'date' => now(),
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem 2',
            'status' => 0,
            'code' => 'PRB-UNIQUE-001', // Duplicate code
            'date' => now(),
        ]);
    }

    public function test_problem_fillable_attributes()
    {
        $problemData = [
            'user_id' => $this->user->id,
            'issue' => 'Test issue',
            'status' => 0,
            'code' => 'PRB-TEST-007',
            'date' => now(),
            'note' => 'Test note',
            'user_technician_id' => $this->technician->id,
        ];

        $problem = Problem::create($problemData);

        foreach ($problemData as $key => $value) {
            $this->assertEquals($value, $problem->$key);
        }
    }

    public function test_problem_has_management_approval_relation()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'user_management_id' => $this->admin->id,
            'issue' => 'Test problem',
            'status' => 5,
            'code' => 'PRB-TEST-008',
            'date' => now(),
        ]);

        $this->assertInstanceOf(User::class, $problem->management);
        $this->assertEquals($this->admin->id, $problem->management->id);
    }

    public function test_problem_has_finance_approval_relation()
    {
        $financeRole = \App\Models\Role::where('name', 'keuangan')->first();
        $financeUser = User::factory()->create(['role_id' => $financeRole->id]);

        $problem = Problem::create([
            'user_id' => $this->user->id,
            'user_finance_id' => $financeUser->id,
            'issue' => 'Test problem',
            'status' => 7,
            'code' => 'PRB-TEST-009',
            'date' => now(),
        ]);

        $this->assertInstanceOf(User::class, $problem->finance);
        $this->assertEquals($financeUser->id, $problem->finance->id);
    }

    public function test_problem_soft_delete()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-010',
            'date' => now(),
        ]);

        $problemId = $problem->id;
        $problem->delete();

        $this->assertSoftDeleted('problems', ['id' => $problemId]);
    }

    public function test_problem_date_casting()
    {
        $testDate = now();
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-011',
            'date' => $testDate,
        ]);

        $this->assertEquals($testDate->format('Y-m-d'), $problem->date->format('Y-m-d'));
        $this->assertInstanceOf(\Carbon\Carbon::class, $problem->date);
    }

    public function test_problem_scope_by_status()
    {
        Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Draft problem',
            'status' => 0,
            'code' => 'PRB-TEST-012',
            'date' => now(),
        ]);

        Problem::create([
            'user_id' => $this->user->id,
            'issue' => 'Submitted problem',
            'status' => 1,
            'code' => 'PRB-TEST-013',
            'date' => now(),
        ]);

        $draftProblems = Problem::where('status', 0)->get();
        $submittedProblems = Problem::where('status', 1)->get();

        $this->assertCount(1, $draftProblems);
        $this->assertCount(1, $submittedProblems);
        $this->assertEquals('Draft problem', $draftProblems->first()->issue);
        $this->assertEquals('Submitted problem', $submittedProblems->first()->issue);
    }

    public function test_problem_has_admin_relation()
    {
        $problem = Problem::create([
            'user_id' => $this->user->id,
            'admin_id' => $this->admin->id,
            'issue' => 'Test problem',
            'status' => 6,
            'code' => 'PRB-TEST-014',
            'date' => now(),
        ]);

        $this->assertInstanceOf(User::class, $problem->admin);
        $this->assertEquals($this->admin->id, $problem->admin->id);
    }
}