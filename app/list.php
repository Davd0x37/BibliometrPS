<?php


// ?action=search
// tytul=asd
// author=ghj
// afiliacja=zut
// dataod=2019-11-28
// datado=2019-11-21
// sortujwedlug=data
// nazwa=on
// autor=on
// wspolautor=on
// data=on
// punkty=on

$action = isset($_REQUEST['action']);

$tytul = check('tytul');
$author = check('author');
$dataod = check('dataod');
$datado = check('datado');
$sortujwedlug = isset($_REQUEST['sortujwedlug']);
$nazwabox = check('nazwabox');
$authorbox = check('authorbox');
$sharesbox = check('sharesbox');
$databox = check('databox');
$punktybox = check('punktybox');
$magazinebox = check('magazinebox');
$conferencebox = check('conferencebox');
$urlbox = check('urlbox');
$sort = check('sort');

$default = (empty($_REQUEST['tytul']) &&
    empty($_REQUEST['author']) &&
    empty($_REQUEST['dataod']) &&
    empty($_REQUEST['datado']) &&
    empty($_REQUEST['nazwabox']) &&
    empty($_REQUEST['authorbox']) &&
    empty($_REQUEST['sharesbox']) &&
    empty($_REQUEST['databox']) &&
    empty($_REQUEST['punktybox']) &&
    empty($_REQUEST['magazinebox']) &&
    empty($_REQUEST['conferencebox']) &&
    empty($_REQUEST['urlbox']) &&
    empty($_REQUEST['sort'])) ? "checked" : "";

$conds = array_filter($_REQUEST, function ($val, $key) {
    return $val !== "";
}, ARRAY_FILTER_USE_BOTH);

$cond = null;
$tables = null;
if ($action) {
    $tables = get_search_cond_box($conds);
    $where = get_search_cond($conds);
    $whereExport = get_search_cond_id($conds);
    $sorts = get_search_cond_sort($conds);
    $cond = ($whereExport !== "()" ? " WHERE " . $whereExport : "");
    $cond .= ($cond !== "" && $where !== "" ? " AND " . $where : "");
    $cond .= ($sorts !== "" ? " ORDER BY " . $sorts : "");
}

