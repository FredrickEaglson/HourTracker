<?php
$pass = "eYY)]ajDe274n_qJ";
$user = "hourtracker";
$db = "hourtracker";
$server = "localhost";

$con = mysqli_connect($server, $user, $pass, $db);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}