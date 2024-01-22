<?php if (isset($_GET['code'])) {die(highlight_file(__File__, 1)); }?>
<?php
require('conf.php');

function insertosalejad($yhendus, $eesnimi, $perenimi, $koorinimi) {
    $paring = $yhendus->prepare("INSERT INTO osalejad(eesnimi, perenimi, koorinimi) VALUES (?, ?, ?)");
    $paring->bind_param("sss", $eesnimi, $perenimi, $koorinimi);
    executeStatement($paring);
}

function executeStatement($paring) {
    if (!$paring->execute()) {
        die("Error in execution: " . $paring->error);
    }
}

function getUserDetails($yhendus, $kasutaja) {
    $paring = $yhendus->prepare("SELECT * FROM osalejad WHERE kasutaja = ?");
    $paring->bind_param("s", $kasutaja);
    $paring->execute();
    $result = $paring->get_result();
    return $result->fetch_assoc();
}

function deleteUser($yhendus, $kasutaja) {
    $paring = $yhendus->prepare("DELETE FROM osalejad WHERE kasutaja = ?");
    $paring->bind_param("s", $kasutaja);
    executeStatement($paring);
}

global $yhendus;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["kustuta"])) {
    $kasutaja = $_GET["kustuta"];

    deleteUser($yhendus, $kasutaja);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_REQUEST["eesnimi"]) && !empty(trim($_REQUEST["eesnimi"]))
        && isset($_REQUEST["perenimi"]) && !empty(trim($_REQUEST["perenimi"]))
        && isset($_REQUEST["koorinimi"]) && !empty(trim($_REQUEST["koorinimi"]))) {

        insertosalejad(
            $yhendus,
            $_REQUEST["eesnimi"],
            $_REQUEST["perenimi"],
            $_REQUEST["koorinimi"]
        );
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>osalejad</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<a href="https://kiurarbeiter22.thkit.ee/TEST/registreering.php">REGISTREERING</a>
<a href="https://kiurarbeiter22.thkit.ee/TEST/table.php">TABEL</a>
<a href="https://kiurarbeiter22.thkit.ee/TEST/info.php">INFO</a>
<h2>LAULUPEO REGISTREERING</h2>

<form action="?" method="post">
    eesnimi: <input type="text" name="eesnimi" required><br>
    perenimi: <input type="text" name="perenimi" required><br>
    koorinimi: <input type="text" name="koorinimi" required><br>
    <input type="submit" value="lisa andmed">
</form>
</body>

</html>
