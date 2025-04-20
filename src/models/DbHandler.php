<?php
namespace Johnl\GlassesShop\Models;

interface DbHandler
{
    public function connect();
    public function get_data($fields = [], $start = 0);
    public function disconnect();
    public function get_record_by_id($id, $primary_key);

}
