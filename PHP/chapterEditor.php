<?php
	 ob_start();
	 session_start();
	 $myPage = file_get_contents('../HTML/chapterEditor.html');                                                 //HTML-Einbettung vom chapterEditor.html
	 include_once("database.php");
	 include_once("Model/Chapter.php");
	 include_once("Navigation.php");
    

    $myModuleID = $_GET['moduleID'];                                                                            //Zwischenspeicherung von ModuleID
    $myChapterID = $_GET['chapterID'];                                                                          //Zwischenspeicherung von ChapterID
	$myChapterID = $myChapterID;
    //$formerChapterID = ($ODB->getChapterIDFromIndex($myChapterID + 1,$myModuleID) - 1);
    if(isset($_GET['groupID'])){//AL Wenn man über den "Hier editieren"-Button gekommen ist
        $myGroupID = $_GET['groupID'];
    }else{
        $myGroupID = NULL;
    }
    $myUserID = $_SESSION['user'];                                                                              //Zwischenspeicherung von UserID
    
	 // if session is not set this will redirect to login page
    if( !isset($_SESSION['user']) ) {                                                                           //Userabfrage
        header("Location: ../index.php");
        exit;
	 }
    if (isset($_GET['openmodal'])){
        $bool = "true";
    } else {
        $bool = "false";
    }

	
	$myPage = str_replace('%Navigation%',getNavigation(),$myPage);                                              //Einbettung von Navigation in die Seite per getNavigation()
    
    if ( isset($_POST['backButton'])){//AL Wenn man zurück in das Kapitel will aus dem man kommt
        header("Location: ../PHP/chapterView.php?moduleID=".$myModuleID."&chapterID=".($myChapterID-1)."&groupID=".$myGroupID);
    }
    if ($myGroupID!=NULL){
        $toAdd = file_get_contents('../HTML/ChapterEditorBackButtonToggle.html');//AL erstellt den Trainer Button
        $search = array('%Toggle%');
        $replace = array($toAdd);
        $myPage = str_replace($search,$replace,$myPage);
    }else{
        $search = array('%Toggle%');
        $replace = array("");
        $myPage = str_replace($search,$replace,$myPage);
    }
	if(!($ODB->hasPermission($_SESSION['user'],"Chapter","edit",$myChapterID)and($ODB->hasPermission($_SESSION['user'],"Chapter","edit",$myChapterID))) ) {
        echo "Sie haben nicht die benötigte Berechtigung um diese Seite anzusehen.";
        exit;
    } else {
    //TODO-Nur user mit Berechtigungen erlaubt
    $myModule = $ODB->getModuleFromID($myModuleID);                                                             //Zwischenspeicherung vom eigenen Modul
    $myUser = $ODB->getUserFromId($myUserID);                                                                   //Zwischenspeicherung vom eigenen Nutzer
    $search = array('%ChapterHeadline%','%ChapterText%','%ChapterID%','%ModuleID%','%bool%');                   //Markierung vom gesamten Kapitel
    $chapterText = $ODB->getChapterFromID($myChapterID)->getsText();                                            //Zwischenspeicherung vom Kapiteltext aus der DB
    $headline = substr($chapterText,((-1)*(strlen($chapterText)))+11,(strpos($chapterText,"[/headline]")-11));  //Titel wird aus dem Kapiteltext mit Stringoperatoren gezogen
    $headline = substr($headline,(strpos($headline,'-'))-strlen($headline)+2);                                  //Titel wird aus dem Kapiteltext mit Stringoperatoren gezogen
    //$ODB->setChapterHeadlineFromId($headline,$myChapterID); ---Hat Kapuut gemacht--                                                    //Titel wird in die DB gespeichert
    $myPage = str_replace("%ChapterTextRaw%",$chapterText,$myPage);                                             //Kapiteltext wird in Seite eingefügt
    $chapterText = $ODB->replaceTags($chapterText);                                                             //Kapiteltext wird nach den gesetzten Tags bearbeitet mit originalen HTML-Tags ersetzt
    $text = '<div class="chapterView col-md-12">  '.$chapterText.'</div>';

    $replace = array($myModule->getChapterHeadlineByIndex($ODB->getIndexFromChapterID($myChapterID)-1),$text,$myChapterID,$myModuleID,$bool);   //Zwischenspeicherung vom Kompletten bearbeitetem Kapitel
    $myPage = str_replace($search,$replace,$myPage);                                                                                            //Einfügen vom Kompletten bearbeitetem Kapitel


	$toAdd = "";
    $images =$ODB->getAllPicsFromModuleID($myModule -> getID());                                                                                //Zwischenspeicherung von allen Modulbilder

	for ($i=0; $i< sizeof($images) ;$i++){                                                                                                      //Zwischenspeicherung von allen Modulbilder in jeweilige Module
     
        $myRow = file_get_contents('../HTML/chapterEditorGalleryPic.html');
		$myRow = str_replace("%Link%",$images[$i],$myRow);
		$toAdd = $toAdd . $myRow;
    }
	$myPage = str_replace('%Pics%',$toAdd,$myPage);                                                                                             //Einfügen von allen Modulbilder in die Seite
   
    
        echo $myPage;                                                                                                                           //Anzeigen der Seite
	}
	
?>