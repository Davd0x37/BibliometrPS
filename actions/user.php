<?php

include_once __DIR__ . "/../orm/db.php";

class User
{

    public static function create(string $name, string $password, string $email, string $afiliacja): bool
    {
        return DB::insert("users", [
            "name" => $name,
            "password" => $password,
            "email" => $email,
            "afiliacja" => $afiliacja
        ]);
    }


    public static function read(string $cond = null)
    {
        $sql = "SELECT * from `users`";
        if ($cond) {
            $sql .= " WHERE " . $cond;
        }

        $db = DB::create_con();
        $res = $db->query($sql);
        if ($res) {
            if ($res->num_rows > 0) {
                return $res->fetch_all(MYSQLI_ASSOC);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function update(string $id, array $data, string $cond = null)
    {
        $user = User::read("id = '" . $id . "'");
        if ($user) {
            $sql = "id = '" . $id . "'" . ($cond ? " AND " . $cond : "");

            return DB::update("users", $data, $sql);
        }
        return false;
    }

    public static function delete(string $cond)
    {
        return DB::delete("users", $cond);
    }

    public static function login(string $email, string $pass)
    {
        $user = User::read("email = '" . $email . "' AND password ='" . $pass . "'");
        if ($user) {
            return $user;
        } else {
            null;
        }
    }

    public static function author_exists(string $name) {
        $req = User::read("name LIKE '%". $name ."%'");
        return !!$req;
    }
}
