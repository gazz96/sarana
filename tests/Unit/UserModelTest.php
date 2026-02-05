<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $role = Role::where('name', 'guru')->first();
        
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    public function test_user_belongs_to_role()
    {
        $role = Role::where('name', 'admin')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertEquals($role->id, $user->role->id);
        $this->assertEquals('admin', $user->role->name);
    }

    public function test_user_has_problems()
    {
        $guruRole = Role::where('name', 'guru')->first();
        $user = User::factory()->create(['role_id' => $guruRole->id]);

        $problem = \App\Models\Problem::create([
            'user_id' => $user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-001',
            'date' => now(),
        ]);

        $this->assertCount(1, $user->problems);
        $this->assertEquals('Test problem', $user->problems->first()->issue);
    }

    public function test_user_can_authenticate()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertFalse(Hash::check('wrongpassword', $user->password));
    }

    public function test_user_email_is_unique()
    {
        $role = Role::where('name', 'guru')->first();
        
        User::create([
            'role_id' => $role->id,
            'name' => 'Test User 1',
            'email' => 'unique@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'role_id' => $role->id,
            'name' => 'Test User 2',
            'email' => 'unique@example.com', // Duplicate email
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_user_fillable_attributes()
    {
        $role = Role::where('name', 'teknisi')->first();
        $userData = [
            'role_id' => $role->id,
            'name' => 'Technician User',
            'email' => 'tech@example.com',
            'password' => Hash::make('password123'),
        ];

        $user = User::create($userData);

        $this->assertEquals($userData['role_id'], $user->role_id);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
    }

    public function test_user_has_hidden_attributes()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $array = $user->toArray();
        
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_user_has_casts()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->email_verified_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->updated_at);
    }

    public function test_user_has_technician_problems()
    {
        $teknisiRole = Role::where('name', 'teknisi')->first();
        $guruRole = Role::where('name', 'guru')->first();

        $guru = User::factory()->create(['role_id' => $guruRole->id]);
        $teknisi = User::factory()->create(['role_id' => $teknisiRole->id]);

        $problem = \App\Models\Problem::create([
            'user_id' => $guru->id,
            'user_technician_id' => $teknisi->id,
            'issue' => 'Test problem',
            'status' => 2,
            'code' => 'PRB-TEST-002',
            'date' => now(),
        ]);

        $this->assertCount(1, $teknisi->technicianProblems);
        $this->assertEquals($problem->id, $teknisi->technicianProblems->first()->id);
    }

    public function test_user_has_api_tokens()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $token = $user->createToken('test-token')->plainTextToken;

        $this->assertNotEmpty($token);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => get_class($user),
        ]);
    }

    public function test_user_can_send_notifications()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $notification = \Illuminate\Notifications\AnonymousNotifiable::instance()
            ->route('mail', $user->email);

        $this->assertEquals($user->email, $notification->routes['mail']);
    }

    public function test_user_has_notification_preferences()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $preferences = $user->notificationPreferences()->create([
            'in_app_enabled' => true,
            'email_enabled' => true,
        ]);

        $this->assertInstanceOf(\App\Models\NotificationPreference::class, $preferences);
        $this->assertTrue($preferences->in_app_enabled);
    }

    public function test_user_soft_delete()
    {
        $role = Role::where('name', 'guru')->first();
        $user = User::factory()->create(['role_id' => $role->id]);
        $userId = $user->id;

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);
        
        // Check that user can be restored
        $user->restore();
        $this->assertDatabaseHas('users', ['id' => $userId]);
    }

    public function test_user_scope_by_role()
    {
        $guruRole = Role::where('name', 'guru')->first();
        $teknisiRole = Role::where('name', 'teknisi')->first();

        User::factory()->count(3)->create(['role_id' => $guruRole->id]);
        User::factory()->count(2)->create(['role_id' => $teknisiRole->id]);

        $guruUsers = User::where('role_id', $guruRole->id)->get();
        $teknisiUsers = User::where('role_id', $teknisiRole->id)->get();

        $this->assertCount(3, $guruUsers);
        $this->assertCount(2, $teknisiUsers);
    }
}