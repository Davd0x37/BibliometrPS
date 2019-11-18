<?php include "header.php";

if (isset($_SESSION['name'])) {
    die("Jesteś już zalogowana/y!");
    exit;
} else {
    ?>

    <h1>Bibliometr - Rejestracja</h1>
    <form action="register.php" method="post" class="container">
        <div class="container__box fieldset__container">
            <p><label for="name">Imię i nazwisko:</label>
                <input type="text" name="name" id="name" value=""></p>
            <p><label for="mail">Email:</label>
                <input type="email" name="mail" id="mail" value=""></p>
            <p><label for="haselko">Hasło:</label>
                <input type="password" name="haselko" id="haselko" value=""></p>
            <p><label for="afiliacja">Afiliacja:</label>
                <input type="text" name="afiliacja" id="afiliacja" value=""></p>
            <input type="submit" class="button_action" value="Rejestruj">
        </div>
    </form>
    <?php
        if (
            isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['mail']) && !empty($_POST['mail']) &&
            isset($_POST['afiliacja']) && !empty($_POST['afiliacja']) &&
            isset($_POST['haselko']) && !empty($_POST['haselko'])
        ) {
            $user = User::read("mail = '" . $_POST['mail'] . "'");
            if ($user) {
                echo "Błędne dane!";
            } else {
                $cr = User::create($_POST['name'], md5($_POST['haselko']), $_POST['mail'], $_POST['afiliacja']);
                if ($cr) {
                    $_SESSION['name'] = $_POST['name'];
                    $_SESSION['role'] = 'user';

                    echo "Zarejestrowano!";
                    header("Location: index.php");
                } else {
                    echo "Użytkownik już istnieje!";
                }
            }
        }
        ?>

<?php
}
include "footer.php";
?>