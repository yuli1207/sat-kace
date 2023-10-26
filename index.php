<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Jakarta');
require_once "/var/www/html/kace/koneksi.php";
$kd_cabang = mysqli_query($conn, "select concat(a.kd_cabang,' ',b.inisial) as kd_cabang from rekap_manage_office a left join mst_branch b on a.kd_cabang = b.kd_cabang order by a.kd_cabang asc");
$acv = mysqli_query($conn, "select ROUND((tot_ok/tot_div) * 100,0) as acv from rekap_manage_office order by kd_cabang asc");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <title>SAT KACE</title>
	<script src="js/Chart.js"></script>
    <link rel="stylesheet" href="/kace/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/kace/css/style.css">
    <link rel="shortcut icon" href="/kace/img/kace.ico">
</head>
<body class="">
    <div class="container-scroller">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item menu-items">
            <a class="nav-link" target="_blank" href="https://hokbox0902.sat.co.id/admin">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Hokbox0902</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" target="_blank" href="https://hokace1101.sat.co.id/admin">
              <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
              </span>
              <span class="menu-title">Hokace1101</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" target="_blank" href="https://hokace1102.sat.co.id/admin">
              <span class="menu-icon">
                <i class="mdi mdi-playlist-play"></i>
              </span>
              <span class="menu-title">Hokace1102</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" target="_blank" href="sign in.php?flag=0">
              <span class="menu-icon">
                <i class="mdi mdi-table-large"></i>
              </span>
              <span class="menu-title">KACE Master Store</span>
            </a>
          </li>
		  <li class="nav-item menu-items">
            <a class="nav-link" target="_blank" href="sign in.php?flag=2">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
              <span class="menu-title">KACE Mst Karyawan</span>
            </a>
          </li>
          <li class="nav-item menu-items">
           <a class="nav-link" target="_blank" href="sign in.php?flag=1">
              <span class="menu-icon">
                <i class="mdi mdi-chart-bar"></i>
              </span>
              <span class="menu-title">KACE Daily Report</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" target="_blank" href="sign in.php?flag=3">
              <span class="menu-icon">
                <i class="mdi mdi-security"></i>
              </span>
              <span class="menu-title">KACE Mst Software</span>
              <i class="menu-title"></i>
            </a>
          </li>
        </ul>
      </nav>
      <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
          <div class="content-wrapper"><center>NASIONAL OFFICE MANAGE KACE</center><br><br>            
		  <div class="container">
			 <canvas id="barchart" width="300" height="100"></canvas>
			  </div>
          </div>
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright &copy; 2022 - <?php echo date('Y'); ?> HO IT Operation</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><a href="https://www.alfamartku.com" target="_blank">Alfamartku</a></span>
            </div>
          </footer>
        </div>
      </div>
    </div>
</body>
</html>

<script  type="text/javascript">
  var ctx = document.getElementById("barchart").getContext("2d");
  var data = {
            labels: [<?php while ($p = mysqli_fetch_array($kd_cabang)) { echo "'" . $p['kd_cabang'] . "',";}?>],
            datasets: [
            {
              label: "ACV (%) ",              
			  data: [<?php while ($p = mysqli_fetch_array($acv)) { echo $p['acv'] . ",";} ?>],
              backgroundColor: ['#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733','#FF5733'],borderColor: ['#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700','#FFF700'],borderWidth: 3
            }
            ]
            };

  var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
            legend: {
              display: false
            },title: {
			  display: false,
			  text: ""
			},
            barValueSpacing: 40,
            scales: {
              yAxes: [{
                  ticks: {
                      min: 0,
                  }
              }],
              xAxes: [{
                          gridLines: {
                              color: "rgba(255, 0, 0, 0)",
                          }
                      }]
              }
          }
        });
		<?php
			mysqli_close($conn);
		?>
</script>