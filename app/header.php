<?php

require __DIR__ . "/../config.php";
require __DIR__ . "/../orm/db.php";
require __DIR__ . "/../actions/user.php";
require __DIR__ . "/../actions/publication.php";
require __DIR__ . "/../utils.php";


session_start();
ob_start();

if (isset($_SESSION['name'])) {
  $req = User::author_exists($_SESSION['name']);
  if (!$req) {
    session_unset();
    session_destroy();
    header("Location: index.php");
  }
}
?>

<html>

<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="main.css?ver=1">
  <title>Bibliometr</title>
</head>

<body>
  <nav>
    <a href="index.php">Strona główna</a>
    <?php
    if (is_admin()) {
      ?>
      <a href="admin.php">Panel administracji</a>
    <?php } ?>
    <?php
    if (!isset($_SESSION['name'])) {
      ?>
      <a href="login.php">Logowanie</a>
      <a href="register.php">Rejestracja</a>
    <?php } else { ?>
      <a href="profile.php">Mój profil</a>
      <a href="index.php?logout">Wyloguj</a>
    <?php } ?>
  </nav>

  <?php
  if (isset($_SESSION['name'])) {
    ?>
    <h1>Witaj <?= $_SESSION['name'] ?>!</h1>
  <?php } else { ?>
    <h1>Bibliometr</h1>
  <?php } ?>

  <?php
  if (isset($_REQUEST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
  }
  ?>