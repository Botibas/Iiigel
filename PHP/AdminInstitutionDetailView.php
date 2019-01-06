<?php 
    include_once("database.php");
	include_once("Navigation.php");
    session_start();

    $myPage = file_get_contents('../HTML/InstitutionDetailView.html');
  
    $myRow = file_get_contents('../HTML/InstitutionDetailView.html');
    $search = array('%Institutionsname%','%InstitutionsID%');
    $replace = array($ODB->getInstitutionFromID($_GET['InstitutionsID'])->getsName(),$_GET['InstitutionsID']) ;
    $myPage = str_replace($search,$replace,$myRow);
  

	if((!$ODB->isAdmin($_SESSION['user']))and(!$ODB->isInstitutionsLeader($_SESSION['user']))) {//AL Nur Admins und Institutionsleiter dürfen die Seite sehen
		 echo "Sie haben nicht die benötigte Berechtigung um diese Seite anzusehen.";
        exit;
    } else {
	
	$myPage = str_replace('%Navigation%',getNavigation(),$myPage);
     $toAdd = "";
    $myUsers = $ODB->getUsersFromInstitution($_GET['InstitutionsID']);
    for ($i=0; $i< sizeof($myUsers);$i++){   //AL Gibt alle Nutzer einer Institution aus
        $myRow = file_get_contents('../HTML/InstitutionDetailViewTablerow.html');
        $search = array('%Vorname%','%Nachname%','%Username%','%Email%');
        $replace = array($myUsers[$i] ->getsFirstName(),$myUsers[$i]->getsLastName(),$myUsers[$i]->getsUsername(),$myUsers[$i]->getsEMail());
        $myRow = str_replace($search,$replace,$myRow);
        
        
        $toAdd = $toAdd . $myRow;
       
       
    }
    
    $add = '';
    $myUsers= $ODB->GetAllUsersNotInInstitution($_GET['InstitutionsID']);

	for ($a=0;$a<sizeof($myUsers);$a++){//AL Macht eine Liste der Leute, welche nicht in der Institution sind, falls man sie hinzufügen will.
		$myRow = file_get_contents('../HTML/AdminGroupUserListitem.html');
      
        $search = array('%Vorname%','%Nachname%','%UserID%');
		$replace = array($myUsers[$a]->getsFirstName(),$myUsers[$a]->getsLastName(),$myUsers[$a]->getID());  
        $myRow = str_replace($search,$replace,$myRow);
        $add = $add . $myRow;
       
	}
        
    $linksAdded ='';
    $links= $ODB->getAllLinksFromInstitution(intval($_GET['InstitutionsID']));
	for ($a=0;$a<sizeof($links);$a++){//AL Gibt alle Einladungslinks in die Institution aus.
		$myRow = file_get_contents('../HTML/AdminInstitutionLinkItem.html');
      
        $search = array('%LinkString%','%startDate%','%endDate%');
		$replace = array($links[$a]->getLink(),$links[$a]->getStartDatum(),$links[$a]->getEndDatum());  
        $myRow = str_replace($search,$replace,$myRow);
        $linksAdded = $linksAdded . $myRow;
       
	}
        
    if (isset($_POST['addLink'])){//AL Wenn man den Button für das erstellen eines Buttons gedrückt hat
        $ODB->addInstitutionInvitationLink($_POST['link'],$_GET['InstitutionsID'],$_POST['startdate'],$_POST['enddate']);
        header("Location: ../PHP/AdminInstitutionDetailView.php?InstitutionsID=".$_GET['InstitutionsID']);
        exit;
    }

    $myPage = str_replace("%Listitems%",$add,$myPage);   
    $myPage = str_replace("%linkrow%",$linksAdded,$myPage);
    $myPage = str_replace("%tablerow%",$toAdd,$myPage);     
    echo $myPage;
	}
?>
