<?php
include "./header.php";

if (is_admin()) {
    ?>

    <a href="?edit_user" class="button_action">Edytuj użytkownika - Wyszukiwanie</a>
    <a href="?edit_user_list" class="button_action">Edytuj użytkownika - Lista</a>
    <a href="?delete_user" class="button_action">Usuń użytkownika</a>
    <a href="?edit_publication" class="button_action">Edytuj publikację</a>
    <a href="?delete_publication" class="button_action">Usuń publikację</a>


<?php

    if (isset($_REQUEST['edit_user'])) {
        include "./actions/edit_user.php";
    } elseif (isset($_REQUEST['delete_user'])) {
        include "./actions/delete_user.php";
    } elseif (isset($_REQUEST['edit_publication'])) {
        include "./actions/edit_publication.php";
    } elseif (isset($_REQUEST['delete_publication'])) {
        include "./actions/delete_publication.php";
    } elseif (isset($_REQUEST['edit_user_list'])) {
        include "./actions/edit_user_list.php";
    }
} else {
    echo "Nie jesteś adminem!";
    exit;
}
?>

<?php include "./footer.php"; ?>