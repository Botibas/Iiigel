<?php 
	
    ob_start();
	session_start();
    include_once("database.php");                                                                       //Zugriff auf Funktionen von database.php
	include_once("Navigation.php");                                                                     //Zugriff auf Funktionen von Navigation.php
    $myPage = file_get_contents('../HTML/AdminGivePermission.html');                                    //Einbettung von AdminGivePermission.html
			
	$toAdd="";

	$PermissionNames =$ODB->getPermissionNames();                                                       //Zwischenspeicherung von Permissionsnames
   
	while($row = $PermissionNames->fetch_array())
    {
        $allPermissionNames[] = $row;
    }

	if (isset($_GET["activeTab"])) {
		$activeTab = $_GET["activeTab"];
        //AL: Mein Penis ist Groß
    }

	foreach($allPermissionNames as $row) {                                                              //Einbettung von Permissionnames
		if(!isset($activeTab)){
			$activeTab= $row['Name'];
		}
        
		if($activeTab == $row['Name']){
			$myRow = "<li role='presentation' class='active'><a href='./AdminGivePermission.php?activeTab=%Name%'>%Name%</a></li>";
		}else {
			$myRow = "<li role='presentation'><a href='./AdminGivePermission.php?activeTab=%Name%'>%Name%</a></li>";
		}
        $search = array("%Name%");
        $replace = array($row['Name']);
        $myRow = str_replace($search,$replace,$myRow);
        
        
        $toAdd = $toAdd . $myRow;
			
	}	

	$myPage = str_replace("%PermissionNameList%",$toAdd,$myPage);                                       //Einbettung von Permissionnames in die Seite
		
	$toAdd = "";
	$permission = $ODB->getPermissionsFromName($activeTab);      
	$myPage = str_replace('%Navigation%',getNavigation(),$myPage);                                      //Einbettung von Navigation in die Seite

	while(($permissionRow = mysqli_fetch_array($permission))!=null){                                    //Zwischenspeicherung von Permissiontabelle in die Seite
		
		$myRow = file_get_contents('../HTML/AdminGivePermissionTablerow.html');
       	$currentUser = $ODB->getUserFromID($permissionRow['UserID']);
        $search = array("%Prename%","%Lastname%","%id%","%CanView%","%CanEdit%","%CanAdd%","%CanDelete%","%UserId%","%Permission%");
        $replace = array($currentUser->getsFirstName(),$currentUser->getsLastName(),$permissionRow["pID"],$permissionRow["canView"], $permissionRow["canEdit"],$permissionRow["canCreate"],$permissionRow["canDelete"],$permissionRow["UserID"],$activeTab);
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
