# Running Tests pada MoneyFlow Application

## Quick Start

### Menjalankan Semua Tests
```bash
cd f:\FILE KULIAH\project_mini\MoneyFlow-app
php artisan test
```

### Menjalankan Tests dengan Format Testdox
```bash
php artisan test --testdox
```

Ini akan menampilkan list semua tests dengan hasil PASS/FAIL.

### Menjalankan Specific Test File
```bash
php artisan test tests/Unit/ModelRelationshipsTest.php
php artisan test tests/Feature/WorkspaceManagementTest.php
```

## Test Structure

### Directory Layout
```
tests/
├── Unit/                           # Unit tests untuk models & services
│   ├── ModelRelationshipsTest.php  # Test model relationships
│   ├── ModelAttributesTest.php     # Test fillable & casts
│   ├── TransactionObserverTest.php # Test observer logic
│   ├── FinancialHealthServiceTest.php
│   ├── TransactionTest.php         # Existing tests
│   ├── walletTest.php
│   └── ... (other existing tests)
│
├── Feature/                         # Integration tests
│   ├── WorkspaceManagementTest.php  # Workspace operations
│   ├── BudgetTrackingTest.php       # Budget tracking
│   ├── GoalAndDebtManagementTest.php # Goals & debts
│   ├── SplitBillAndSubscriptionTest.php
│   ├── WalletIntegrationTest.php
│   ├── TransactionIntegrationTest.php
│   └── FiturBudgetTest.php
│
└── TestCase.php                     # Base test class
```

## Test Commands

### Run Unit Tests Only
```bash
php artisan test tests/Unit
```

### Run Feature Tests Only
```bash
php artisan test tests/Feature
```

### Run Specific Test Class
```bash
php artisan test tests/Unit/ModelRelationshipsTest.php --testdox
```

### Run Specific Test Method
```bash
php artisan test tests/Unit/ModelRelationshipsTest.php --filter=test_user_account_has_many_workspaces
```

### Run with Coverage Report
```bash
php artisan test --coverage
php artisan test --coverage --min=80  # Require minimum 80% coverage
```

### Run with Verbose Output
```bash
php artisan test --verbose
```

### Run with Random Order
```bash
php artisan test --order=random
```

### Run Tests in Parallel (faster)
```bash
php artisan test --parallel
```

## Test Groups

Tests diorganisir menggunakan PHPUnit groups. Anda dapat menjalankan test per group:

```bash
# Run only unit tests with @group unit_tests
php artisan test --group=unit_tests

# Run only feature tests with @group feature_tests
php artisan test --group=feature_tests

# Run workspace tests
php artisan test --group=workspace_tests

# Run budget tests
php artisan test --group=budget_tests

# Run goal and debt tests
php artisan test --group=goal_debt_tests

# Run split bill and subscription tests
php artisan test --group=split_bill_subscription_tests
```

## Database Configuration untuk Tests

Tests menggunakan SQLite in-memory database (dikonfigurasi di `phpunit.xml`):

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

Ini memastikan:
- ✓ Tests berjalan cepat
- ✓ Database fresh setiap test run
- ✓ Tidak ada perubahan ke production database
- ✓ Tests terisolasi satu sama lain

## Factories yang Tersedia

Semua model memiliki factory untuk test data generation:

```php
// Unit test example
user_account::factory()->create();
workspace::factory()->for($userAccount)->create();
wallet::factory()->for($workspace)->create(['balance' => 1000000]);
transaction::factory()
    ->for($workspace)
    ->for($wallet)
    ->for($category)
    ->create(['amount' => 100000, 'type' => 'income']);

// Create multiple records
goal::factory()->count(5)->for($workspace)->create();
GoalSaving::factory()->count(10)->for($goal)->for($wallet)->create();
SavingInvestasi::factory()->count(3)->for($workspace)->for($wallet)->create();
subscriptionTransaction::factory()->count(4)->for($workspace)->for($wallet)->for($category)->create();
```

## Assertions yang Digunakan

Setiap test menggunakan Laravel Testing assertions:

```php
// Database assertions
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);
$this->assertDatabaseMissing('users', ['id' => 999]);
$this->assertEquals(10, User::count());

// Collection assertions
$this->assertCount(5, $workspace->wallets);
$this->assertTrue($wallet->transactions->contains($transaction));

// Relationship assertions
$this->assertInstanceOf(HasMany::class, $workspace->wallets());
$this->assertEquals(wallet::class, get_class($relation->getRelated()));

// Value assertions
$this->assertEquals(1000000, $wallet->balance);
$this->assertGreaterThan(500000, $totalExpenses);
$this->assertLessThan(5000000, $remainingBudget);
```

## Troubleshooting

### Tests Gagal karena Database Issue
```bash
# Refresh migrations
php artisan migrate:refresh

# Run seeds if needed
php artisan db:seed
```

### Tests Timeout
Increase timeout di `phpunit.xml`:
```xml
<testsuites>
    <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
</testsuites>
```

### Memory Issue
Run tests in smaller batches:
```bash
php artisan test tests/Unit --parallel --processes=2
```

### Clear Test Cache
```bash
php artisan test --no-coverage --no-cache
```

## Coverage Report

Untuk melihat code coverage:

```bash
# Generate coverage report
php artisan test --coverage

# Generate detailed HTML coverage report
php artisan test --coverage --coverage-html=coverage

# Set minimum coverage threshold
php artisan test --coverage --min=75
```

Coverage akan ditampilkan untuk:
- ✓ Models
- ✓ Services
- ✓ Observers
- ✓ Factories
- ✓ Migrations

## Best Practices

1. **Use RefreshDatabase**: Semua integration tests menggunakan `RefreshDatabase` trait
2. **Isolate Tests**: Setiap test independen dan tidak bergantung ke test lain
3. **Use Factories**: Buat data test dengan factory, jangan hardcode
4. **Clear Assertions**: Setiap test harus memiliki clear assertion
5. **One Concept Per Test**: Setiap test fokus pada satu concept
6. **Meaningful Names**: Nama test mendeskripsikan apa yang ditest

## Writing New Tests

Template untuk membuat test baru:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\YourModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourFeatureTest extends TestCase
{
    use RefreshDatabase;

    private YourModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = YourModel::factory()->create();
    }

    public function test_can_do_something(): void
    {
        // Arrange
        $data = ['field' => 'value'];

        // Act
        $result = $this->model->someMethod($data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('table_name', ['id' => $this->model->id]);
    }
}
```

## Expected Test Results

Setelah implementasi lengkap, Anda seharusnya melihat:

```
✓ 156+ tests passed
✓ 0 errors
✓ ~95%+ assertions passed
✓ ~30-40% code coverage (depending on project size)
✓ All model relationships tested
✓ All business logic tested
✓ All edge cases covered
```

## Next Steps

1. Run: `php artisan test --testdox`
2. Check failing tests dan perbaiki
3. Generate coverage report
4. Set CI/CD pipeline untuk run tests otomatis
5. Maintain tests ketika menambah feature baru
