<?php include "./header.php";

if (check("id")) {
    $pub = Publication::get_publication_by_id($_REQUEST['id']);
    $pub = $pub[0];
    $sh = json_decode($pub['shares']);
    $shares = [];
    foreach ($sh as $au => $val) {
        array_push($shares, $au . ": " . $val);
    }
    $shares = join(", ", $shares);

    ?>
    <h1>Bibliometr - Publikacja: <?= $pub['title'] ?></h1>

    <fieldset name="Stronaglowna">
        <legend>Strona główna</legend>
        <p>Tytuł: <?= $pub['title'] ?></p>
        <p>Autorzy: <?= join(", ", json_decode($pub['authors'])) ?></p>
        <p>Data publikacji: <?= $pub['publication_date'] ?></p>
        <p>Udziały: <?= $shares ?></p>
        <p>Punkty ministerialne: <?= $pub['points'] ?></p>
        <p>Czasopismo: <?= $pub['magazine'] ?></p>
        <p>URL: <a href="<?= $pub['url'] ?>"><?= $pub['url'] ?></a></p>
    </fieldset>

<?php
} else {
    echo "Podaj id publikacji!";
    exit;
}
?>

<?php include "./footer.php"; ?>