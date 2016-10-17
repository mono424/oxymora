<?php
use KFall\oxymora\database\DB;

$table = "statistics_visits";


$pdo = DB::pdo();
// GET LATEST VISITORS
$prep = $pdo->prepare("SELECT *, COUNT(*) FROM `".$table."` GROUP BY `ip` ORDER BY `time` DESC LIMIT 5");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$last_visitors = $prep->fetchAll(PDO::FETCH_ASSOC);

// GET LATEST OPENDED PAGES
$prep = $pdo->prepare("SELECT * FROM `".$table."` ORDER BY `time` DESC LIMIT 5");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$last_sites = $prep->fetchAll(PDO::FETCH_ASSOC);

// GET NUMBER OF VISITS
$prep = $pdo->prepare("SELECT COUNT(*) FROM `".$table."`");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$total_visits = $prep->fetchAll(PDO::FETCH_NUM)[0][0];

// GET NUMBER OF VISITS TODAY
$prep = $pdo->prepare("SELECT COUNT(*) FROM `".$table."` WHERE DATE(`time`) = CURDATE()");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$today_visits = $prep->fetchAll(PDO::FETCH_NUM)[0][0];

// GET NUMBER UNIQUE VISITORS
$prep = $pdo->prepare("SELECT count(*) FROM (SELECT COUNT(*) FROM `".$table."` GROUP BY `ip`) AS x");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$unique_visitor = $prep->fetchAll(PDO::FETCH_NUM)[0][0];

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../css/content.css">
  <style media="screen">
  <?php echo file_get_contents('css/style.css'); ?>
  </style>
</head>
<body>
  <div class="ox-trible-container">
    <div class="ox-box">
      <div class="bigNumber">
        <?php echo $total_visits; ?>
      </div>
      <div class="bigNumberLabel">
        Gesamt Seitenaufrufe
      </div>
    </div>
    <div class="ox-box">
      <div class="bigNumber">
        <?php echo $today_visits; ?>
      </div>
      <div class="bigNumberLabel">
        Heutige Seitenaufrufe
      </div>
    </div>
    <div class="ox-box">
      <div class="bigNumber">
        <?php echo $unique_visitor; ?>
      </div>
      <div class="bigNumberLabel">
        Besucher
      </div>
    </div>
  </div>


  <div class="ox-box">
    <h2>Letzte Besucher</h2>
    <table>
      <thead>
        <th>IP</th>
        <th>Seitenaufrufe</th>
        <th>Browser</th>
        <th>Time</th>
      </thead>
      <tbody>
        <?php
        foreach($last_visitors as $bes){
          ?>
          <tr>
            <td><?php echo $bes['ip']; ?></td>
            <td><?php echo $bes['COUNT(*)']; ?></td>
            <td><?php echo $bes['browser']; ?></td>
            <td><?php echo $bes['time']; ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="ox-box">
    <h2>Zuletzt Besuchte Seiten</h2>
    <table>
      <thead>
        <th>Seite</th>
        <th>IP</th>
        <th>Browser</th>
        <th>Time</th>
      </thead>
      <tbody>
        <?php
        foreach($last_sites as $site){
          ?>
          <tr>
            <td><?php echo $site['page']; ?></td>
            <td><?php echo $site['ip']; ?></td>
            <td><?php echo $site['browser']; ?></td>
            <td><?php echo $site['time']; ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>


</body>
</html>
