<h1>Bibliometr - Dodaj publikacje</h1>
<form action="profile.php?add_publication" method="post" class="container">
    <div class="container__box fieldset__container">
        <p><label for="title">Tytul:</label>
            <input type="text" name="title" id="title" value=""></p>
        <p><label for="authors">Autorzy (po przecinku):</label>
            <input type="text" name="authors" id="authors" value="<?= $_SESSION['name'] ?>"></p>
        <p><label for="publication_date">Data publikacji:</label>
            <input type="date" name="publication_date" id="publication_date" value=""></p>
        <p><label for="shares">Udziały (Wzór: Mark-20,John-30):</label>
            <input type="text" name="shares" id="shares" value=""></p>
        <p><label for="points">Punkty ministerialne:</label>
            <input type="number" name="points" id="points" value=""></p>
        <p><label for="magazine">Czasopismo:</label>
            <input type="radio" name="magconf" id="magazine" checked value="magazine"></p>
        <p><label for="conference">Konferencja:</label>
            <input type="radio" name="magconf" id="conference" value="conference"></p>
        <input type="text" name="magconfval" id="magconf" value="">
        <p><label for="url">URL doi:</label>
            <input type="text" name="url" id="url" value=""></p>
        <input type="submit" class="button_action" value="Publikuj">
    </div>
</form>

<?php
if (
    check('title') &&
    check('authors') &&
    check('publication_date') &&
    check('shares') &&
    check('points') &&
    check('magconf') &&
    check('magconfval') &&
    check('url')
) {
    $shares = edit_shares($_POST['shares']);

    if ($shares === null) {
        echo "Za dużo udziałów!";
        exit;
    }

    $authors = explode(", ", $_POST['authors']);

    $exists = [];
    foreach ($authors as $author) {
        $author = trim($author);
        array_push($exists, User::author_exists($author));
    }
    foreach ($shares as $author => $sh) {
        $author = trim($author);
        array_push($exists, User::author_exists($author));
    }

    if (in_array(false, $exists)) {
        echo "Podany użytkownik nie istnieje!";
    } else {

        $pub = Publication::save_publication(
            $_POST['title'],
            json_encode($authors),
            $_POST['publication_date'],
            json_encode($shares),
            $_POST['points'],
            $_POST['magconf'],
            $_POST['magconfval'],
            $_POST['url']
        );
        if ($pub) {
            echo "Dodano publikacje!";
        } else {
            echo "Publikacja już istnieje!";
        }
    }
}
?>