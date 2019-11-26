<?php

function trim_sql_elements($arr, $sep)
{
    return array_map(function ($el) use ($sep) {
        return $sep . $el . $sep;
    }, $arr);
};

// function joins(string $sep)
// {
//     return function (array $data) use ($sep) {
//         return array_reduce($data, function ($prev, $curr) use ($sep) {
//             return $prev === "" ? ($prev . $curr) : ($prev . " " . $sep . " " . $curr);
//         }, "");
//     };
// }

function joins(string $sep = "=", string $impl = ", ")
{
    return function (array $data) use ($sep, $impl) {
        return implode($impl, array_map(
            function ($val, $key) use ($sep) {
                return sprintf("%s" . $sep . "'%s'", $key, $val);
            },
            $data,
            array_keys($data)
        ));
    };
}

function check(string $what)
{
    return isset($_REQUEST[$what]) && !empty($_REQUEST[$what]);
}

function edit_shares($data)
{
    $shares = explode(", ", $data);
    $shar = new stdClass();
    $counter = 0;
    foreach ($shares as $sh) {
        $nwsh = explode("-", $sh);
        $let = $nwsh[0];
        $shar->$let = $nwsh[1];
        $counter += $nwsh[1];
    }

    if ($counter > 100) {
        return null;
    } else {
        return $shar;
    }
}

function is_admin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === "admin";
}

function is_logged()
{
    return isset($_SESSION['name']);
}

function is_author($authors)
{
    $authors = is_array($authors) ? json_encode($authors) : $authors;
    $arr = explode(", ", $authors);
    return isset($_SESSION['name']) && $_SESSION['name'] === json_decode($arr[0])[0];
}

function get_search_cond_id(array $cond) {
    $ret = [];
    foreach ($cond as $key => $val) {
        switch ($key) {
            case "pubid": {
                if ($val !== "") {
                    if(is_array($val)) {
                        foreach($val as $id) {
                            array_push($ret, "id = '".$id."'");
                        }
                    }
                }
                break;
            }
            default:
                break;
        }
    }
    $ret = count($ret) > 1 ? join(" OR ", $ret) : join("", $ret);
    $ret = "(" . $ret . ")";
    return $ret;
}

function get_search_cond(array $cond)
{
    $ret = [];
    foreach ($cond as $key => $val) {
        switch ($key) {
            case "tytul": {
                    if ($val !== "") {
                        array_push($ret, "title LIKE '%" . $cond['tytul'] . "%'");
                    }
                    break;
                }
            case "author": {
                    if ($val !== "") {
                        array_push($ret, "authors LIKE '%" . $cond['author'] . "%'");
                    }
                    break;
                }
            case "dataod": {
                    if ($val !== "") {
                        array_push($ret, "publication_date >= '" . $cond['dataod'] . "'");
                    }
                    break;
                }
            case "datado": {
                    if ($val !== "") {
                        array_push($ret, "publication_date <= '" . $cond['datado'] . "'");
                    }
                    break;
                }
            default: {
                    // array_push($ret, null);
                    break;
                }
        }
    }
    return count($ret) > 1 ? join(" AND ", $ret) : join("", $ret);
}

function get_search_cond_sort(array $cond)
{
    $ret = "";
    foreach ($cond as $key => $val) {
        switch ($key) {
            case "sortujwedlug": {
                    if ($val !== "") {
                        $ret = $val;
                    }
                    break;
                }
            case "sort": {
                    if ($val !== "") {
                        $ret .= " " . strtoupper($val);
                    }
                    break;
                }
        }
    }
    return $ret;
}


function get_search_cond_box(array $cond)
{
    $ret = [];
    foreach ($cond as $key => $val) {
        switch ($key) {
            case "nazwabox": {
                    array_push($ret, "title");
                    break;
                }
            case "authorbox": {
                    array_push($ret, "authors");
                    break;
                }
            case "sharesbox": {
                    array_push($ret, "shares");
                    break;
                }
            case "databox": {
                    array_push($ret, "publication_date");
                    break;
                }
            case "punktybox": {
                    array_push($ret, "points");
                    break;
                }
            case "magazinebox": {
                    array_push($ret, "magazine");
                    break;
                }
            case "conferencebox": {
                    array_push($ret, "conference");
                    break;
                }
            case "urlbox": {
                    array_push($ret, "url");
                    break;
                }
            default: {
                    break;
                }
        }
    }
    return count($ret) > 1 ? join(", ", $ret) : join(", ", $ret);
}
