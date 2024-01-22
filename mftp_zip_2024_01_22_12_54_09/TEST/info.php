<?php
global $yhendus;
if (isset($_GET['code'])) {
    die(highlight_file(__File__, 1));
}

require('conf.php');

if (isset($_GET["kustuta"])) {
    $paring = $yhendus->prepare("DELETE FROM osalejad WHERE id=?");
    $paring->bind_param("i", $_GET["kustuta"]);
    executeStatement($paring);
}
function updateEsinemiskuupaev($yhendus, $id, $esinemiskuupaev)
{
    $paring = $yhendus->prepare("UPDATE osalejad SET esinemiskuupaev = ? WHERE id = ?");
    $paring->bind_param("si", $esinemiskuupaev, $id);
    executeStatement($paring);
}


if (isset($_GET['id'])) {
    echo $_GET['id'];
}

function executeStatement($paring)
{
    if (!$paring->execute()) {
        die("Error in execution: " . $paring->error);
    }
}

function getUserDetails($yhendus, $kasutaja)
{
    $paring = $yhendus->prepare("SELECT * FROM osalejad");
    $paring->bind_param("s", $kasutaja);
    $paring->execute();
    $result = $paring->get_result();
    return $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['esinemiskuupaev'] as $id => $esinemiskuupaev) {
        updateEsinemiskuupaev($yhendus, $id, $esinemiskuupaev);
    }
}

// Select * from osalejad
$result = $yhendus->query("SELECT * FROM osalejad");
$data = $result->fetch_all(MYSQLI_ASSOC);
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

<h2>ÜRITUSE ANDMED</h2>

<table border="1">
    <tr>
        <th>Eesnimi</th>
        <th>Perenimi</th>
        <th>Koorinimi</th>
        <th>Esinemiskuupäev</th>
        <th>Tegevused</th>
    </tr>

    <?php foreach ($data as $row): ?>
        <tr>
            <td><?php echo $row['eesnimi']; ?></td>
            <td><?php echo $row['perenimi']; ?></td>
            <td><?php echo $row['koorinimi']; ?></td>
            <td><?php echo $row['esinemiskuupaev']; ?></td>
            <td>
                <a href='?kustuta=<?php echo $row['id']; ?>' class="btn">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>

</html>
