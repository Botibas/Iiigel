<?php
    ob_start();
    session_start();
    $myPage = file_get_contents('../HTML/AdminInstitution.html');                                       // Einbettung von AdminInstitution.html in angezeigte
    include_once("database.php");                                                                       // Zugriff auf Funktionen von database.php
	include_once("Navigation.php");                                                                     // Zugriff auf Funktionen von Navigation.php
   
    $myUserID = $_SESSION['user'];                                                                      //ID vom eingeloggten user wird auf eine Variable gespeichert
    
	 // if session is not set this will redirect to login page
    if( !isset($_SESSION['user']) ) {                                                               
        header("Location: ../index.php");
        exit;
	 }

	if(!$ODB->isAdmin($_SESSION['user'])) {                                                             //Überprüft ob der eingeloggte User ein Admin ist
		 echo "Sie haben nicht die benötigte Berechtigung um diese Seite anzusehen.";                     //Wenn nicht: exit
        exit;
    } else {
	$myPage = str_replace('%Navigation%',getNavigation(),$myPage);                                       //Wenn ja: Einbettung von Navigation
    $toAdd = "";
    $myInstitution = $ODB->getAllInstitutions();
    for ($i=0; $i< sizeof($myInstitution);$i++){                                                         //Zwischenspeicherung von allen Institutionen mit ihren Informationen
        $myRow = file_get_contents('../HTML/AdminInstitutionTablerow.html');
        $search = array('%Institutionsname%','%InstitutionsID%');
        $replace = array($myInstitution[$i] ->getsName(),$myInstitution[$i]->getID());
        $myRow = str_replace($search,$replace,$myRow);
        
        
        $toAdd = $toAdd . $myRow;
       
       
    }


    $myPage = str_replace("%tablerow%",$toAdd,$myPage);                                                 //Einbettung von Institutionsdaten
    echo $myPage;                                                                                       //Seite wird angezeigt
	}
?>
