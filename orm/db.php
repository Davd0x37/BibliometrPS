<?php

require __DIR__ . "../../config.php";

class DB
{
    /**
     * INSERT INTO $TABLE (...elements) VALUES (...values) WHERE ...conditions
     * @param string $table Table name
     * @param array $data Assoc array as ["column name" => "value"]
     * @param array $where [Default false] Array with conditions ["login != 'name'"]
     * @return bool True if inserted or false if not
     */
    public static function insert($table, $data, $where = false): bool
    {
        $sql = "INSERT INTO " . $table . " (";

        $elements = array_keys($data);
        $elements = trim_sql_elements($elements, "`");
        $elements = join(", ", $elements);

        $values = array_values($data);
        $values = trim_sql_elements($values, "\"");
        $values = join(", ", $values);

        $sql .= $elements . ") VALUES (" . $values . ")";

        if ($where) {
            $cnt = count($where);
            $i = 0;
            $sql .= " WHERE ";
            if ($cnt >= 2) {
                while ($i < $cnt) {
                    $sql .= $where[$i] . ($i < $cnt - 1 ? " AND " : "");
                    $i++;
                }
            } else {
                $sql .= $where[0];
            }
        }

        $con = DB::create_con();
        if (!$con->query($sql)) {
            echo "Error at: " . mysqli_error($con);
            return false;
        }
        return true;
    }

    public static function delete(string $table, string $cond)
    {
        // $sql = "DELETE FROM `" . $table . "` WHERE " . $cond;
        // $cnt = count($cond);
        // $i = 0;
        // if ($cnt >= 2) {
        //     while ($i < $cnt) {
        //         $sql .= $cond[$i] . ($i < $cnt - 1 ? " AND " : "");
        //         $i++;
        //     }
        // } else {
        //     $sql .= $cond[0];
        // }

        $con = DB::create_con();
        $req = $con->query("DELETE FROM `" . $table . "` WHERE " . $cond);
        return !!$req;
    }

    public static function update(string $table, array $data, string $cond) {
        $con = DB::create_con();
        $req = $con->query("UPDATE `". $table ."` SET " . joins()($data) . " WHERE " . $cond);
        return !!$req;
    }

    public static function create_con()
    {
        global $HOST;
        global $USER;
        global $PASSWORD;
        global $DB;
        return new mysqli($HOST, $USER, $PASSWORD, $DB);
    }
}
