<?php
    ob_start();
    session_start();
    $myPage = file_get_contents('../HTML/userOverview.html');
    include_once("database.php");
    include_once("Model/User.php");
	include_once("Navigation.php");


    // if session is not set this will redirect to login page
    if( !isset($_SESSION['user']) ) {
        header("Location: ../index.php");
        exit;
    }

    //TV User Daten werden herausgesucht
    $myUser = $ODB->getUserFromID($_SESSION['user']); 
    $myGroups = $ODB->getGroupsFromUserID($_SESSION['user']);
    $search = array('%Vorname%', '%Nachname%', '%UserName%', '%EMail%','%Rechte%','%ProfilePicture%','%userID%');
    $replace = array($myUser->getsFirstName(), $myUser->getsLastName(), $myUser->getsUsername(), $myUser->getsEMail(),$ODB->getPermissionsFromUserID($myUser->getID()), $ODB->getProfilePicFromID($myUser->getID()), $myUser->getID() );
    $myPage = str_replace($search,$replace,$myPage);
	$myPage = str_replace('%Navigation%',getNavigation(false),$myPage);
	
	
    $myTrainer = [];
    $toAdd = "";
    //TV Anlegen der Modulboxen
    for ($i=0; $i< sizeof($myGroups);$i++){ 
        $myTrainer = $myGroups[$i]->getTrainer();
        $oneTrainer = reset($myTrainer);
        if ($ODB->isTrainerofGroup($_SESSION['user'], $myGroups[$i]->getID())) {
            $link = "trainerModulview.php?groupID=".$myGroups[$i]->getID(); 
            $myBox = file_get_contents('../HTML/groupBoxTrainer.html');
            if (sizeof($ODB->getModuleFromID($myGroups[$i]->getModulID())->chapter)!=0){
                $Progress=($myGroups[$i]->getAverageProgressFromGroup())/sizeof($ODB->getModuleFromID($myGroups[$i]->getModulID())->chapter);
                $ProgressPercent= 100*(($myGroups[$i]->getAverageProgressFromGroup())/sizeof($ODB->getModuleFromID($myGroups[$i]->getModulID())->chapter));
            } else {
                $Progress = 0;
                $ProgressPercent = 0;
            }
        } else {
            $link = "ChapterView.php?moduleID=" . $myGroups[$i]->getModulID() . "&chapterID=" . $myGroups[$i]->getProgressFromUserID($_SESSION['user'])."&groupID=". $myGroups[$i]->getID() ;
            $myBox = file_get_contents('../HTML/groupBoxTN.html');
            if (sizeof($ODB->getModuleFromID($myGroups[$i]->getModulID())->chapter)!=0){
                $Progress=$myGroups[$i]->getProgressFromUserID($_SESSION['user'])+1;
                $ProgressPercent=(100*($myGroups[$i]->getProgressFromUserID($_SESSION['user'])+1)/(sizeof ($ODB->getModuleFromID($myGroups[$i]->getModulID()) -> chapter)));
            } else {
                $Progress = 0;
                $ProgressPercent = 0;    
            }
        }
            if ($oneTrainer != null){
                $search = array('%Name%', '%Institution%', '%Trainer%', '%Progress%', '%ProgressPercent%','%ModuleLink%','%ModulePicture%', '%ID%');
                $replace = array($myGroups[$i]->getsName(), $ODB->getInstitutionFromID($myGroups[$i]-> getInstitutionsID())->getsName(),$oneTrainer->getsFirstName()." ". $oneTrainer->getsLastName(),$Progress,$ProgressPercent, $link,$ODB->getModuleImageFromID($myGroups[$i]->getModulID()), "Modul".$i);
                $myBox = str_replace($search,$replace,$myBox);
            } else {
               $search = array('%Name%', '%Institution%', '%Trainer%', '%Progress%', '%ProgressPercent%','%ModuleLink%','%ModulePicture%', '%ID%');
                $replace = array($myGroups[$i]->getsName(), $ODB->getInstitutionFromID($myGroups[$i]-> getInstitutionsID())->getsName()," ",$Progress,$ProgressPercent, $link,$ODB->getModuleImageFromID($myGroups[$i]->getModulID()), "Modul".$i);
                $myBox = str_replace($search,$replace,$myBox); 
            }
        $toAdd = $toAdd . $myBox;
    }

//-------------------------------------------------------------------------------------------------------------------------------------------
    
        $InstitutionsID = 1;
        $add1 = "";
            //$myGroups = $ODB->getGroupsFromInstitution($_GET['InstitutionsID']);                                        //Zwischenspeicherung von Gruppen
            $myGroups = $ODB->getGroupsFromInstitution($InstitutionsID);
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
                $add1 = $add1 . $myRow;
            }


            $add2="";
            $myModules = $ODB->getAllModules();                                                                         //Zwischenspeicherung aller Module
            for ($a=0;$a<sizeof($myModules);$a++){                                                                      //Auflistung aller Module mit Namen und ID
                $myRow = file_get_contents('../HTML/AdminGroupListItem.html');
                $search = array('%Modulname%','%ModulID%');
                $replace = array($myModules[$a] ->getsName(),$myModules[$a]->getID());   
                $myRow = str_replace($search,$replace,$myRow);


                $add2 = $add2 . $myRow;
            }

            $add3="";
            $myRow = file_get_contents('../HTML/AdminUserListItem.html');
            $search = array('%Username%','%UserID%');
            $replace = array("Ohne Trainer",0);   
            $myRow = str_replace($search,$replace,$myRow);
            $add3 = $add3 . $myRow;

            //$myUsers = $ODB->getUsersFromInstitution($_GET['InstitutionsID']);                                           //Zwischenspeicherung aller Nutzer einer Institution
            $myUsers = $ODB->getUsersFromInstitution($InstitutionsID);
            for ($a=0;$a<sizeof($myUsers);$a++){                                                                         //Auflistung aller Nutzer mit Namen und ID
                $myRow = file_get_contents('../HTML/AdminUserListItem.html');
                $search = array('%Username%','%UserID%');
                $replace = array($myUsers[$a] ->getsUserName(),$myUsers[$a]->getID());   
                $myRow = str_replace($search,$replace,$myRow);


                $add3 = $add3 . $myRow;
            }

            $myPage = str_replace("%InstitutionID%",$InstitutionsID,$myPage);                                    //Hinzufügen von InstitutionID zur Seite
            $myPage = str_replace("%tablerow%",$add1,$myPage);                                                          //Hinzufügen von Gruppenliste zur Seite
            $myPage = str_replace("%Listitems%",$add2,$myPage);                                                         //Hinzufügen von Modulliste zur Seite
            $myPage = str_replace("%UListitems%",$add3,$myPage);                                                         //Hinzufügen von Nutzerliste zur Seite  
    
//-------------------------------------------------------------------------------------------------------------------------------------------

    if (isset($_POST['BeitrittButton'])){
        $ODB->processRegistrationLink($myUser->getID(),$_POST['input']);
        header("Location: ../PHP/userOverview.php");
        exit;
    }

    $myPage=str_replace('%Module%',$toAdd,$myPage);
    echo $myPage;
    
?>