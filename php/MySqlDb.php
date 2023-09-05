<?php

include_once "Functions.php";

class MySqlDb
{
    private MySQLi $connection;

    public function __construct()
    {
        $this->connect("mysql", $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DATABASE']);
    }

    private function connect($host, $user, $pass, $name)
    {
        try {
            $this->connection = new MySQLi($host, $user, $pass, $name);
        } catch (Exception $exception) {
            die("Connection failed: " . $exception->getMessage());
        }
    }

    public function prepareStatement(string $sql): mysqli_stmt
    {
        $statement = $this->connection->prepare($sql);

        if (!$statement) {
            throw new Exception("Error in statement preparation: " . $this->connection->error);
        }

        return $statement;
    }

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

    public function executeStatement(mysqli_stmt $statement): void
    {
        $statement->execute();

        if ($statement->errno) {
            throw new Exception("Error in statement execution: " . $statement->error);
        }
    }

    public function getResult(mysqli_stmt $statement) {

        $result = $statement->get_result();

        if (!$result) {
            throw new Exception("Error in getting result");
        }

        return $result;
    }

    public function closeStatement(mysqli_stmt $statement): void
    {
        if ($statement) {
            $statement->close();
        }
    }

    public function close()
    {
        $this->connection->close();
    }

    private function refValues(array $arr)
    {
        $refs = array();
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }

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

    public function in(array $values): string
    {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        return "IN (" . $placeholders . ")";
    }
}
