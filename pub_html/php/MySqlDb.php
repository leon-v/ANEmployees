<?php
include_once "Functions.php";

class MySqlDb
{
    private $connection;

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

    public function prepareStatement($sql)
    {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            die("Error in statement preparation: " . $this->connection->error);
        }

        return $stmt;
    }

    public function bindParams($stmt, $params = array())
    {
        if (!empty($params)) {
            $types = '';
            $bindParams = array();

            foreach ($params as $param) {
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

            array_unshift($bindParams, $types);
            call_user_func_array(array($stmt, 'bind_param'), $this->refValues($bindParams));
        }
    }

    public function executeStatement($stmt)
    {
        $stmt->execute();
        if ($stmt->errno) {
            die("Error in statement execution: " . $stmt->error);
        }
    }

    public function closeStatement($stmt)
    {
        if ($stmt) {
            $stmt->close();
        }
    }

    public function close()
    {
        $this->connection->close();
    }

    private function refValues($arr)
    {
        $refs = array();
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }
}
