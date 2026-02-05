<?php

namespace Tests\Unit;

use App\Models\Good;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoodModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_good_can_be_created()
    {
        $location = Location::factory()->create();

        $good = Good::create([
            'name' => 'Test Good',
            'code' => 'BRG-TEST-001',
            'location_id' => $location->id,
            'status' => 'AKTIF',
        ]);

        $this->assertDatabaseHas('goods', [
            'code' => 'BRG-TEST-001',
            'name' => 'Test Good',
            'status' => 'AKTIF',
        ]);

        $this->assertEquals('Test Good', $good->name);
        $this->assertEquals('BRG-TEST-001', $good->code);
        $this->assertEquals('AKTIF', $good->status);
    }

    public function test_good_belongs_to_location()
    {
        $location = Location::factory()->create(['name' => 'Test Location']);
        $good = Good::factory()->create(['location_id' => $location->id]);

        $this->assertInstanceOf(Location::class, $good->location);
        $this->assertEquals($location->id, $good->location->id);
        $this->assertEquals('Test Location', $good->location->name);
    }

    public function test_good_can_have_problem_items()
    {
        $location = Location::factory()->create();
        $good = Good::factory()->create(['location_id' => $location->id]);

        $guruRole = \App\Models\Role::where('name', 'guru')->first();
        $user = \App\Models\User::factory()->create(['role_id' => $guruRole->id]);

        $problem = \App\Models\Problem::create([
            'user_id' => $user->id,
            'issue' => 'Test problem',
            'status' => 0,
            'code' => 'PRB-TEST-001',
            'date' => now(),
        ]);

        $problem->items()->create([
            'good_id' => $good->id,
            'quantity' => 2,
            'price' => 50000,
        ]);

        $this->assertCount(1, $good->problemItems);
        $this->assertEquals(2, $good->problemItems->first()->quantity);
    }

    public function test_good_status_values()
    {
        $location = Location::factory()->create();

        $activeGood = Good::create([
            'name' => 'Active Good',
            'code' => 'BRG-TEST-001',
            'location_id' => $location->id,
            'status' => 'AKTIF',
        ]);

        $inactiveGood = Good::create([
            'name' => 'Inactive Good',
            'code' => 'BRG-TEST-002',
            'location_id' => $location->id,
            'status' => 'TIDAK AKTIF',
        ]);

        $this->assertEquals('AKTIF', $activeGood->status);
        $this->assertEquals('TIDAK AKTIF', $inactiveGood->status);
    }

    public function test_good_code_is_unique()
    {
        $location = Location::factory()->create();

        Good::create([
            'name' => 'Good 1',
            'code' => 'BRG-UNIQUE-001',
            'location_id' => $location->id,
            'status' => 'AKTIF',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Good::create([
            'name' => 'Good 2',
            'code' => 'BRG-UNIQUE-001', // Duplicate code
            'location_id' => $location->id,
            'status' => 'AKTIF',
        ]);
    }

    public function test_good_fillable_attributes()
    {
        $location = Location::factory()->create();
        $goodData = [
            'name' => 'Test Good',
            'code' => 'BRG-TEST-001',
            'location_id' => $location->id,
            'status' => 'AKTIF',
            'description' => 'Test description',
        ];

        $good = Good::create($goodData);

        foreach ($goodData as $key => $value) {
            $this->assertEquals($value, $good->$key);
        }
    }

    public function test_good_can_be_deactivated()
    {
        $location = Location::factory()->create();
        $good = Good::create([
            'name' => 'Active Good',
            'code' => 'BRG-TEST-001',
            'location_id' => $location->id,
            'status' => 'AKTIF',
        ]);

        $good->update(['status' => 'TIDAK AKTIF']);

        $this->assertEquals('TIDAK AKTIF', $good->status);
        $this->assertDatabaseHas('goods', [
            'code' => 'BRG-TEST-001',
            'status' => 'TIDAK AKTIF',
        ]);
    }

    public function test_good_scope_active()
    {
        $location = Location::factory()->create();

        Good::factory()->count(3)->create([
            'location_id' => $location->id,
            'status' => 'AKTIF'
        ]);

        Good::factory()->count(2)->create([
            'location_id' => $location->id,
            'status' => 'TIDAK AKTIF'
        ]);

        $activeGoods = Good::where('status', 'AKTIF')->get();
        $inactiveGoods = Good::where('status', 'TIDAK AKTIF')->get();

        $this->assertCount(3, $activeGoods);
        $this->assertCount(2, $inactiveGoods);
    }

    public function test_good_scope_by_location()
    {
        $location1 = Location::factory()->create(['name' => 'Location 1']);
        $location2 = Location::factory()->create(['name' => 'Location 2']);

        Good::factory()->count(3)->create(['location_id' => $location1->id]);
        Good::factory()->count(2)->create(['location_id' => $location2->id]);

        $location1Goods = Good::where('location_id', $location1->id)->get();
        $location2Goods = Good::where('location_id', $location2->id)->get();

        $this->assertCount(3, $location1Goods);
        $this->assertCount(2, $location2Goods);
    }

    public function test_good_soft_delete()
    {
        $location = Location::factory()->create();
        $good = Good::factory()->create(['location_id' => $location->id]);
        $goodId = $good->id;

        $good->delete();

        $this->assertSoftDeleted('goods', ['id' => $goodId]);
    }

    public function test_good_search_by_name()
    {
        $location = Location::factory()->create();

        Good::factory()->create([
            'name' => 'Laboratory Computer',
            'location_id' => $location->id,
        ]);

        Good::factory()->create([
            'name' => 'Office Chair',
            'location_id' => $location->id,
        ]);

        $searchResults = Good::where('name', 'like', '%Computer%')->get();

        $this->assertCount(1, $searchResults);
        $this->assertEquals('Laboratory Computer', $searchResults->first()->name);
    }

    public function test_good_can_be_paginated()
    {
        $location = Location::factory()->create();
        Good::factory()->count(25)->create(['location_id' => $location->id]);

        $page1 = Good::paginate(20);
        $page2 = Good::paginate(20, ['*'], 'page', 2);

        $this->assertCount(20, $page1);
        $this->assertCount(5, $page2);
        $this->assertEquals(25, $page1->total());
    }
}