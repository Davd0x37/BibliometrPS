<?php

require "../config.php";
require "../orm/db.php";
require "../actions/user.php";
require "../actions/publication.php";
require "../utils.php";

session_start();
$_SESSION['role'] = "admin";
$_SESSION['name'] = "Zygfryd";
?>

<html>

<head>
  <meta charset="UTF-8" />
  <link rel="Stylesheet" type="text/css" href="main.css" />
  <title>Bibliometr</title>
</head>

<body>
  <nav>
    <a href="index.php">Strona główna</a>
    <?php
    if (isset($_SESSION['role']) && ($_SESSION['role'] == "admin")) {
      ?>
      <a href="admin.php">Panel administracji</a>
    <?php } ?>
    <?php
    if (!isset($_SESSION['name'])) {
      ?>
      <!-- <a href="login.php">Logowanie</a> -->
      <!-- <a href="register.php">Rejestracja</a> -->
    <?php } else { ?>
      <a href="profile.php">Mój profil</a>
      <!-- <a href="index.php?logout">Wyloguj</a> -->
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
  // if (isset($_REQUEST['logout'])) {
  //   session_unset();
  //   session_destroy();
  //   header("Location: index.php");
  // }
  ?>