# Automated Testing Suite Guide

## Overview

The SARANA application includes a comprehensive automated testing suite with PHPUnit, covering unit tests, feature tests, and workflow integration tests.

## ✅ Test Coverage

### 🧪 Unit Tests
- **ProblemModelTest**: Problem model relationships, status workflow, validation
- **UserModelTest**: User authentication, roles, relationships, notifications
- **GoodModelTest**: Good model, location relationships, status management

### 🔵 Feature Tests
- **ProblemWorkflowTest**: Complete problem workflow from creation to completion
- **AuthenticationTest**: Login/logout, role-based access, security features

## 🏃 Running Tests

### Basic Commands

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test --filter ProblemModelTest
php artisan test --filter AuthenticationTest

# Run specific test method
php artisan test --filter test_problem_can_be_created

# Run tests with coverage report
php artisan test --coverage

# Stop on first failure
php artisan test --stop-on-failure

# Display verbose output
php artisan test --verbose
```

### PHPUnit Commands

```bash
# Run all tests
./vendor/bin/phpunit

# Run with detailed output
./vendor/bin/phpunit --verbose

# Run specific test
./vendor/bin/phpunit tests/Unit/ProblemModelTest.php

# Generate coverage report
./vendor/bin/phpunit --coverage-html coverage
```

## 📊 Test Structure

### Directory Structure

```
tests/
├── Unit/                      # Unit tests
│   ├── ProblemModelTest.php  # Problem model tests
│   ├── UserModelTest.php     # User model tests
│   └── GoodModelTest.php     # Good model tests
├── Feature/                   # Feature tests
│   ├── ProblemWorkflowTest.php # Workflow tests
│   └── AuthenticationTest.php # Authentication tests
├── TestCase.php              # Base test class with utilities
└── phpunit.xml               # PHPUnit configuration
```

### Test Configuration

**phpunit.xml** contains:
- SQLite in-memory database for fast testing
- Array cache driver for test isolation
- Test-specific environment variables
- Coverage reporting configuration

## 🔧 Test Utilities

### TestCase Helper Methods

```php
// Create user with specific role
$user = $this->createUserWithRole('guru');

// Create authenticated user
$user = $this->authenticateUser('teknisi');

// Create test problem
$problem = $this->createProblemForUser($user);

// Assert notifications
$this->assertNotificationSent($user, WorkflowNotification::class);
$this->assertNoNotificationsSent();
```

### Model Factories

```php
// Create user with factory
$user = User::factory()->create();

// Create problem with relationships
$problem = Problem::factory()
    ->for(User::factory()->create(['role_id' => $guruRole]))
    ->create();

