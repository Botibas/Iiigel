<?php 
	
    ob_start();
	session_start();
    include_once("database.php");                                                                       //Zugriff auf Funktionen von database.php
	include_once("Navigation.php");                                                                     //Zugriff auf Funktionen von Navigation.php
    $myPage = file_get_contents('../HTML/RightGroups.html');                                    //Einbettung von AdminGivePermission.html
			
	$toAdd="";

	$PermissionNames =$ODB->getPermissionNames();             

//Zwischenspeicherung von Permissionsnames
    


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

//---------------------------Erstellen der Rechte Gruppen Tabelle TV----------------------
    $zaehler = 0;
    
    $myRow = file_get_contents('../HTML/RightGroupsTablerow.html');

	for($zaehler = 0;$zaehler<=6 ;$zaehler++){   
        
        //Einsetzen der RightGroup Namen in die RightGroup Tabelle TV
        $search = array("%Name%");
        $replace = array($ODB->getPermissionGroupByID($zaehler));
        $myRow = str_replace($search,$replace,$myRow);
        
        
        
        //Einsetzen der ID in die RightGroup Tabelle TV
        $search = array("%canView%");
        $replace = array($ODB->RightGroupHasPermission($zaehler,"canView"));
        $myRow = str_replace($search,$replace,$myRow);
        
        $search = array("%canEdit%");
        $replace = array($ODB->RightGroupHasPermission($zaehler,"canEdit"));
        $myRow = str_replace($search,$replace,$myRow);
        
        $search = array("%canEditModul%");
        $replace = array($ODB->RightGroupHasPermission($zaehler,"canEditModul"));
        $myRow = str_replace($search,$replace,$myRow);
        
        $search = array("%canCreateModul%");
        $replace = array($ODB->RightGroupHasPermission($zaehler,"canCreateModul"));
        $myRow = str_replace($search,$replace,$myRow);
        
        $search = array("%canCreateGroup%");
        $replace = array($ODB->RightGroupHasPermission($zaehler,"canCreateGroup"));
        $myRow = str_replace($search,$replace,$myRow);
        
        $search = array("%ID%");
        $replace = array($ODB->GetIDofPermissionGroup($ODB->getPermissionGroupByID($zaehler)));
        $myRow = str_replace($search,$replace,$myRow);
        
        
        $toAdd = $toAdd . $myRow;
        
        
        $myRow = file_get_contents('../HTML/RightGroupsTablerow.html');
	}





	$myPage = str_replace("%PermissionTable%",$toAdd,$myPage);                                         //Einbettung von Permissiontabelle in die Seite
	$toAdd = "";
    
	echo $myPage;                                                                                       //Anzeigen der Seite zusammengefügten Seite
?>
