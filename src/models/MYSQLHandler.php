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
        // sql select fileds/* from items LIMIt start ,RECORDS_PER_PAGE
        // concat the array of fildes by "," using implode
        //transfer the query to number array by mysqli_fetch_all
        $table  = "items";
        $select = empty($fields) ? "*" : implode(",", $fields);
        $query  = "SELECT $select FROM $table LIMIT $start," . $this->config['records_per_page'];
        $result = mysqli_query($this->connection, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function get_record_by_id($id, $primary_key = 'id')
    {
        $table  = "items";
        $query  = "SELECT * FROM $table WHERE $primary_key = $id";
        $result = mysqli_query($this->connection, $query);
        return mysqli_fetch_assoc($result);
    }
}
