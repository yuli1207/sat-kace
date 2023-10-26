<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

session_start();

if(!isset($_SESSION["kd_dc"]))
{
	session_destroy();
	header("location: sign in.php?flag=2");
	exit();
}
date_default_timezone_set('Asia/Jakarta');
$timeout = 60; 
$timeout = $timeout * 60;
if(isset($_SESSION['start_session']))
{
	$elapsed_time = time()-$_SESSION['start_session'];
	if($elapsed_time >= $timeout)
	{
		session_destroy();
		header("location: sign out.php?flag=2");
		exit();
	}
}

$_SESSION['start_session']=time();
	
?>

<!doctype html>
<html lang="en">
	<head>
		<title>KACE Master Karyawan</title>
		<meta charset="utf-8">
		<meta http-equiv="refresh" content="3605"; name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="shortcut icon" href="/kace/img/kace.ico"/>
				
		<style>
		#myTable tr:nth-child(even){background-color: #DCEABA;}
		#myTable tr:hover {background-color: #010101;color: #FFFFFF;}
		#myTable th 
		{
		  padding-top: 8px;
		  padding-bottom: 8px;
		  text-align: center;
		  background-color: #F57F17;
		  color: white;
		  font-size: 14px;
		}
		
		.modal .modal-dialog 
		{
			max-width: 400px;
		}
		.modal .modal-body 
		{
			max-height: 80vh;
			overflow-y: auto;
			font-size: 12px;
		}
		.modal .modal-header, .modal .modal-body, .modal .modal-footer
		{
			padding: 20px 20px;
		}
		.modal .modal-content 
		{
			border-radius: 3px;
			font-size: 12px;
		}
		
		</style>
		<script>
		
		</script>
	</head>

	<body style="background-color:#585454">
		<div class="container" style="margin-top: 20px">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header" style="text-align:right">
							<span style="float:left"><h5><?php echo $_SESSION["kd_dc"] . " " . strtoupper($_SESSION["nm_dc"]); ?></h5></span>
							<i class="fa fa-user icon"></i>&nbsp;&nbsp;&nbsp;<?php echo $_SESSION['nama']; echo str_repeat("&nbsp;", 5); echo "|"; echo str_repeat("&nbsp;", 5); ?><a href="sign out.php?flag=2" style="color:black">Sign Out</a>
						</div>
						<div class="card-body">
							<a href="cetak.php" class="btn btn-md btn-success" style="margin-bottom: 18px"><i class="fa fa-file-excel-o" style="font-size:20px;color:white"></i>&ensp;Excel</a>
							<table class="table table-bordered" id="myTable" align="center">
								<thead>
									<tr>
										<th scope="col">NO</th>
										<th scope="col">NIK</th>
										<th scope="col">NAMA</th>
										<th scope="col">JABATAN</th>
										<th scope="col">DEPARTMENT</th>
										<th scope="col">DIVISI</th>
										<th scope="col">ACTIVE/ASSET</th>
										<th scope="col">ACTION</th>
									</tr>
								</thead>
								<tbody>
								<?php 
					  $no = 1;
                      include('koneksi.php');
					  if ($_SESSION['kd_dc']=="Z001")
						{
							if ($_SESSION["jabatan"]=="It Operation Controller")
							{
								$query = mysqli_query($conn,"SELECT nik,nama,jabatan,dept,divisi,active,reason FROM rekap_karyawan WHERE kd_dc<>'" . $_SESSION["kd_dc"] . "' ORDER BY kd_dc,active,divisi ASC");
							}
							elseif ($_SESSION["jabatan"]=="It Roll Out Manager" || $_SESSION["jabatan"]=="It Roll Out Specialist")
							{
								$query = mysqli_query($conn,"SELECT nik,nama,jabatan,dept,divisi,active,reason FROM rekap_karyawan ORDER BY kd_dc,active,divisi ASC");
							}
							else
							{
								$query = mysqli_query($conn,"SELECT nik,nama,jabatan,dept,divisi,active,reason FROM rekap_karyawan WHERE kd_dc='" . $_SESSION["kd_dc"] . "' ORDER BY active,divisi ASC");
							}
						}
						else
						{
							$query = mysqli_query($conn,"SELECT nik,nama,jabatan,dept,divisi,active,reason FROM rekap_karyawan WHERE kd_dc='" . $_SESSION["kd_dc"] . "' ORDER BY active,divisi ASC");
						}
                      while($row = mysqli_fetch_array($query))
					  {
					?>

                  <tr style="font-size: 13px">
                      <td><center><?php echo $no++ ?></center></td>
                      <td><center><?php echo $row['nik'] ?></center></td>
                      <td><?php echo $row['nama'] ?></td>
                      <td><?php echo $row['jabatan'] ?></td>
					  <td><?php echo $row['dept'] ?></td>
                      <td><?php echo $row['divisi'] ?></td>
					  <td><center><?php if($row['active']=='Y')
					  {
						  ?><img src="/kace/img/ok.png" alt="ok" height="20" width="30" /><?php ;
						  
					  }
					   elseif($row['active']=='N')
					  {
						   ?><img src="/kace/img/nok.png" alt="nok" height="20" width="20" /><?php ;
					  } 
					  elseif($row['active']=='T')
					  {
						   ?><img src="/kace/img/not_valid.png" alt="not_valid" height="25" width="25" /><?php ;
					  }
					  else
					  {
						  
					  }
					  
					  ?></center></td>
                      <td><center>
					  <a data-toggle="modal" data-nikkar="<?php echo $row['nik'] ?>" data-nama="<?php echo $row['nama'] ?>" data-jabatan="<?php echo $row['jabatan'] ?>" data-dept="<?php echo $row['dept'] ?>" data-divisi="<?php echo $row['divisi'] ?>" data-active="<?php echo $row['active'] ?>" data-reason="<?php echo $row['reason'] ?>" data-kd_dc="<?php echo $_SESSION['kd_dc'] ?>" data-nik="<?php echo $_SESSION['nik'] ?>" title="Edit" class="open-editmststore" href="#<?php if($_SESSION["kd_dc"] == "Z001"){ if($_SESSION["jabatan"] == "Office Support" || $_SESSION["jabatan"] == "Dms Technician & Engineer" || $_SESSION["jabatan"] == "Ho It Operation Manager") { ?>editmststore <?php ;} else { ?>myModal <?php ;}} else { ?>editmststore <?php ;} ?>"><img src="/kace/img/edit.png" alt="edit" height="25" width="25" title="Edit"/></a>					  
					  </center></td>
                  </tr>

                <?php }; mysqli_close($conn); ?>
                </tbody>
              </table>
			</div>
          </div>
      </div>
    </div>
