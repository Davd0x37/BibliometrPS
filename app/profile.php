<?php include "./header.php"; ?>

<a href="?add_publication" class="button_action">Dodaj publikacje</a>
<a href="?edit_publication" class="button_action">Edytuj publikacje</a>
<a href="publication.php?name=<?= $_SESSION['name'] ?>" class="button_action">Moje publikacje</a>
<a href="?edit_profile" class="button_action">Edytuj moje dane</a>

<?php
if (isset($_REQUEST['add_publication'])) {
    include "./actions/add_publication.php";
} elseif (isset($_REQUEST['edit_publication'])) {
    include "./actions/edit_publication.php";
} elseif (isset($_REQUEST['edit_profile'])) {
    include "./actions/edit_user.php";
}
?>

<?php include "./footer.php"; ?>