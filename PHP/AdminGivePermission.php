<?php 
	
    ob_start();
	session_start();
    include_once("database.php");                                                                       //Zugriff auf Funktionen von database.php
	include_once("Navigation.php");                                                                     //Zugriff auf Funktionen von Navigation.php
    $myPage = file_get_contents('../HTML/AdminGivePermission.html');                                    //Einbettung von AdminGivePermission.html
			
	$toAdd="";

    //AL erstellt den 'Dieses Kapitel bearbeiten' Button
    $search = array('%RightGroupsButton%');
    $replace = array(file_get_contents('../HTML/RightGroupsButton.html'));
    $myPage = str_replace($search,$replace,$myPage);

    if ( isset($_POST['RightButton'])){//AL Wenn der Trainer auf den Button für das bearbeiten dieses Kapitels drückt    
        header("Location: ../PHP/RightGroups.php");
    }

	$PermissionNames =$ODB->getPermissionNames();                                                       //Zwischenspeicherung von Permissionsnames
   
	while($row = $PermissionNames->fetch_array())
    {
        $allPermissionNames[] = $row;
    }

	if (isset($_GET["activeTab"])) {
		$activeTab = $_GET["activeTab"];
        //AL: Mein Penis ist Groß
    }

    $zaehler;

	for($zaehler=0;$zaehler<=6;$zaehler++) {                                                              //Einbettung von Permissionnames
		if(!isset($activeTab)){
			$activeTab= $row['Name'];
		}
        
		if($activeTab == $row['Name']){
			$myRow = "<li role='presentation' class='active'><a href='./AdminGivePermission.php?activeTab=%Name%'>%Name%</a></li>";
		}else {
			$myRow = "<li role='presentation'><a href='./AdminGivePermission.php?activeTab=%Name%'>%Name%</a></li>";
		}
        $search = array("%Name%");
        $replace = array($ODB->getPermissionGroupByID($zaehler));
        $myRow = str_replace($search,$replace,$myRow);
        
        
        $toAdd = $toAdd . $myRow;
			
	}	

	$myPage = str_replace("%PermissionNameList%",$toAdd,$myPage);                                       //Einbettung von Permissionnames in die Seite
		
	$toAdd = "";
	$permission = $ODB->getPermissionsFromName($activeTab); 
   
	$myPage = str_replace('%Navigation%',getNavigation(),$myPage);                                      //Einbettung von Navigation in die Seite

	//while(($permissionRow = mysqli_fetch_array($permission))!=null){                                    //Zwischenspeicherung von Permissiontabelle in die Seite
    $AllUsersFromPermission = $ODB->getUsersFromPermission($activeTab);
	for($i=0;$i< sizeof($AllUsersFromPermission);$i++){	
		$myRow = file_get_contents('../HTML/AdminGivePermissionTablerow.html');
        $search = array("%Prename%","%Lastname%","%Username%","%Rechtegruppe%");
        $replace = array($AllUsersFromPermission[$i]->getsFirstName(),$AllUsersFromPermission[$i]->getsLastName(),$AllUsersFromPermission[$i]->getsUserName(),$activeTab);
        $myRow = str_replace($search,$replace,$myRow);
        
        $toAdd = $toAdd . $myRow;
	}

	$myPage = str_replace("%PermissionTable%",$toAdd,$myPage);                                         //Einbettung von Permissiontabelle in die Seite
	
	$allUsers = $ODB->getAllUsers();
	$toAdd = "";
	for ($i=0;$i<sizeof($allUsers);$i++){                                                               //Zwischenspeicherung von Optionen in einer Selectliste
		$toAdd .= "<option value=".$allUsers[$i]->getID().">".$allUsers[$i]->getsFirstName()." ".$allUsers[$i]->getsLastName()."</option>";
	}
	$myPage = str_replace("%AllUserDropdownData%",$toAdd,$myPage);                                      //Einbettung von Optionen in einer Selectliste
	
	echo $myPage;                                                                                       //Anzeigen der Seite zusammengefügten Seite
?>
