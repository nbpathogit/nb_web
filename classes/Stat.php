<?php

class Stat
{


    public static function getTotal($conn)
    {
        $sql="SELECT table_name, table_rows
        FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'zp12370_abc'";

        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
