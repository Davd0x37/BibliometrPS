<h1>Bibliometr - Usuń użytkownika</h1>
<form action="admin.php?delete_user" method="post" class="container">
    <div class="container__box fieldset__container">
        <!-- <p><label for="name">Imie:</label>
            <input type="text" name="name" id="name"></p> -->
        <p><label for="mail">Email:</label>
            <input type="email" name="mail" id="mail"></p>
        <!-- <p><label for="id">ID:</label>
            <input type="text" name="id" id="id"></p> -->
        <input type="submit" class="button_action" value="Edytuj">
    </div>
</form>
<?php
if (isset($_POST['mail']) && !empty($_POST['mail'])) {
    $user = User::read("email = '" . $_POST['mail'] . "'");
    if ($user) {
        $req = User::delete("email = '" . $_POST['mail'] . "'");
        if ($req) {
            echo "Pomyślnie usunięto użytkownika!";
        } else {
            echo "Nie można usunąć użytkownika";
        }
    } else {
        echo "Użytkownik nie istnieje!";
    }
}
// if (
//     (isset($_POST['name']) && !empty($_POST['name'])) || (isset($_POST['mail']) && !empty($_POST['mail'])) || (isset($_POST['id']) && !empty($_POST['id']))
// ) {
//     $cond = (isset($_POST['name']) && !empty($_POST['name'])) ? "name = '" . $_POST['name'] . "'" : "";
//     $cond = (isset($_POST['mail']) && !empty($_POST['mail'])) ? ($cond !== "" ? $cond . " OR " : $cond) . "email = '" . $_POST['mail'] . "'" : ($cond !== "" ? $cond . " OR " : $cond) . "";
//     $cond = (isset($_POST['id']) && !empty($_POST['id'])) ? ($cond !== "" ? $cond . " OR " : $cond) . "id = '" . $_POST['id'] . "'" : ($cond !== "" ? $cond . " OR " : $cond) . "";
//     echo $cond;
// $user = User::read();
// $user = $user[0];
// if ($user) {
//     $mail = !empty($_POST['mail']) ? $_POST['mail'] : $user['email'];
//     $afiliacja = !empty($_POST['afiliacja']) ? $_POST['afiliacja'] : $user['afiliacja'];
//     $password = !empty($_POST['password']) ? $_POST['password'] : $user['password'];
//     $role = !empty($_POST['role']) ? $_POST['role'] : $user['role'];
//     $ed = User::update($user['id'], [
//         "email" => $mail,
//         "afiliacja" => $afiliacja,
//         "password" => $password,
//         "role" => $role
//     ]);

//     if ($ed) {
//         echo "Edytowano użytkownika!";
//     } else {
//         echo "Podano błędne dane!";
//     }
// } else {
//     echo "Użytkownik nie istnieje!";
//     exit;
// }
// }
?>