$tableNames = [];
$nazwabox ? array_push($tableNames, ["title" => "Tytuł publikacji"]) : "";
$authorbox ? array_push($tableNames, ["authors" => "Autorzy"]) : "";
$sharesbox ? array_push($tableNames, ["shares" => "Udziały"]) : "";
$databox ? array_push($tableNames, ["publication_date" => "Data publikacji"]) : "";
$punktybox ? array_push($tableNames, ["points" => "Punkty ministerialne"]) : "";
$magazinebox ? array_push($tableNames, ["magazine" => "Czasopismo"]) : "";
$conferencebox ? array_push($tableNames, ["conference" => "Konferencja"]) : "";
$urlbox ? array_push($tableNames, ["url" => "URL/DOI"]) : "";
$rows = count($tableNames);
$pub = Publication::get_publications_cond($cond);
if ($pub) {
    $len = count($pub);

    if ($action && $_REQUEST['action'] === "Eksport") {
        ob_end_clean();
        ob_start();
        $ret = export_file($rows, $len, $tables, $tableNames, $pub);
    }
    ?>
    <h1>Bibliometr</h1>
    <form action="?" method="get">
        <div class="strona__glowna">
            <div class="fieldset__container">
                <div>
                    <label for="tytul">Tytuł:</label>
                    <input type="text" name="tytul" id="tytul" value="<?= $tytul ? $_REQUEST['tytul'] : "" ?>" />
                </div>
                <div>
                    <label for="author">Autor:</label>
                    <input type="text" name="author" id="author" value="<?= $author ? $_REQUEST['author'] : "" ?>" />
                </div>
            </div>
            <div class="fieldset__container">
                <div>
                    <label for="dataod">Data od:</label>
                    <input type="date" name="dataod" id="dataod" value="<?= $dataod ? $_REQUEST['dataod'] : "" ?>" />
                </div>
                <div>
                    <label for="datado">Data do:</label>
                    <input type="date" name="datado" id="datado" value="<?= $datado ? $_REQUEST['datado'] : "" ?>" />
                </div>
            </div>
            <div class="fieldset__container">
                <div>
                    <label for="sortujwedlug">Sortuj według:</label>
                    <select name="sortujwedlug">
                        <option value="title" <?= $sortujwedlug ? ($_REQUEST['sortujwedlug'] === "title" ? "selected" : "") : "selected" ?>>Nazwa publikacji</option>
                        <option value="authors" <?= $sortujwedlug ? ($_REQUEST['sortujwedlug'] === "authors" ? "selected" : "") : "" ?>>Autor publikacji</option>
                        <option value="shares" <?= $sortujwedlug ? ($_REQUEST['sortujwedlug'] === "shares" ? "selected" : "") : "" ?>>Współautor publikacji</option>
                        <option value="publication_date" <?= $sortujwedlug ? ($_REQUEST['sortujwedlug'] === "publication_date" ? "selected" : "") : "" ?>>Data publikacji</option>
                        <option value="points" <?= $sortujwedlug ? ($_REQUEST['sortujwedlug'] === "points" ? "selected" : "") : "" ?>>Punkty</option>
                        <option value="magazine" <?= $sortujwedlug ? ($_REQUEST['sortujwedlug'] === "magazine" ? "selected" : "") : "" ?>>Czasopismo</option>
                        <option value="conference" <?= $sortujwedlug ? ($_REQUEST['sortujwedlug'] === "conference" ? "selected" : "") : "" ?>>Konferencja</option>
                    </select>
                    <p><label for="asc">Rosnąco:</label>
                        <input type="radio" name="sort" id="asc" value="asc" <?= $sort ? ($_REQUEST['sort'] === "asc" ? "checked" : "") : "checked" ?>></p>
                    <p><label for="desc">Malejąco:</label>
                        <input type="radio" name="sort" id="desc" value="desc" <?= $sort ? ($_REQUEST['sort'] === "desc" ? "checked" : "") : "" ?>></p>
                </div>
            </div>
        </div>
        <div class="strona__glowna">
            <div class="select__options">
                <div class="fieldset__container">
                    <div>
                        <input type="checkbox" name="nazwabox" id="nazwabox" <?= $default ? "checked" : ($nazwabox ? "checked" : "") ?>>
                        <label for="nazwabox">Nazwa publikacji</label>
                    </div>
                    <div>
                        <input type="checkbox" name="authorbox" id="authorbox" <?= $default ? "checked" : ($authorbox ? "checked" : "") ?>>
                        <label for="authorbox">Autorzy publikacji</label>
                    </div>
                    <div>
                        <input type="checkbox" name="sharesbox" id="sharesbox" <?= $default ? "checked" : ($sharesbox ? "checked" : "") ?>>
                        <label for="sharesbox">Udziały</label>
                    </div>
                </div>
                <div class="fieldset__container">
                    <div>
                        <input type="checkbox" name="punktybox" id="punktybox" <?= ($punktybox ? "checked" : "") ?>>
                        <label for="punktybox">Punkty</label>
                    </div>
                    <div>
                        <input type="checkbox" name="databox" id="databox" <?= $default ? "checked" : ($databox ? "checked" : "") ?>>
                        <label for="databox">Data publikacji</label>
                    </div>
                </div>
                <div class="fieldset__container">
                    <div>
                        <input type="checkbox" name="magazinebox" id="magazinebox" <?= ($magazinebox ? "checked" : "") ?>>
                        <label for="magazinebox">Czasopismo</label>
                    </div>
                    <div>
                        <input type="checkbox" name="conferencebox" id="conferencebox" <?= ($conferencebox ? "checked" : "") ?>>
                        <label for="conferencebox">Konferencja</label>
                    </div>
                    <div>
                        <input type="checkbox" name="urlbox" id="urlbox" <?= ($urlbox ? "checked" : "") ?>>
                        <label for="urlbox">URL/DOI</label>
                    </div>
                </div>
            </div>
            <div class="select__options__submit">
                <input class="search_action" name="action" type="submit" value="Szukaj">
                <input class="search_action" name="action" type="submit" value="Eksport">
            </div>
        </div>
        <hr>
        <div class="divTable">
            <div class="divTableBody">
                <div class="divTableRow">
                    <div class="divTableCell">Wybierz</div>
                    <?php

                        if ($action) {
                            ?>
                        <?php if ($nazwabox) { ?> <div class="divTableCell">Tytuł publikacji</div> <?php } ?>
                        <?php if ($authorbox) { ?> <div class="divTableCell">Autorzy</div> <?php } ?>
                        <?php if ($sharesbox) { ?> <div class="divTableCell">Udziały</div> <?php } ?>
                        <?php if ($databox) { ?> <div class="divTableCell">Data publikacji</div> <?php } ?>
                        <?php if ($punktybox) { ?> <div class="divTableCell">Punkty ministerialne</div> <?php } ?>
                        <?php if ($magazinebox) { ?> <div class="divTableCell">Czasopismo</div> <?php } ?>
                        <?php if ($conferencebox) { ?> <div class="divTableCell">Konferencja</div> <?php } ?>
                        <?php if ($urlbox) { ?> <div class="divTableCell">URL/DOI</div> <?php } ?>
                        <?php if (is_admin()) { ?>
                            <div class="divTableCell">Edytuj</div>
                            <div class="divTableCell">Usuń</div>
                        <?php } elseif (isset($_SESSION['name'])) { ?>
                            <div class="divTableCell">Edytuj</div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="divTableCell">Tytuł publikacji</div>
                        <div class="divTableCell">Autorzy</div>
                        <div class="divTableCell">Udziały</div>
                        <div class="divTableCell">Data publikacji</div>
                        <?php if (is_admin()) { ?>
                            <div class="divTableCell">Edytuj</div>
                            <div class="divTableCell">Usuń</div>
                        <?php } elseif (isset($_SESSION['name'])) { ?>
                            <div class="divTableCell">Edytuj</div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <?php
                for ($i = 0; $i < $len; $i++) {
                    $el = $pub[$i];
                    $shares = [];
                    $sh = json_decode($el['shares']);
                    if ($sh) {
                        foreach ($sh as $au => $val) {
                            array_push($shares, $au . ": " . $val);
                        }
                        $shares = join(", ", $shares);
                    } else {
                        $shares = "";
                    }
                    ?>
                <div class="divTableRow">
                    <div class="divTableCell"><input type="checkbox" name="pubid[]" id="pubid" value="<?= $el['id'] ?>" /></div>
                    <?php if ($action) { ?>
                        <?php if ($nazwabox) { ?>
                            <div class="divTableCell">
                                <a href="publication.php?id=<?= $el['id'] ?>"><?= $el['title'] ?></a>
                            </div>
                        <?php } ?>
                        <?php if ($authorbox) { ?> <div class="divTableCell"><?= join(", ", json_decode($el['authors'])) ?></div> <?php } ?>
                        <?php if ($sharesbox) { ?> <div class="divTableCell"><?= $shares ?></div> <?php } ?>
                        <?php if ($databox) { ?> <div class="divTableCell"><?= $el['publication_date'] ?></div> <?php } ?>
                        <?php if ($punktybox) { ?> <div class="divTableCell"><?= $el['points'] ?></div> <?php } ?>
                        <?php if ($magazinebox) { ?> <div class="divTableCell"><?= $el['magazine'] ? $el['magazine'] : "" ?></div> <?php } ?>
                        <?php if ($conferencebox) { ?> <div class="divTableCell"><?= $el['conference'] ? $el['conference'] : "" ?></div> <?php } ?>
                        <?php if ($urlbox) { ?> <div class="divTableCell"><a href="<?= $el['url'] ?>"><?= $el['url'] ?></a></div> <?php } ?>
                        <?php if (is_admin()) { ?>
                            <div class="divTableCell">
                                <a href="admin.php?edit_publication&id=<?= $el['id'] ?>">Edytuj</a>
                            </div>
                            <div class="divTableCell">
                                <a href="admin.php?delete_publication&id=<?= $el['id'] ?>">Usuń</a>
                            </div>
                        <?php } elseif (is_author($el['authors'])) { ?>
                            <div class="divTableCell">
                                <a href="profile.php?edit_publication&id=<?= $el['id'] ?>">Edytuj</a>
                            </div>
                        <?php } ?>

                    <?php } else { ?>
                        <div class="divTableCell">
                            <a href="publication.php?id=<?= $el['id'] ?>"><?= $el['title'] ?></a>
                        </div>
                        <div class="divTableCell"><?= join(", ", json_decode($el['authors'])) ?></div>
                        <div class="divTableCell"><?= $shares ?></div>
                        <div class="divTableCell"><?= $el['publication_date'] ?></div>
                        <?php if (is_admin()) { ?>
                            <div class="divTableCell">
                                <a href="admin.php?edit_publication&id=<?= $el['id'] ?>">Edytuj</a>
                            </div>
                            <div class="divTableCell">
                                <a href="admin.php?delete_publication&id=<?= $el['id'] ?>">Usuń</a>
                            </div>
                        <?php } elseif (is_author($el['authors'])) { ?>
                            <div class="divTableCell">
                                <a href="profile.php?edit_publication&id=<?= $el['id'] ?>">Edytuj</a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </form>
<?php } ?>