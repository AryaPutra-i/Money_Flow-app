<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\budget;
use App\Models\category;
use App\Models\debt;
use App\Models\goal;

/**
 * @group unit_tests
 */
class FillableAttributesTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_fillable_atributes_budget(): void
    {
        $model = new budget();
        $atributeFillable = ['workspace_id', 'category_id', 'limit_amount', 'month_year'];
        $this->assertEquals($atributeFillable, $model->getFillable());
    }
    public function test_fillable_atributes_category(): void
    {
        $model = new category();
        $fillable = ['workspace_id', 'name_category', 'type_category'];
        $this->assertEquals($fillable, $model->getFillable());
    }
    public function test_fillable_atributes_debt(): void
    {
        $model = new debt();
        $fillable = ['workspace_id', 'type', 'person_name', 'amount', 'status'];
        $this->assertEquals($fillable, $model->getFillable());
    }
    public function test_fillable_atributes_goal(): void
    {
        $model = new goal();
        $fillableAtribute = ['workspace_id', 'Deskripsi', 'target_amount', 'current_amount'];
        $this->assertEquals($fillableAtribute, $model->getFillable());
    }
}
