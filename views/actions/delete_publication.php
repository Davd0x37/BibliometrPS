<h1>Bibliometr - Usuń publikacje</h1>
<form action="admin.php?delete_publication" method="post" class="container">
    <div class="container__box fieldset__container">
        <p><label for="id">ID:</label>
            <input type="number" name="id" id="id"></p>
        <input type="submit" class="button_action" value="Edytuj">
    </div>
</form>
<?php
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $pub = Publication::get_publication_by_id($_POST['id']);
    if ($pub) {
        $req = Publication::delete_publication($_POST['id']);
        if ($req) {
            echo "Pomyślnie usunięto publikacje!";
        } else {
            echo "Nie można usunąć publikacji";
        }
    } else {
        echo "Publikacja nie istnieje!";
    }
}
?>