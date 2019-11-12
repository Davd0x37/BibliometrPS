<h1>Bibliometr - Edytuj użytkownika</h1>
<form action="admin.php?edit_user" method="post" class="container">
    <div class="container__box fieldset__container">
        <p><label for="name">Imie:</label>
            <input type="text" name="name" id="name"></p>
        <p><label for="mail">Email:</label>
            <input type="email" name="mail" id="mail"></p>
        <p><label for="afiliacja">Afiliacja:</label>
            <input type="text" name="afiliacja" id="afiliacja"></p>
        <p><label for="password">Hasło</label>
            <input type="password" name="password" id="password"></p>
        <p><label for="role">Rola:</label>
            <input type="text" name="role" id="role"></p>
        <input type="submit" class="button_action" value="Edytuj">
    </div>
</form>
<?php
if (
    check('name') &&
    isset($_POST['mail']) &&
    isset($_POST['afiliacja']) &&
    isset($_POST['password']) &&
    isset($_POST['role'])
) {
    $user = User::read("name = '" . $_POST['name'] . "'");
    $user = $user[0];
    if ($user) {
        $mail = !empty($_POST['mail']) ? $_POST['mail'] : $user['email'];
        $afiliacja = !empty($_POST['afiliacja']) ? $_POST['afiliacja'] : $user['afiliacja'];
        $password = !empty($_POST['password']) ? $_POST['password'] : $user['password'];
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
    } else {
        echo "Użytkownik nie istnieje!";
        exit;
    }
}
?>