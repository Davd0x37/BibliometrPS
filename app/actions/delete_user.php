<h1>Bibliometr - Usuń użytkownika</h1>
<form action="admin.php?delete_user" method="post" class="container">
    <div class="container__box fieldset__container">
        <p><label for="mail">Email:</label>
            <input type="email" name="mail" id="mail"></p>
        <input type="submit" class="button_action" value="Usuń">
    </div>
</form>
<?php
if (isset($_POST['mail']) && !empty($_POST['mail'])) {
    $user = User::read("email = '" . $_POST['mail'] . "'");
    if ($user) {
        $removeAuthors = Publication::remove_author($user[0]['id']);
        $req = User::delete("email = '" . $_POST['mail'] . "'");
        if ($req) {
            echo "Pomyślnie usunięto użytkownika!";
        } else {
            echo "Nie można usunąć użytkownika";
        }
    } else {
        echo "Użytkownik nie istnieje!";
    }
} elseif (isset($_GET['mail']) && !empty($_GET['mail'])) {
    $user = User::read("email = '" . $_GET['mail'] . "'");
    if ($user) {
        $removeAuthors = Publication::remove_author($user[0]['id']);
        $req = User::delete("email = '" . $_GET['mail'] . "'");
        if ($req) {
            echo "Pomyślnie usunięto użytkownika!";
        } else {
            echo "Nie można usunąć użytkownika";
        }
    } else {
        echo "Użytkownik nie istnieje!";
    }
}
?>