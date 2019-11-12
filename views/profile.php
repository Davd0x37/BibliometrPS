<?php include "./header.php"; ?>

<a href="?add_publication" class="button_action">Dodaj publikacje</a>

<?php if (isset($_REQUEST['add_publication'])) { ?>

    <h1>Bibliometr - Dodaj publikacje</h1>
    <form action="profile.php?add_publication" method="post" class="container">
        <div class="container__box fieldset__container">
            <p><label for="title">Tytul:</label>
                <input type="text" name="title" id="title" value=""></p>
            <p><label for="authors">Autorzy (po przecinku):</label>
                <input type="text" name="authors" id="authors" value=""></p>
            <p><label for="publication_date">Data publikacji:</label>
                <input type="date" name="publication_date" id="publication_date" value=""></p>
            <p><label for="shares">Udziały (Wzór: Mark-20, John-30):</label>
                <input type="text" name="shares" id="shares" value=""></p>
            <p><label for="points">Punkty ministerialne:</label>
                <input type="number" name="points" id="points" value=""></p>
            <p><label for="magazine">Czasopismo:</label>
                <input type="text" name="magazine" id="magazine" value=""></p>
            <p><label for="url">URL doi:</label>
                <input type="text" name="url" id="url" value=""></p>
            <input type="submit" class="button_action" value="Rejestruj">
        </div>
    </form>
<?php
    if (
        check('title') &&
        check('authors') &&
        check('publication_date') &&
        check('shares') &&
        check('points') &&
        check('magazine') &&
        check('url')
    ) {
        $shares = explode(",", $_POST['shares']);
        $shar = new stdClass();
        foreach ($shares as $sh) {
            $nwsh = explode("-", $sh);
            $let = $nwsh[0];
            $shar->$let = $nwsh[1];
        }
        $authors = explode(", ", $_POST['authors']);

        $pub = Publication::save_publication(
            $_POST['title'],
            json_encode($authors),
            $_POST['publication_date'],
            json_encode($shar),
            $_POST['points'],
            $_POST['magazine'],
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

<?php include "./footer.php"; ?>