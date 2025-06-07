<?php


namespace App\Tests;

use App\Database\Database;
use App\Database\Sqlite;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class DatabaseTest extends FrameworkTestCase
{
    private Database $db;

    protected function setUp(): void
    {
        $this->db = new Database(new Sqlite());
    }

    public function testConnection()
    {
        $stmt = $this->db->query("SELECT 1");
        $this->assertInstanceOf(\PDOStatement::class, $stmt);
        $result = $stmt->fetchColumn();
        $this->assertEquals(1, $result);
    }

    public function testQueryWithParams()
    {
        $stmt = $this->db->query("SELECT :value AS value", ['value' => 42]);
        $this->assertInstanceOf(\PDOStatement::class, $stmt);
        $result = $stmt->fetchColumn();
        $this->assertEquals(42, $result);
    }

    public function testQueryWithInvalidSql()
    {
        $this->expectException(\PDOException::class);
        $this->db->query("SELECT * FROM non_existing_table");
        $this->fail("Expected exception not thrown");
    }

    public function testQueryWithEmptySql()
    {
        $this->expectException(\Exception::class);
        $this->db->query("");
    }

    public function testQueryWithNoParams()
    {
        $stmt = $this->db->query("SELECT 1");
        $this->assertInstanceOf(\PDOStatement::class, $stmt);
        $result = $stmt->fetchColumn();
        $this->assertEquals(1, $result);
    }

    public function testQueryWithMultipleParams()
    {
        $stmt = $this->db->query("SELECT :value1 + :value2 AS sum", ['value1' => 10, 'value2' => 20]);
        $this->assertInstanceOf(\PDOStatement::class, $stmt);
        $result = $stmt->fetchColumn();
        $this->assertEquals(30, $result);
    }
}
