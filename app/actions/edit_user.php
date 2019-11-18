<h1>Bibliometr - Edytuj użytkownika</h1>

<?php
require "render_view.php";

if (
    check('name')
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
            isset($_POST['role'])
        ) {
            $mail = !empty($_POST['mail']) ? $_POST['mail'] : $user['email'];
            $afiliacja = !empty($_POST['afiliacja']) ? $_POST['afiliacja'] : $user['afiliacja'];
            $password = !empty($_POST['password']) ? md5($_POST['password']) : $user['password'];
            $role = !empty($_POST['role']) ? $_POST['role'] : $user['role'];
            $ed = User::update($user['id'], [
                "email" => $mail,
                "afiliacja" => $afiliacja,
                "password" => $password,
                "role" => $role
            ]);

            if ($ed) {
                echo "Edytowano użytkownika!";
            } else {
                echo "Podano błędne dane!";
            }
        }
    } else {
        echo "Użytkownik nie istnieje!";
        exit;
    }
} else {
    render_edit_user("admin.php?edit_user", "post");
}
?>