<?php
if (isset($_GET['code'])) {
    die(highlight_file(__File__, 1));
}

require('conf.php');

function updateEsinemiskuupaev($yhendus, $id, $esinemiskuupaev)
{
    $paring = $yhendus->prepare("UPDATE osalejad SET esinemiskuupaev = ? WHERE id = ?");
    $paring->bind_param("si", $esinemiskuupaev, $id);
    executeStatement($paring);
}

function executeStatement($paring)
{
    if (!$paring->execute()) {
        die("Error in execution: " . $paring->error);
    }
}

function getUserDetails($yhendus, $kasutaja)
{
    $paring = $yhendus->prepare("SELECT * FROM osalejad WHERE kasutaja = ?");
    $paring->bind_param("s", $kasutaja);
    $paring->execute();
    $result = $paring->get_result();
    return $result->fetch_assoc();
}

function deleteUser($yhendus, $kasutaja)
{
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
    foreach ($_POST['esinemiskuupaev'] as $id => $esinemiskuupaev) {
        updateEsinemiskuupaev($yhendus, $id, $esinemiskuupaev);
    }
}

// Select * from osalejad
$result = $yhendus->query("SELECT * FROM osalejad");
$data = $result->fetch_all(MYSQLI_ASSOC);
$date = new DateTime('2024-01-01', new DateTimeZone('Pacific/Nauru'));
$date->setTimezone(new DateTimeZone('Pacific/Chatham'));
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

    <h2>LAULUPEO KUUPÄEVA LISAMINE</h2>

    <form action="?" method="post">
        <table border="1">
            <tr>
                <th>Eesnimi</th>
                <th>Perenimi</th>
                <th>Koorinimi</th>
                <th>Esinemiskuupäev</th>
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                <td><?php echo $row['eesnimi']; ?></td>
                <td><?php echo $row['perenimi']; ?></td>
                <td><?php echo $row['koorinimi']; ?></td>
                <td>
                    <?php
                    $currentYear = date('Y');
                    $currentDate = date('Y-m-d\TH:i', strtotime($currentYear . '-01-01'));
                    ?>
                    <input type="datetime-local" name="esinemiskuupaev[<?php echo $row['id']; ?>]" value="<?php echo date('Y-m-d\TH:i', strtotime($row['esinemiskuupaev'])); ?>" min="<?php echo $currentDate; ?>" required>
                </td>
            </tr>
            <?php endforeach; ?>
            
        </table>

        <input type="submit" value="Uuenda andmed">
    </form>

</body>

</html>
