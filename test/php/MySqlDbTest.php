<?php

use PHPUnit\Framework\TestCase;

include "php/MySqlDb.php";

class MySqlDbTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        // Initialize a test database connection
        $this->db = new MySqlDb();
    }

    protected function tearDown(): void
    {
        $sql = "DELETE FROM Employee WHERE name = ?";
        $statement = $this->db->prepareStatement($sql);
        $name = "PHP_UNIT_TEST";
        $this->db->bindParams($statement, $name);

        $statement->execute();

        $this->db->close();
    }

    public function testPrepareStatement()
    {
        $sql = "SELECT * FROM Employee WHERE employeeId = ?";
        $statement = $this->db->prepareStatement($sql);
        $this->assertInstanceOf(mysqli_stmt::class, $statement);
    }

    public function testBindParams()
    {
        $sql = "INSERT INTO Employee (name, email) VALUES (?, ?)";
        $statement = $this->db->prepareStatement($sql);

        $name = "PHP_UNIT_TEST";
        $email = "php_init_test@example.com";

        $this->db->bindParams($statement, $name, $email);

        $this->assertTrue($statement->execute());

        // You can add assertions here to check the database state after insertion
    }

    public function testIn()
    {
        $values = [1, 2, 3];
        $inClause = $this->db->in($values);

        $expectedClause = "IN (?,?,?)";
        $this->assertEquals($expectedClause, $inClause);
    }

    // Add more test methods for other functions in the class as needed
}
