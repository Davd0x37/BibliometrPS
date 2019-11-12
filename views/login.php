<?php include "./header.php"; ?>

<h1>Bibliometr - Logowanie</h1>
<form action="login.php" method="post" class="container">
    <div class="container__box fieldset__container">
        <p><label for="mail">Email:</label>
            <input type="email" name="mail" id="mail" value=""></p>
        <p><label for="haselko">Hasło:</label>
            <input type="password" name="haselko" id="haselko" value=""></p>
        <input type="submit" class="button_action" value="Zaloguj">
    </div>
</form>
<?php
// session_start();
if (
    isset($_POST['mail']) && !empty($_POST['mail']) &&
    isset($_POST['haselko']) && !empty($_POST['haselko'])
) {
    $user = User::login($_POST['mail'], $_POST['haselko']);
    if ($user) {
        $user = $user[0];
        if ($user['role'] == "admin") {
            $_SESSION['role'] = "admin";
        } else {
            $_SESSION['role'] = "user";
        }
        $_SESSION['name'] = $user['name'];

        echo "Zalogowano";
        header("Location: index.php");
    } else {
        echo "Błędne dane!";
    }
}
?>

<?php include "./footer.php"; ?>