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

    public static function get_publications_cond(string $cond = null)
    {
        $qry = "SELECT * FROM `publications`" . ($cond ? $cond : "");
        $con = DB::create_con();
        $sql = $con->query($qry);
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
        if ($sql) {
            if ($sql->num_rows > 0) {
                return $sql->fetch_all(MYSQLI_ASSOC);
            } else {
                return null;
            }
        }
    }

    public static function get_publication_by_author(string $author)
    {
        $con = DB::create_con();
        $user = User::read("name LIKE '%" . $author . "%'");
        if ($user) {
            $user = $user[0];
            $sql = $con->query("SELECT publication_id FROM `publications_list` WHERE author_id = '" . $user['id'] . "'");
            if ($sql && $sql->num_rows > 0) {
                $ids = $sql->fetch_all(MYSQLI_ASSOC);
                $req = [];
                foreach ($ids as $id) {
                    array_push($req, Publication::get_publication_by_id($id['publication_id']));
                }
                return $req;
            } else {
                return null;
            }
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

    public static function get_publications_list(string $id, string $what = "*", $where = "publication_id")
    {
        $con = DB::create_con();
        $req = $con->query("SELECT " . $what . " FROM `publications_list` WHERE " . $where . " = '" . $id . "'");
        if ($req->num_rows > 0) {
            return $req->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public static function save_publication(string $title, string $authors, string $pub_date, string $shares, int $points, string $magconf, string $magconfval, string $url)
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


        $magazine = $magconf === "magazine" ? $magconfval : "";
        $conference = $magconf === "conference" ? $magconfval : "";

        $date = date('Y-m-d', strtotime($pub_date));
        $con = DB::create_con();
        $sql = $con->prepare("INSERT INTO `publications` (`title`, `authors`, `publication_date`, `shares`, `points`, `magazine`, `conference`, `url`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("ssssssss", $title, $authors, $date, $shares, $points, $magazine, $conference, $url);
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
            if ($data['authors']) {
                $authors = json_decode($data['authors']);
                Publication::delete_publications_list_id($id);
                if(is_array($authors) && count($authors) > 1) {
                    foreach ($authors as $auth) {
                        $usr = User::read("name = '" . $auth . "'");
                        if ($usr) {
                            Publication::add_author($id, [$usr[0]['id']]);
                        }
                    }
                }else{
                    $usr = User::read("name = '" . $authors[0] . "'");
                    if ($usr) {
                        Publication::add_author($id, [$usr[0]['id']]);
                    }
                }
            }

            return DB::update("publications", $data, $sql);
        }
        return false;
    }

    public static function delete_publication(string $id)
    {
        Publication::delete_publications_list_id($id);
        return DB::delete("publications", "id = '" . $id . "'");
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

    public static function remove_author(string $author_id)
    {
        $pub = Publication::get_publications_list($author_id, "`author_id`, `publication_id`", "author_id");
        $user = User::read("id = '" . $author_id . "'");
        if ($user) {
            if ($pub) {
                $user = $user[0];
                foreach ($pub as $p) {
                    $public = Publication::get_publication_by_id($p['publication_id']);
                    foreach ($public as $pc) {
                        $authors = $pc['authors'];
                        $authors = json_decode($authors);
                        $authors = array_filter($authors, function ($auth) use ($user) {
                            return $auth !== $user['name'];
                        });
                        $authors = array_values($authors);
                        $authors = json_encode($authors);

                        $shares = $pc['shares'];
                        $shares = json_decode($shares);
                        unset($shares->{$user['name']});
                        $shares = json_encode($shares);

                        Publication::update_publication($pc['id'], [
                            "shares" => $shares,
                            "authors" => $authors
                        ]);
                    }
                }
            }
        }
    }

    public static function replace_author(string $author_id, string $new_name, string $old_name)
    {
        $pub = Publication::get_publications_list($author_id, "`author_id`, `publication_id`", "author_id");
        $user = User::read("id = '" . $author_id . "'");
        if ($user) {
            if ($pub) {
                $user = $user[0];
                foreach ($pub as $p) {
                    $public = Publication::get_publication_by_id($p['publication_id']);
                    foreach ($public as $pc) {
                        $authors = $pc['authors'];
                        $authors = json_decode($authors);
                        if(is_array($authors) && count($authors) > 1) {
                            $authors = array_map($authors, function($auth) use ($user) {
                                if($auth === $user['name']) {
                                    return $new_name;
                                }else{
                                    return $auth;
                                }
                            });
                            $authors = array_values($authors);
                            $authors = json_encode($authors);
                        }else{
                            $authors = $new_name;
                            $authors = json_encode([$authors]);
                        }
                        

                        $shares = $pc['shares'];
                        $shares = json_decode($shares);
                        $shares->{$new_name} = $shares->{$old_name};
                        unset($shares->{$old_name});
                        $shares = json_encode($shares);

                        Publication::update_publication($pc['id'], [
                            "shares" => $shares,
                            "authors" => $authors
                        ]);
                    }
                }
            }
        }
    }

    public static function delete_publications_list_id($id)
    {
        $req = DB::delete('publications_list', "publication_id = '" . $id . "'");
        return !!$req;
    }
}
