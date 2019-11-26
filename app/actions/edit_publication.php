<h1>Bibliometr - Edytuj publikacje</h1>
<?php
if ((isset($_POST['id']) && !empty($_POST['id'])) || (isset($_GET['id']) && !empty($_GET['id']))) {
    if ((isset($_POST['id']) && !empty($_POST['id']))) {
        $pub = Publication::get_publication_by_id($_POST['id']);
    } elseif (isset($_GET['id']) && !empty($_GET['id'])) {
        $pub = Publication::get_publication_by_id($_GET['id']);
    }

    if (!$pub) {
        echo "Publikacja nie istnieje!";
        exit;
    } else {
        $pub = $pub[0];

        if (!is_admin()) {
            if (!is_author($pub['authors'])) {
                echo "Nie jesteś autorem!";
                exit;
            }
        }

        $shares = "";
        foreach (json_decode($pub['shares']) as $key => $val) {
            $shares .= $key . "-" . $val . ", ";
        }
        $shares = substr($shares, 0, -2);

        ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>?edit_publication" method="post" class="container">
            <div class="container__box fieldset__container">
                <input type="hidden" name="id" value="<?= $pub['id'] ?>" />
                <p><label for="title">Tytul:</label>
                    <input type="text" name="title" id="title" value="<?= $pub['title'] ?>"></p>
                <p><label for="authors">Autorzy (po przecinku):</label>
                    <input type="text" name="authors" id="authors" value="<?= join(", ", json_decode($pub['authors'])) ?>"></p>
                <p><label for="publication_date">Data publikacji:</label>
                    <input type="date" name="publication_date" id="publication_date" value="<?= $pub['publication_date'] ?>"></p>
                <p><label for="shares">Udziały (Wzór: Mark-20, John-30):</label>
                    <input type="text" name="shares" id="shares" value="<?= $shares ?>"></p>
                <p><label for="points">Punkty ministerialne:</label>
                    <input type="number" name="points" id="points" value="<?= $pub['points'] ?>"></p>
                <p><label for="magazine">Czasopismo:</label>
                    <input type="radio" name="magconf" id="magazine" value="magazine" <?= $pub['magazine'] !== "" ? "checked" : "" ?>></p>
                <p><label for="conference">Konferencja:</label>
                    <input type="radio" name="magconf" id="conference" value="conference" <?= $pub['conference'] !== "" ? "checked" : "" ?>></p>
                <input type="text" name="magconfval" id="magconf" value="<?= $pub['magazine'] ? $pub['magazine'] : $pub['conference'] ?>">
                <p><label for="url">URL doi:</label>
                    <input type="text" name="url" id="url" value="<?= $pub['url'] ?>"></p>
                <input type="submit" class="button_action" value="Edytuj">
            </div>
        </form>
    <?php
            if (
                isset($_POST['title']) &&
                isset($_POST['authors']) &&
                isset($_POST['publication_date']) &&
                isset($_POST['shares']) &&
                isset($_POST['points']) &&
                isset($_POST['magconf']) &&
                isset($_POST['magconfval']) &&
                isset($_POST['url'])
            ) {

                $title = !empty($_POST['title']) ? $_POST['title'] : $pub['title'];
                $authors = !empty($_POST['authors']) ? explode(", ", $_POST['authors']) : $pub['authors'];
                $publication_date = !empty($_POST['publication_date']) ? $_POST['publication_date'] : $pub['publication_date'];
                $points = !empty($_POST['points']) ? $_POST['points'] : $pub['points'];
                $magazine = !empty($_POST['magconf']) && $_POST['magconf'] === "magazine" ? $_POST['magconfval'] : "";
                $conference = !empty($_POST['magconf']) && $_POST['magconf'] === "conference" ? $_POST['magconfval'] : "";
                $url = !empty($_POST['url']) ? $_POST['url'] : $pub['url'];

                $shares = !empty($_POST['shares']) ? edit_shares($_POST['shares']) : $pub['shares'];


                if ($shares === null) {
                    echo "Za dużo udziałów!";
                    exit;
                }

                // var_dump(json_encode($authors));
                $exists = [];
                foreach ($authors as $author) {
                    array_push($exists, User::author_exists($author));
                }

                if (in_array(false, $exists)) {
                    echo "Podany użytkownik nie istnieje!";
                } else {

                    $req = Publication::update_publication(
                        $pub['id'],
                        [
                            "title" => $title,
                            "authors" => json_encode($authors, JSON_UNESCAPED_UNICODE),
                            "publication_date" => $publication_date,
                            "shares" => json_encode($shares, JSON_UNESCAPED_UNICODE),
                            "points" => $points,
                            "magazine" => $magazine,
                            "conference" => $conference,
                            "url" => $url,
                        ]
                    );
                    if ($req) {
                        echo "Pomyślnie edytowano publikacje!";
                    } else {
                        echo "Nie można edytować publikacji";
                    }
                }
            }
        }
    } else {
        ?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>?edit_publication" method="post" class="container">
        <div class="container__box fieldset__container">
            <p><label for="id">ID publikacji:</label>
                <input type="number" name="id" id="id"></p>
            <input type="submit" class="button_action" value="Wyszukaj">
        </div>
    </form>
<?php } ?>