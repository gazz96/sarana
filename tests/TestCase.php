<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations only once
        // RefreshDatabase trait handles database reset between tests
    }

    /**
     * Clean up the test environment.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Create a user with the given role.
     */
    protected function createUserWithRole(string $roleName)
    {
        $role = \App\Models\Role::where('name', $roleName)->first();
        
        if (!$role) {
            $role = \App\Models\Role::create([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }

        return \App\Models\User::factory()->create(['role_id' => $role->id]);
    }

    /**
     * Create an authenticated user for testing.
     */
    protected function authenticateUser(string $roleName = 'guru')
    {
        $user = $this->createUserWithRole($roleName);
        $this->actingAs($user);
        
        return $user;
    }

    /**
     * Create a problem for testing.
     */
    protected function createProblemForUser($user, array $attributes = [])
    {
        return \App\Models\Problem::create(array_merge([
            'user_id' => $user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-' . rand(1000, 9999),
            'date' => now(),
        ], $attributes));
    }

    /**
     * Assert that a notification was sent to a user.
     */
    protected function assertNotificationSent($user, $notificationClass)
    {
        \Illuminate\Support\Facades\Notification::assertSentTo(
            $user,
            $notificationClass
        );
    }

    /**
     * Assert that no notifications were sent.
     */
    protected function assertNoNotificationsSent()
    {
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }
}
