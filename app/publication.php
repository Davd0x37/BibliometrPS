<?php include "header.php";

function get_authors_links(array $authors)
{
    $res = [];
    foreach ($authors as $author) {
        $req = User::read("name LIKE '%" . $author . "%'");
        if ($req) {
            array_push($res, '<a href="publication.php?name=' . $author . '">' . $author . '</a>');
        }
    }
    return $res;
}

if (check("id")) {
    $pub = Publication::get_publication_by_id($_REQUEST['id']);
    if ($pub) {
        $pub = $pub[0];
        $sh = json_decode($pub['shares']);
        $shares = [];
        if ($sh) {
            foreach ($sh as $au => $val) {
                array_push($shares, $au . ": " . $val);
            }
            $shares = join(", ", $shares);
        } else {
            $shares = "";
        }

        $authors = get_authors_links(json_decode($pub['authors']));


        ?>
        <h1>Bibliometr - Publikacja: <?= $pub['title'] ?></h1>

        <fieldset name="Stronaglowna">
            <legend>Strona główna</legend>
            <p>Tytuł: <?= $pub['title'] ?></p>
            <p>Autorzy: <?= join(", ", $authors) ?></p>
            <p>Data publikacji: <?= $pub['publication_date'] ?></p>
            <p>Udziały: <?= $shares ?></p>
            <p>Punkty ministerialne: <?= $pub['points'] ?></p>
            <?php if ($pub['magazine'] !== "") { ?><p>Czasopismo: <?= $pub['magazine'] ?></p> <?php } ?>
            <?php if ($pub['conference'] !== "") { ?><p>Konferencja: <?= $pub['conference'] ?></p> <?php } ?>
            <p>DOI/URL: <a href="<?= $pub['url'] ?>"><?= $pub['url'] ?></a></p>
            <?php if (is_author(json_decode($pub['authors'])) || is_admin()) { ?>
                <a href="profile.php?edit_publication&id=<?= $pub['id'] ?>">Edytuj</a>
            <?php } ?>
            <?php if (is_admin()) { ?>
                <a href="admin.php?delete_publication&id=<?= $pub['id'] ?>">Usuń publikację</a>
            <?php } ?>
        </fieldset>

        <?php
            } else {
                echo "Nie znaleziono publikacji!";
            }
        } elseif (check("name")) {
            $pubs = Publication::get_publication_by_author($_REQUEST['name']);
            if ($pubs) {
                foreach ($pubs as $pub) {
                    foreach ($pub as $part) {
                        $shares = [];
                        $sh = json_decode($part['shares']);
                        if ($sh) {

                            foreach ($sh as $au => $val) {
                                array_push($shares, $au . ": " . $val);
                            }
                            $shares = join(", ", $shares);
                        } else {
                            $shares = "";
                        }

                        $authors = get_authors_links(json_decode($part['authors']));

                        ?>

                <fieldset name="Stronaglowna">
                    <legend><?= $part['title'] ?></legend>
                    <p>Tytuł: <?= $part['title'] ?></p>
                    <p>Autorzy: <?= join(", ", $authors) ?></p>
                    <p>Data publikacji: <?= $part['publication_date'] ?></p>
                    <p>Udziały: <?= $shares ?></p>
                    <p>Punkty ministerialne: <?= $part['points'] ?></p>
                    <?php if ($part['magazine'] !== "") { ?><p>Czasopismo: <?= $part['magazine'] ?></p> <?php } ?>
                    <?php if ($part['conference'] !== "") { ?><p>Konferencja: <?= $part['conference'] ?></p> <?php } ?>
                    <p>URL/DOI: <a href="<?= $part['url'] ?>"><?= $part['url'] ?></a></p>
                    <?php if (is_admin() || is_author($part['authors'])) { ?>
                        <a href="profile.php?edit_publication&id=<?= $part['id'] ?>">Edytuj</a>
                    <?php } ?>
                    <?php if (is_admin()) { ?>
                        <a href="admin.php?delete_publication&id=<?= $part['id'] ?>">Usuń publikację</a>
                    <?php } ?>
                </fieldset>
                <hr>
<?php
            }
        }
    }
} else {
    echo "Podaj id publikacji!";
    exit;
}
?>

<?php include "footer.php"; ?>