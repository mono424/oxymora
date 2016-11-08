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
$prep = $pdo->prepare("SELECT count(*) FROM (SELECT COUNT(*) FROM `".$table."` WHERE DATE(`time`) = CURDATE() GROUP BY `ip`) AS x");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$unique_visitor = $prep->fetchAll(PDO::FETCH_NUM)[0][0];

// GET CHART DATA
$prep = $pdo->prepare("SELECT count(*) as 'visits', DATE(`time`) as 'date' FROM `".$table."` GROUP BY `date` LIMIT 20");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$resVisits = $prep->fetchAll(PDO::FETCH_ASSOC);

$chartVerlauf['dates'] = [];
$chartVerlauf['visits'] = [];
$chartVerlauf['visitors'] = [];
foreach($resVisits as $chartItem){
  $chartVerlauf['dates'][] = $chartItem['date'];
  $chartVerlauf['visits'][] = $chartItem['visits'];
  //TODO: SHIT SOLUTION MAYBE OVERTHINK
  $prep = $pdo->prepare("SELECT count(*) FROM (SELECT count(*) FROM `".$table."` WHERE DATE(`time`)='".$chartItem['date']."' GROUP BY `ip`) AS x LIMIT 20");
  $success = $prep->execute();
  if(!$success){die('something went wrong!');}
  $chartVerlauf['visitors'][] = $prep->fetchAll(PDO::FETCH_NUM)[0][0];
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../../css/font-awesome.min.css">
  <link rel="stylesheet" href="../../css/content.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <button id="reload" onclick="location.reload();"><i class="fa fa-refresh" aria-hidden="true"></i></button>
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
        Heutige Besucher
      </div>
    </div>
  </div>

  <div class="ox-box">
    <h2>Letzte Tage</h2>
    <canvas id="verlaufChart" height="80"></canvas>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js" charset="utf-8"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js" charset="utf-8"></script>
  <script type="text/javascript">
  var verlaufDates = JSON.parse('<?php echo json_encode($chartVerlauf['dates']); ?>');
  var verlaufVisits = JSON.parse('<?php echo json_encode($chartVerlauf['visits']); ?>');
  var verlaufVisitors = JSON.parse('<?php echo json_encode($chartVerlauf['visitors']); ?>');

  verlaufDates = verlaufDates.map(changeDate);

  var ctx = document.getElementById("verlaufChart");
  var verlaufChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: verlaufDates,
      datasets: [
        {
          label: "Visits",
          fill: false,
          lineTension: 0.1,
          backgroundColor: "rgba(75,192,192,0.4)",
          borderColor: "rgba(75,192,192,1)",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "rgba(75,192,192,1)",
          pointBackgroundColor: "#fff",
          pointBorderWidth: 1,
          pointHoverRadius: 5,
          pointHoverBackgroundColor: "rgba(75,192,192,1)",
          pointHoverBorderColor: "rgba(220,220,220,1)",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          data: verlaufVisits,
          spanGaps: false,
        },
        {
          label: "Visitors",
          fill: false,
          lineTension: 0.1,
          backgroundColor: "rgba(193, 75, 163, 0.4)",
          borderColor: "rgba(193, 75, 163, 1)",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "rgba(193, 75, 163 ,1)",
          pointBackgroundColor: "#fff",
          pointBorderWidth: 1,
          pointHoverRadius: 5,
          pointHoverBackgroundColor: "rgba(193, 75, 163 ,1)",
          pointHoverBorderColor: "rgba(220,220,220,1)",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          data: verlaufVisitors,
          spanGaps: false,
        }
      ]
    },
    options: []
  });

  function changeDate(date){
    return Date.parse(date).toString("dd.MM");
  }
  </script>
</body>
</html>
