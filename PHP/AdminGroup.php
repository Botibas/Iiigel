<?php 
    include_once("database.php");
	include_once("Navigation.php");
    session_start();

    $myPage = file_get_contents('../HTML/AdminGroup.html');                                                         //HTML-Einbettung vom AdminGroup.html
  
    if(!$ODB->isAdmin($_SESSION['user'])and(!$ODB->isInstitutionsLeader($_SESSION['user']))) {                      //Admin und Institionleader Abfrage
        echo "Sie haben nicht die benötigte Berechtigung um diese Seite anzusehen.";                
        exit;
    } else {
        
        $myPage = str_replace('%Navigation%',getNavigation(),$myPage);                                              //Einbettung vom Navigation.php über getNavigation
        $toAdd = "";
        $myGroups = $ODB->getGroupsFromInstitution($_GET['InstitutionsID']);                                        //Zwischenspeicherung von Gruppen
        
        for ($i=0; $i< sizeof($myGroups);$i++){                                                                     
            $myRow = file_get_contents('../HTML/AdminGroupTableRow.html');                                          //HTML-Einbettung vom AdminGroupTableRow.html
            $myTrainer = $ODB->getTrainerofGroup($myGroups[$i]->getID());                                           //Zwischenspeicherung von Trainer der Gruppen
            $search = array('%Gruppenbezeichnung%','%Trainer%','%GroupID%' );                                       //Markierung von Gruppenbezeichnung, Trainer und GroupId aus einer AdminGroup.html
            
            if ($myTrainer!=false) {                                                                                //Wenn Trainer
                $replace = array($myGroups[$i] ->getsName(),$myTrainer->getsFullName(),$myGroups[$i]->getID());     //Füg alles aus der DB in die Markierung ein
            } else {                                                                                                //Wenn nicht Trainer
                $replace = array($myGroups[$i] ->getsName(),"-",$myGroups[$i]->getID());                            //Füg alles aus der DB außer den Trainernamen in die Markierung ein
            }

            $myRow = str_replace($search,$replace,$myRow);                                                          //Zwischenspeicherung der Liste
            $toAdd = $toAdd . $myRow;
        }

        $add="";
        $myModules = $ODB->getAllModules();                                                                         //Zwischenspeicherung aller Module
        for ($a=0;$a<sizeof($myModules);$a++){                                                                      //Auflistung aller Module mit Namen und ID
            $myRow = file_get_contents('../HTML/AdminGroupListItem.html');
            $search = array('%Modulname%','%ModulID%');
            $replace = array($myModules[$a] ->getsName(),$myModules[$a]->getID());   
            $myRow = str_replace($search,$replace,$myRow);


            $add = $add . $myRow;
        }
        
        $Uadd="";
        $myRow = file_get_contents('../HTML/AdminUserListItem.html');
        $search = array('%Username%','%UserID%');
        $replace = array("Ohne Trainer",0);   
        $myRow = str_replace($search,$replace,$myRow);
        $Uadd = $Uadd . $myRow;
        
        $myUsers = $ODB->getAllUsers();                                           //Zwischenspeicherung aller Nutzer einer Institution
        for ($a=0;$a<sizeof($myUsers);$a++){                                                                         //Auflistung aller Nutzer mit Namen und ID
            $myRow = file_get_contents('../HTML/AdminUserListItem.html');
            $search = array('%Username%','%UserID%');
            $replace = array($myUsers[$a] ->getsUserName(),$myUsers[$a]->getID());   
            $myRow = str_replace($search,$replace,$myRow);


            $Uadd = $Uadd . $myRow;
        }
        
        $myPage = str_replace("%InstitutionID%",$_GET['InstitutionsID'],$myPage);                                    //Hinzufügen von InstitutionID zur Seite
        $myPage = str_replace("%tablerow%",$toAdd,$myPage);                                                          //Hinzufügen von Gruppenliste zur Seite
        $myPage = str_replace("%Listitems%",$add,$myPage);                                                           //Hinzufügen von Modulliste zur Seite
        $myPage = str_replace("%UListitems%",$Uadd,$myPage);                                                         //Hinzufügen von Nutzerliste zur Seite
        echo $myPage;                                                                                                //Anzeigen der ganzen Seite
	}
?>
