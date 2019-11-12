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

function edit_shares($data) {
    $shares = explode(",", $data);
    $shar = new stdClass();
    foreach ($shares as $sh) {
        $nwsh = explode("-", $sh);
        $let = $nwsh[0];
        $shar->$let = $nwsh[1];
    }
    return $shar;
}