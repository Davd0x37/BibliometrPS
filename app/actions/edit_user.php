<h1>Bibliometr - Edytuj użytkownika</h1>

<?php
require "render_view.php";

if (
    //check('name')
    isset($_REQUEST['name']) && !empty($_REQUEST['name']) &&
    isset($_SESSION['name']) && !empty($_SESSION['name']) &&
    isset($_SESSION['role']) && !empty($_SESSION['role']) &&
    $_SESSION['role'] === "admin"
) {
    $user = null;
    if (isset($_GET['name']) && !empty($_GET['name'])) {
        $user = User::read("name = '" . $_GET['name'] . "'");
    } elseif (isset($_POST['name']) && !empty($_POST['name'])) {
        $user = User::read("name = '" . $_POST['name'] . "'");
    } else {
        echo "Podaj parametr name w URL!";
        exit;
    }

    if ($user) {
        $user = $user[0];

        render_edit_user("admin.php?edit_user", "post", [
            "name" => $user['name'],
            "mail" => $user['email'],
            "afiliacja" => $user['afiliacja'],
            "role" => $user['role']
        ]);

        if (
            isset($_POST['mail']) &&
            isset($_POST['afiliacja']) &&
            isset($_POST['password']) &&
            isset($_POST['role']) && isset($_POST['name'])
        ) {
            $name = !empty($_POST['name']) ? $_POST['name'] : $user['name'];
            $mail = !empty($_POST['mail']) ? $_POST['mail'] : $user['email'];
            $afiliacja = !empty($_POST['afiliacja']) ? $_POST['afiliacja'] : $user['afiliacja'];
            $password = !empty($_POST['password']) ? md5($_POST['password']) : $user['password'];
            $role = !empty($_POST['role']) ? $_POST['role'] : $user['role'];
            $ed = User::update($user['id'], [
                "name" => $name,
                "email" => $mail,
                "afiliacja" => $afiliacja,
                "password" => $password,
                "role" => $role
            ]);

            if ($ed) {
                Publication::replace_author($user['id'], $name, $user['name']);
                echo "Edytowano użytkownika!";
            } else {
                echo "Podano błędne dane!";
            }
        }
    } else {
        echo "Użytkownik nie istnieje!";
        exit;
    }
}elseif(
    isset($_SESSION['role']) &&
    isset($_SESSION['name']) &&
    $_SESSION['role'] === "user"
    ) {
    $user = User::read("name = '".$_SESSION['name']."'");
    if($user) {
        $user = $user[0];
    
        render_edit_user("profile.php?edit_profile", "post", [
            "name" => $user['name'],
            "mail" => $user['email'],
            "afiliacja" => $user['afiliacja']
        ]);
    
        if (
            isset($_POST['mail']) &&
            isset($_POST['name']) &&
            isset($_POST['afiliacja'])
        ) {
            $mail = !empty($_POST['mail']) ? $_POST['mail'] : $user['email'];
            $name = !empty($_POST['name']) ? $_POST['name'] : $user['name'];
            $afiliacja = !empty($_POST['afiliacja']) ? $_POST['afiliacja'] : $user['afiliacja'];
            $ed = User::update($user['id'], [
                "name" => $name,
                "email" => $mail,
                "afiliacja" => $afiliacja
            ]);
            
            if ($ed) {
                Publication::replace_author($user['id'], $name, $user['name']);
                $_SESSION['name'] = $name;
                echo "Edytowano profil!";
            } else {
                echo "Podano błędne dane!";
            }
        }
    }
} else {
    render_edit_user("admin.php?edit_user", "post");
}
?>