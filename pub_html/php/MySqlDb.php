<?php
/**
 * MySQL Database Utility Class
 *
 * This PHP file contains the MySqlDb class, which provides utility functions
 * for interacting with a MySQL database using MySQLi.
 *
 * @category Database
 * @author   Leon Valkenborg
 */

/**
 * A class for handling MySQL database operations using MySQLi.
 */
class MySqlDb
{
    /**
     * The MySQLi database connection.
     *
     * This property holds the MySQLi database connection instance used for
     * executing SQL queries and interacting with the database.
     *
     * @var MySQLi
     */
    private MySQLi $connection;

    /**
     * Constructor to initialize the database connection.
     */
    public function __construct()
    {
        // Connect to the MySQL database using environment variables
        $this->connect("mysql", $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DATABASE']);
    }

    /**
     * Private method to establish a database connection.
     *
     * @param string $host The database host.
     * @param string $user The database username.
     * @param string $pass The database password.
     * @param string $name The database name.
     */
    private function connect($host, $user, $pass, $name)
    {
        try {
            $this->connection = new MySQLi($host, $user, $pass, $name);
        } catch (Exception $exception) {
            die("Connection failed: " . $exception->getMessage());
        }
    }

    /**
     * Prepare a SQL statement for execution.
     *
     * @param string $sql The SQL query to prepare.
     *
     * @return mysqli_stmt The prepared statement.
     *
     * @throws Exception If there is an error in statement preparation.
     */
    public function prepareStatement(string $sql): mysqli_stmt
    {
        $statement = $this->connection->prepare($sql);

        if (!$statement) {
            throw new Exception("Error in statement preparation: " . $this->connection->error);
        }

        return $statement;
    }

    /**
     * Bind parameters to a prepared statement.
     *
     * @param mysqli_stmt $statement The prepared statement to bind parameters to.
     * @param mixed ...$params The parameters to bind.
     */
    public function bindParams(mysqli_stmt $statement, ...$params): void
    {
        if (empty($params)) {
            return;
        }

        $types = '';
        $bindParams = array();

        foreach ($params as $param) {
            if (is_array($param)) {
                foreach ($param as $arrayParam) {
                    if (is_int($arrayParam)) {
                        $types .= 'i'; // integer
                    } elseif (is_double($arrayParam)) {
                        $types .= 'd'; // double
                    } elseif (is_string($arrayParam)) {
                        $types .= 's'; // string
                    } else {
                        $types .= 'b'; // blob
                    }
                    $bindParams[] = $arrayParam;
                }
            } else {
                if (is_int($param)) {
                    $types .= 'i'; // integer
                } elseif (is_double($param)) {
                    $types .= 'd'; // double
                } elseif (is_string($param)) {
                    $types .= 's'; // string
                } else {
                    $types .= 'b'; // blob
                }
                $bindParams[] = $param;
            }
        }

        array_unshift($bindParams, $types);
        call_user_func_array(array($statement, 'bind_param'), $this->refValues($bindParams));
    }

    /**
    * Execute a prepared statement.
    *
    * @param mysqli_stmt $statement The prepared statement to execute.
    *
    * @throws Exception If there is an error in statement execution.
    */
    public function executeStatement(mysqli_stmt $statement): void
    {
        $statement->execute();

        if ($statement->errno) {
            throw new Exception("Error in statement execution: " . $statement->error);
        }
    }

    /**
     * Get the result of a prepared statement.
     *
     * @param mysqli_stmt $statement The prepared statement.
     *
     * @return mysqli_result The result set obtained from the statement.
     *
     * @throws Exception If there is an error in getting the result.
     */
    public function getResult(mysqli_stmt $statement)
    {

        $result = $statement->get_result();

        if (!$result) {
            throw new Exception("Error in getting result");
        }

        return $result;
    }

    /**
     * Close a prepared statement.
     *
     * @param mysqli_stmt $statement The prepared statement to close.
     */
    public function closeStatement(mysqli_stmt $statement): void
    {
        if ($statement) {
            $statement->close();
        }
    }

    /**
     * Close the database connection.
     */
    public function close()
    {
        $this->connection->close();
    }

    /**
     * Get references to the values of an array.
     *
     * This method takes an array and returns an array of references to its values.
     *
     * @param array $arr The input array from which references will be obtained.
     *
     * @return array An array containing references to the values of the input array.
     */
    private function refValues(array $arr)
    {
        $refs = array();
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }

    /**
     * Fetch rows from a result set as an associative array.
     *
     * @param mysqli_result $result The result set to fetch data from.
     * @param string|null $key (Optional) A key to use for indexing the result array.
     *
     * @return array An associative array of fetched rows.
     */
    public function fetchAssoc(mysqli_result $result, ?string $key = null): array
    {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            if ($key) {
                $keyVal = $row[$key] ?? null;
                $rows[$keyVal] = (object) $row;
            } else {
                $rows[] = (object) $row;
            }

        }

        return $rows;
    }

    /**
    * Utility method to create an IN clause for SQL queries.
    *
    * @param array $values An array of values to be used in the IN clause.
    *
    * @return string The IN clause as a string.
    */
    public function in(array $values): string
    {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        return "IN (" . $placeholders . ")";
    }
}