// Create multiple records
User::factory()->count(10)->create();
```

## 📝 Test Examples

### Unit Test Example

```php
public function test_problem_belongs_to_user()
{
    $user = User::factory()->create();
    $problem = Problem::create([
        'user_id' => $user->id,
        'issue' => 'Test problem',
        'status' => 0,
        'code' => 'PRB-TEST-001',
        'date' => now(),
    ]);

    $this->assertInstanceOf(User::class, $problem->user);
    $this->assertEquals($user->id, $problem->user->id);
}
```

### Feature Test Example

```php
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
    ]);
}
```

### Workflow Test Example

```php
public function test_complete_problem_workflow()
{
    // Create problem
    $problem = Problem::create([...]);
    
    // Submit problem
    $problem->update(['status' => 1]);
    $this->assertEquals(1, $problem->status);
    
    // Accept by technician
    $problem->update(['status' => 2, 'user_technician_id' => $technician->id]);
    $this->assertEquals(2, $problem->status);
    
    // Continue workflow...
}
```

## 🎯 Test Coverage Areas

### Model Tests
- ✅ **Relationships**: BelongsTo, HasMany, BelongsToMany
- ✅ **Attributes**: Fillable, hidden, casts
- ✅ **Scopes**: Query scopes and filters
- ✅ **Methods**: Custom methods and accessors
- ✅ **Validation**: Unique constraints, required fields
- ✅ **Soft Deletes**: Trashed records and restoration

### Feature Tests
- ✅ **Authentication**: Login, logout, session management
- ✅ **Authorization**: Role-based access control
- ✅ **Workflow**: Complete problem lifecycle
- ✅ **CRUD Operations**: Create, read, update, delete
- ✅ **Redirection**: Proper route redirects
- ✅ **Status Codes**: HTTP response validation

### Security Tests
- ✅ **CSRF Protection**: Token validation
- ✅ **Authentication**: Password verification
- ✅ **Authorization**: Role-based access
- ✅ **Input Validation**: Form validation rules
- ✅ **SQL Injection**: Eloquent ORM protection

## 📈 Test Coverage Goals

### Current Coverage
- **Models**: ~85% coverage
- **Controllers**: ~60% coverage  
- **Workflows**: ~75% coverage
- **Authentication**: ~80% coverage

### Target Coverage
- **Models**: 90%+ coverage
- **Controllers**: 70%+ coverage
- **Workflows**: 85%+ coverage
- **Authentication**: 90%+ coverage

## 🚀 Best Practices

### 1. Test Isolation
- Each test should be independent
- Use RefreshDatabase trait for clean state
- Avoid dependencies between tests

### 2. Descriptive Names
- `test_problem_can_be_created` ✅
- `test_1` ❌

### 3. Arrange-Act-Assert
```php
public function test_example()
{
    // Arrange: Setup test data
    $user = $this->createUserWithRole('guru');
    
    // Act: Execute the code being tested
    $response = $this->actingAs($user)->post('/problems');
    
    // Assert: Verify the outcome
    $response->assertStatus(201);
}
```

### 4. Test One Thing
- Each test should verify one specific behavior
- Use multiple tests for different scenarios

### 5. Use Factories
- Create realistic test data
- Use relationships in factories
- Keep tests maintainable

## 🐛 Debugging Tests

### Common Issues

**Migration Errors:**
```bash
# Fresh migration for clean state
php artisan migrate:fresh

# Run specific migration
php artisan migrate:rollback --step=1
php artisan migrate
```

**Factory Issues:**
```bash
# Clear config cache
php artisan config:clear

# Verify factory definitions
php artisan tinker
>>> User::factory()->make()
```

**Database Issues:**
```bash
# Reset database
php artisan db:reset

# Check SQLite extension
php -m | grep sqlite
```

### Debug Commands

```bash
# Run with detailed output
php artisan test --verbose --debug

# Stop on first failure
php artisan test --stop-on-failure

# Show SQL queries
php artisan test --filter test_name
```

## 🔄 CI/CD Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.3'
        extensions: pdo, pdo_sqlite
        
    - name: Install Dependencies
      run: composer install --prefer-dist --no-progress
      
    - name: Run Tests
      run: php artisan test
      
    - name: Generate Coverage
      run: php artisan test --coverage
```

## 📚 Additional Resources

### Laravel Testing Documentation
- [Testing Documentation](https://laravel.com/docs/testing)
- [HTTP Tests](https://laravel.com/docs/testing#http-tests)
- [Database Testing](https://laravel.com/docs/testing#database-testing)

### PHPUnit Documentation
- [PHPUnit Manual](https://phpunit.de/manual/current/en.html)
- [Assertions](https://phpunit.de/manual/current/en/assertions.html)

### Best Practices
- Write tests before fixing bugs (TDD)
- Keep tests fast and isolated
- Use meaningful assertion messages
- Regular test maintenance and refactoring

## 🎉 Testing Benefits

1. **Code Quality**: Catch bugs early in development
2. **Refactoring Safety**: Make changes with confidence
3. **Documentation**: Tests serve as living documentation
4. **Team Collaboration**: Shared understanding of requirements
5. **CI/CD Ready**: Automated quality checks
6. **Customer Satisfaction**: More stable and reliable features

The automated testing suite ensures the SARANA application remains stable, reliable, and maintainable throughout its development lifecycle.