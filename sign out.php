<?php 
 
session_start();
unset($_SESSION["nik"]);
unset($_SESSION["kd_dc"]);
unset($_SESSION["nm_dc"]);
unset($_SESSION["nama"]);
unset($_SESSION["jabatan"]);
unset($_SESSION["start_session"]);
session_destroy();
if($_GET['flag']==0)
{
	header("Location: sign in.php?flag=0");
}
elseif($_GET['flag']==1)
{
	header("Location: sign in.php?flag=1");
}
elseif($_GET['flag']==2)
{
	header("Location: sign in.php?flag=2");
}
else
{
	header("Location: sign in.php?flag=3");
}
exit();

?>