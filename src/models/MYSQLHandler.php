<?php
namespace Johnl\GlassesShop\models;

class MYSQLHandler implements DbHandler
{
    private $connection;
    private $config;
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    public function connect()
    {
        $this->connection = mysqli_connect(
            $this->config['db']['host'],
            $this->config['db']['user'],
            $this->config['db']['pass'],
            $this->config['db']['name']
        );
        if (! $this->connection) {
            return false;
        }
        return true;
    }
    public function disconnect()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }
    public function get_data($fields = [], $start = 0)
    {
        $start = max(0, (int) $start);

        $table  = "items";
        $select = empty($fields) ? "*" : implode(",", $fields);
        $limit  = $this->config['records_per_page'];
        $query  = "SELECT $select FROM $table LIMIT $start, $limit";
        $result = mysqli_query($this->connection, $query);

        if (! $result) {
            throw new \Exception("Database query failed: " . mysqli_error($this->connection));
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function get_record_by_id($id, $primary_key = 'id')
    {
        $table  = "items";
        $query  = "SELECT * FROM $table WHERE $primary_key = $id";
        $result = mysqli_query($this->connection, $query);
        return mysqli_fetch_assoc($result);
    }
    public function get_total_count()
    {
        $query  = "SELECT COUNT(*) as total FROM items";
        $result = mysqli_query($this->connection, $query);

        if (! $result) {
            throw new \Exception("Database query failed: " . mysqli_error($this->connection));
        }

        $row = mysqli_fetch_assoc($result);
        return (int) $row['total'];
    }
}
