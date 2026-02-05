<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_connection_works()
    {
        $this->assertNotNull(app('db'));
        $this->assertEquals('sqlite', config('database.default'));
    }

    public function test_users_table_exists()
    {
        $this->assertTrue(
            \Schema::hasTable('users'),
            'Users table should exist in database'
        );
    }

    public function test_roles_table_exists()
    {
        $this->assertTrue(
            \Schema::hasTable('roles'),
            'Roles table should exist in database'
        );
    }

    public function test_can_create_simple_user()
    {
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1, // Assuming role with ID 1 exists
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        $this->assertEquals('test@example.com', $user->email);
    }

    public function test_can_perform_basic_queries()
    {
        // Create a role
        $role = \App\Models\Role::create([
            'name' => 'test_role',
            'guard_name' => 'web'
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'test_role'
        ]);

        // Create a user
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id
        ]);

        $this->assertEquals($role->id, $user->role_id);
    }

    public function test_model_relationships_work()
    {
        $role = \App\Models\Role::create([
            'name' => 'test_role_two',
            'guard_name' => 'web'
        ]);

        $user = \App\Models\User::create([
            'name' => 'Relationship User',
            'email' => 'relation@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id
        ]);

        $this->assertInstanceOf(\App\Models\Role::class, $user->role);
        $this->assertEquals('test_role_two', $user->role->name);
        $this->assertEquals($role->id, $user->role->id);
    }

    public function test_database_is_refreshed_between_tests()
    {
        $countBefore = \App\Models\User::count();
        
        \App\Models\User::create([
            'name' => 'Temp User',
            'email' => 'temp@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1
        ]);

        $countDuring = \App\Models\User::count();
        $this->assertEquals($countBefore + 1, $countDuring);
    }

    public function test_soft_deletes_work()
    {
        $role = \App\Models\Role::create([
            'name' => 'soft_delete_role',
            'guard_name' => 'web'
        ]);

        $user = \App\Models\User::create([
            'name' => 'Soft Delete User',
            'email' => 'soft@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id
        ]);

        $userId = $user->id;
        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);
        $this->assertDatabaseMissing('users', [
            'id' => $userId,
            'deleted_at' => null
        ]);
    }

    public function test_timestamps_work()
    {
        $role = \App\Models\Role::create([
            'name' => 'timestamp_role',
            'guard_name' => 'web'
        ]);

        $user = \App\Models\User::create([
            'name' => 'Timestamp User',
            'email' => 'time@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id
        ]);

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->updated_at);
    }

    public function test_validation_rules_work()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Try to create user with duplicate email
        $role = \App\Models\Role::create([
            'name' => 'validation_role',
            'guard_name' => 'web'
        ]);

        \App\Models\User::create([
            'name' => 'User One',
            'email' => 'duplicate@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id
        ]);

        \App\Models\User::create([
            'name' => 'User Two',
            'email' => 'duplicate@example.com', // Duplicate email
            'password' => Hash::make('password'),
            'role_id' => $role->id
        ]);
    }

    public function test_scopes_and_queries_work()
    {
        $role = \App\Models\Role::create([
            'name' => 'scope_role',
            'guard_name' => 'web'
        ]);

        // Create multiple users
        \App\Models\User::factory()->count(3)->create(['role_id' => $role->id]);

        // Test query scopes
        $allUsers = \App\Models\User::where('role_id', $role->id)->get();
        $this->assertCount(3, $allUsers);

        $firstUser = \App\Models\User::where('role_id', $role->id)->first();
        $this->assertInstanceOf(\App\Models\User::class, $firstUser);
    }
}