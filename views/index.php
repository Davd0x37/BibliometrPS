<?php include "./header.php"; ?>

<form>
  <div class="strona__glowna">
    <div class="fieldset__container">
      <div>
        <label for="tytul">Tytuł:</label>
        <input type="text" name="tytul" id="tytul" value="" />
      </div>
      <div>
        <label for="imieautora">Imie autora:</label>
        <input type="text" name="imieautora" id="imieautora" value="" />
      </div>
    </div>
    <div class="fieldset__container">
      <div>
        <label for="nazwiskoautora">Nazwisko autora:</label>
        <input type="text" name="nazwiskoautora" id="nazwiskoautora" value="" />
      </div>
      <div>
        <label for="dataod">Data od:</label>
        <input type="date" name="dataod" id="dataod" value="" />
      </div>
    </div>
    <div class="fieldset__container">
      <div>
        <label for="datado">Data do:</label>
        <input type="date" name="datado" id="datado" value="" />
      </div>
      <div>
        <label for="sortujpo">Sortuj po:</label>
        <input type="text" name="sortujpo" id="sortujpo" value="" />
      </div>
    </div>
    <div class="fieldset__container">
      <p><label for="pokazujtylko">Pokazuj tylko:</label></p>
      <select name="pokazujtylko">
        <option value="1">Nazwa publikacji</option>
        <option value="2">Autor publikacji</option>
        <option value="3">Współautor publikacji</option>
        <option value="4">Data publikacji</option>
        <option value="5">Punkty</option>
        <option value="6">Afiliacja</option>
      </select>

      <input class="search_action" type="submit" value="Szukaj">
      <button class="search_action" value="Eksportuj">Eksportuj</button>
    </div>
  </div>
</form>

<div class="divTable">
  <div class="divTableBody">
    <div class="divTableRow">
      <div class="divTableCell"></div>
      <div class="divTableCell">Współautor</div>
      <div class="divTableCell">Tytuł publikacji</div>
      <div class="divTableCell">Punkty</div>
      <?php if ($_SESSION['role'] === "admin") { ?>
        <div class="divTableCell">Edytuj</div>
      <?php } ?>
    </div>
  </div>
  <?php
  $pub = Publication::get_publications();
  if ($pub) {
    $len = count($pub);
    for ($i = 0; $i < $len; $i++) {
      $el = $pub[$i];
      ?>
      <div class="divTableRow">
        <div class="divTableCell"><input type="checkbox" /></div>
        <div class="divTableCell"><?= join(", ", json_decode($el['authors'])) ?></div>
        <div class="divTableCell">
          <a href="publication.php?id=<?= $el['id'] ?>"><?= $el['title'] ?></a>
        </div>
        <div class="divTableCell"><?= $el['points'] ?></div>
        <?php if ($_SESSION['role'] === "admin") { ?>
          <div class="divTableCell">
            <a href="admin.php?edit_publication&id=<?= $el['id'] ?>">Edytuj</a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  <?php } ?>
</div>


<?php include "./footer.php"; ?>