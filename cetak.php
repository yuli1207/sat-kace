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
include('koneksi.php');

function filterData(&$str)
{ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 

$fields = array('BRANCH CODE','BRANCH NAME','NIK', 'NAMA', 'JABATAN', 'DEPARTMENT', 'DIVISI', 'ACTIVE/ASSET', 'REASON');
$excelData = implode("\t", array_values($fields)) . "\n";

if ($_SESSION['kd_dc']=="Z001")
{
	if ($_SESSION["jabatan"]=="It Operation Controller")
	{
		$query = mysqli_query($conn,"SELECT kd_dc,nm_dc,nik,nama,jabatan,dept,divisi,active,reason FROM rekap_karyawan WHERE kd_dc<>'" . $_SESSION["kd_dc"] . "' ORDER BY kd_dc,active,divisi ASC");
		$fileName = "All Branch KACE Mst Karyawan " . date('d-m-y') . ".xlsx";
	}
	elseif ($_SESSION["jabatan"]=="It Roll Out Manager" || $_SESSION["jabatan"]=="It Roll Out Specialist")
	{
		$query = mysqli_query($conn,"SELECT kd_dc,nm_dc,nik,nama,jabatan,dept,divisi,active,reason FROM rekap_karyawan ORDER BY kd_dc,active,divisi ASC"); 
		$fileName = "NAS KACE Mst Karyawan " . date('d-m-y') . ".xlsx";
	}
	else
	{
		$query = mysqli_query($conn,"SELECT kd_dc,nm_dc,nik,nama,jabatan,dept,divisi,active,reason FROM rekap_karyawan WHERE kd_dc='" . $_SESSION["kd_dc"] . "' ORDER BY active,divisi ASC");
		$fileName = $_SESSION["kd_dc"] . " KACE Mst Karyawan " . date('d-m-y') . ".xls";
	}
}
else
{
	$query = mysqli_query($conn,"SELECT kd_dc,nm_dc,nik,nama,jabatan,dept,divisi,active,reason FROM rekap_karyawan WHERE kd_dc='" . $_SESSION["kd_dc"] . "' ORDER BY active,divisi ASC");
	$fileName = $_SESSION["kd_dc"] . " KACE Mst Karyawan " . date('d-m-y') . ".xls";
}

if($query->num_rows > 0)
{ 
	while($row = $query->fetch_assoc())
	{ 		
		$lineData = array($row['kd_dc'], $row['nm_dc'], "'" . $row['nik'], $row['nama'], $row['jabatan'], $row['dept'], $row['divisi'], $row['active'], $row['reason']); 
		array_walk($lineData, 'filterData'); 
		$excelData .= implode("\t", array_values($lineData)) . "\n"; 
	} 
}
else
{ 
	$excelData .= 'No records found...'. "\n"; 
}
 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
header("Pragma: no-cache");  
header("Expires: 0");  

echo $excelData; 
mysqli_close($conn);
exit();

?>