</div><br><center><font color="white">Copyright &copy; 2022 - <?php echo date('Y'); ?> HO IT Operation</font></center><br>

<div class="modal fade" id="editmststore">
 	<div class="modal-dialog">
	 <div class="modal-content">
			<form class="form-horizontal" method="POST" action="update2.php">
				<div class="modal-body">					
					<div class="form-group">
						<label>NIK</label>
						<input type="text" class="form-control" id="nikkar" name="nikkar" readonly value="" required>
					</div>
					<div class="form-group">
						<label>NAMA</label>
						<input type="text" class="form-control" id="nama" name="nama" readonly value="" required>
					</div>
					<div class="form-group">
						<label>JABATAN</label>
						<input type="text" class="form-control" id="jabatan" name="jabatan" readonly value="" required>
					</div>
					<div class="form-group">
						<label>DEPARTMENT</label>
						<input type="text" class="form-control" id="dept" name="dept" readonly value="" required>
					</div>
					<div class="form-group">
						<label>DIVISI</label>
						<input type="text" class="form-control" id="divisi" name="divisi" readonly value="" required>
					</div>
					<div class="form-group">
						<label>ACTIVE / ASSET</label>
						<input type="checkbox" class="form-control" id="active" name="active" value="Y">
					</div>
					<div class="form-group">
						<label>REASON</label>
						<select class="form-control" id="reason" name="reason">
						<option value="Tidak memiliki asset">Tidak memiliki asset</option>
						<option value="Memakai asset pribadi">Memakai asset pribadi</option>
						<option value="User di cabang lain">User di cabang lain</option>
						<option value="Bekerja di luar kantor">Bekerja di luar kantor</option>
						<option value="OS tidak support KACE">OS tidak support KACE</option>
						<option value="BA tidak instal">BA tidak instal</option>						
						<option value="Cuti hamil">Cuti hamil</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" class="form-control" id="kd_dc" name="kd_dc" value="">
						<input type="hidden" class="form-control" id="nik" name="nik" value="">
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-danger" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-warning" value="Update">
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal"">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-body">
          <h6>Access denied</h6>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
      $(document).ready( function () 
	  {
          $('#myTable').DataTable();
		  $(document).on("click", ".open-editmststore", function () 
		{
			var nikkar = $(this).data('nikkar');
			$(".modal-body #nikkar").val( nikkar );

			var nama = $(this).data('nama');
			$(".modal-body #nama").val( nama );
			 
			var jabatan = $(this).data('jabatan');
			$(".modal-body #jabatan").val( jabatan );
				 
			var dept = $(this).data('dept');
			$(".modal-body #dept").val( dept );
			 
			var divisi = $(this).data('divisi');
			$(".modal-body #divisi").val( divisi );
			 
			var active = $(this).data('active');
			$(".modal-body #active").prop('checked', active == 'Y');
			
			var reason = $(this).data('reason');
			$(".modal-body #reason").val( reason );
			
			var kd_dc = $(this).data('kd_dc');
			$(".modal-body #kd_dc").val( kd_dc );
			
			var nik = $(this).data('nik');
			$(".modal-body #nik").val( nik );
		});
      });
		 
    </script>				
  </body>
</html>