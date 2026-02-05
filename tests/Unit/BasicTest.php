<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function test_true_is_true()
    {
        $this->assertTrue(true);
    }

    public function test_basic_arithmetic()
    {
        $this->assertEquals(4, 2 + 2);
    }

    public function test_string_operations()
    {
        $string = "Hello World";
        $this->assertEquals(11, strlen($string));
        $this->assertStringContainsString('World', $string);
    }

    public function test_array_operations()
    {
        $array = [1, 2, 3, 4, 5];
        $this->assertCount(5, $array);
        $this->assertEquals(3, $array[2]);
    }

    public function test_assertions()
    {
        $this->assertEquals(10, 10);
        $this->assertNotEquals(10, 20);
        $this->assertTrue(true);
        $this->assertFalse(false);
        $this->assertNull(null);
        $this->assertNotNull('value');
        $this->assertEmpty([]);
        $this->assertNotEmpty([1, 2, 3]);
    }
}