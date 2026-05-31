<?php

namespace Tests\Unit;

use App\Models\user_account;
use App\Models\workspace;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

/**
 * @group unit_tests
 */
class user_accountTest extends TestCase
{
    /**
     * TC-01
     * Memastikan relasi workspaces() bertipe HasMany
     */
    public function test_defines_workspaces_relation(): void
    {
        $user_account = new user_account();

        $this->assertInstanceOf(
            HasMany::class,
            $user_account->workspaces()
        );
    }

    /**
     * TC-02
     * Memastikan relasi mengarah ke model Workspace
     */
    public function test_workspaces_relation_targets_workspace_model(): void
    {
        $user_account = new user_account();

        $relation = $user_account->workspaces();

        $this->assertEquals(
            workspace::class,
            get_class($relation->getRelated())
        );
    }

    /**
     * TC-03
     * Memastikan foreign key yang digunakan benar
     */
    public function test_workspaces_relation_uses_correct_foreign_key(): void
    {
        $user_account = new user_account();

        $relation = $user_account->workspaces();

        $this->assertEquals(
            'user_account_id',
            $relation->getForeignKeyName()
        );
    }

    /**
     * TC-04
     * Memastikan local key yang digunakan adalah id
     */
    public function test_workspaces_relation_uses_id_as_local_key(): void
    {
        $user_account = new user_account();

        $relation = $user_account->workspaces();

        $this->assertEquals(
            'id',
            $relation->getLocalKeyName()
        );
    }
}