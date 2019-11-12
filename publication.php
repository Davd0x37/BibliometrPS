<?php

class Publication
{

    public static function get_publications()
    {
        $con = DB::create_con();
        $sql = $con->query("SELECT * FROM `publications`");
        if ($sql->num_rows > 0) {
            return $sql->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public static function get_publication_by_id(string $id)
    {
        $con = DB::create_con();
        $sql = $con->query("SELECT * FROM `publications` WHERE id = '" . $id . "'");
        if ($sql->num_rows > 0) {
            return $sql->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public static function get_publication_by_title(string $title = null)
    {
        $sql = "SELECT * FROM `publications`";
        if ($title) {
            $sql .= " WHERE title LIKE '%" . $title . "%'";
        }

        $con = DB::create_con();
        $sql = $con->query($sql);
        if ($sql->num_rows > 0) {
            return $sql->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public static function get_publications_list(string $id, string $what = "*")
    {
        $con = DB::create_con();
        $req = $con->query("SELECT " . $what . " FROM `publications_list` WHERE publication_id = '" . $id . "'");
        if ($req->num_rows > 0) {
            return $req->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public static function save_publication(string $title, string $authors, string $pub_date, string $shares, int $points, string $magazine, string $url)
    {
        // Authors and shares needs to be stringified to JSON
        // URL needs to be encodeURI in js
        // Authors - only names
        /**
         * Shares
         * Mark => 20
         * John => 30
         */
        // INSERT INTO publications (`title`, `authors`, `shares`, `points`, `magazine`, `url`)
        // VALUES ($title, JSON($authors), JSON($shares), $points, $magazine, $url)
        // VALUES (?, ?, ?, ?, ?, ?)
        // echo htmlspecialchars(json_encode($authors), ENT_QUOTES, 'UTF-8');
        // return true;

        $date = date('Y-m-d', strtotime($pub_date));
        $con = DB::create_con();
        $sql = $con->prepare("INSERT INTO `publications` (`title`, `authors`, `publication_date`, `shares`, `points`, `magazine`, `url`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("sssssss", $title, $authors, $date, $shares, $points, $magazine, $url);
        $sql->execute();

        if ($sql->affected_rows > 0) {

            $pub_id = mysqli_insert_id($con);

            $authors = json_decode($authors, true);
            if (!is_array($authors)) {
                $authors = [$authors];
            }

            $result = true;
            foreach ($authors as $author) {
                $authors_map = $con->query("SELECT id FROM `users` WHERE name = '" . $author . "'");
                if ($authors_map) {
                    if ($authors_map->num_rows > 0) {
                        $id = $authors_map->fetch_assoc();
                        $result = DB::insert("publications_list", [
                            "publication_id" => $pub_id,
                            "author_id" => $id['id']
                        ]);
                    }
                }
            }

            return $result;
        } else {
            return false;
        }
    }

    public static function update_publication(string $id, array $data, string $cond = null)
    {
        $pub = Publication::get_publication_by_id($id);
        if ($pub) {
            $sql = "id = '" . $id . "'" . ($cond ? " AND " . $cond : "");

            return DB::update("publications", $data, $sql);
        }
        return false;
    }

    public static function delete_publication(string $id)
    {
        return DB::delete("publications", "id = '". $id ."'");
    }

    /**
     * @param array $authors Array of other authors IDs
     * [1, 2, 3, 59, 20, 31]
     */
    public static function add_author(string $pub_id, array $authors)
    {
        $pub = Publication::get_publications_list($pub_id, "author_id");
        $users = User::read("id = '" . join("' OR id = '", $authors) . "'");

        if ($users) {
            if ($pub) {
                $pub = array_map(function ($el) {
                    return $el['author_id'];
                }, $pub);

                $authors = array_diff($authors, $pub);
            }

            if (count($authors) > 0) {
                $result = true;
                foreach ($authors as $add) {
                    $user = User::read("id = '" . $add . "'");
                    if ($user) {
                        $result = DB::insert("publications_list", [
                            "publication_id" => $pub_id,
                            "author_id" => $add
                        ]);
                    }
                }
                return $result;
            }
        } else {
            echo "Unable to find users";
            return false;
        }
    }
}
