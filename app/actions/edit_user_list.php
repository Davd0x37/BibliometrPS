<h1>Bibliometr - Edytuj użytkownika</h1>
<?php
require "render_view.php";

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
} else {
    ?>

    <div class="divTable">
        <div class="divTableBody">
            <div class="divTableRow">
                <div class="divTableCell">Nazwa</div>
                <div class="divTableCell">Email</div>
                <div class="divTableCell">Rola</div>
                <div class="divTableCell">Edytuj</div>
                <div class="divTableCell">Usuń</div>
            </div>
        </div>
        <?php
            $user = User::read();
            if ($user) {
                $len = count($user);
                for ($i = 0; $i < $len; $i++) {
                    $el = $user[$i];
                    ?>
                <div class="divTableRow">
                    <div class="divTableCell"><?= $el['name'] ?></div>
                    <div class="divTableCell"><?= $el['email'] ?></div>
                    <div class="divTableCell"><?= $el['role'] ?></div>
                    <div class="divTableCell">
                        <a href="admin.php?edit_user&name=<?= $el['name'] ?>">Edytuj</a>
                    </div>
                    <div class="divTableCell">
                        <a href="admin.php?delete_user&mail=<?= $el['email'] ?>">Usuń</a>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

<?php
}
?>