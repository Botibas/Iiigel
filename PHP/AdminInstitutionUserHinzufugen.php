<?php 
    include_once("database.php");
session_start();
	if(!$ODB->isAdmin($_SESSION['user'])and(!$ODB->isInstitutionsLeader($_SESSION['user']))) {
		 echo "Sie haben nicht die benötigte Berechtigung um diese Seite anzusehen.";
        exit;
    } else {
	$myGroup = $ODB->addUsertoInstitution($_POST['UserID'],$_POST['InstitutionsID']);
    header("Location: AdminInstitutionDetailView.php?InstitutionsID=".$_POST['InstitutionsID']);
	}
?>
