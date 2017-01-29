<?php
use KFall\oxymora\database\DB;

$table = "statistics_visits";


$pdo = DB::pdo();

// GET CHART DATA
$prep = $pdo->prepare("SELECT count(*) as 'visits', DATE(`time`) as 'date' FROM `".$table."` GROUP BY `date` ORDER BY `date` DESC LIMIT 4");
$success = $prep->execute();
if(!$success){die('something went wrong!');}
$resVisits = $prep->fetchAll(PDO::FETCH_ASSOC);
$resVisits = array_reverse($resVisits);

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

  <div class="widget">
    <h2>Statistik - Letzte Tage</h2>
    <canvas id="verlaufChart" height="220"></canvas>
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
