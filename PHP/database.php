<?php

        include_once("Model/User.php");
        include_once("Model/Teilnehmer.php");
        include_once("Model/Institution.php");
        include_once("Model/Group.php");
        include_once("Model/Chapter.php");
        include_once("Model/Module.php");
        include_once("Model/RegistrationLink.php");
        include_once("Model/HandIn.php");
       
        
    class Database
    {
        
        
        private $db_connection;
        
        
        private $stmtExistsAccountWithForeignID;
        private $stmtisEmailTaken;
        private $stmtisUsernameTaken;
        private $stmtisUsernameFromID;
        private $stmtisEMailFromID;
        private $stmtisUserinGroup;
        private $stmtisUserinInstitution;
        private $stmtisTrainerofGroup;
		private $stmtisNewHandIn;
        private $stmthasPermissiontoView;
        private $stmthasPermissiontoEdit;
        private $stmthasPermissiontoCreate;
        private $stmthasPermissiontoDelete;
        private $stmtisGroupLink;
        private $stmtisInstitutionLink;
        private $stmtisGroupLinkgueltig;
        private $stmtisInstitutionLinkgueltig;
        private $stmtisUserDeleted;
        private $stmtisAdmin;
        private $stmtisEditor;
        private $stmtisInstitutionsLeader;
        private $stmtisGroupLinkTaken;
        private $stmtisInstitutionLinkTaken;
        
        //-------------------------------------------------
        
		private $stmtGetUserFromID;
        private $stmtGetUserIDFromForeignID;
		private $stmtGetInstitutionFromID;
        private $stmtGetInstitutionIDFromCodeClub;
		private $stmtGetUserFromUsername;
		private $stmtGetGroupFromID;
        private $stmtGetGroupIDFromName;
        private $stmtGetGroupIDFromTutorialModule;
		private $stmtGetGroupsFromUserID;
        private $stmtGetTrainerofGroup;
		private $stmtGetModuleFromID;
        private $stmtGetModuleIDFromName;
        private $stmtGetProfilePicFromUserID;
        private $stmtGetIDFromUsername;
        private $stmtGetUsersFromInstitution;
        private $stmtGetUsersFromGroup;
        private $stmtGetUsersFromPermission;
        private $stmtGetModulesFromInstitution;
        private $stmtGetGroupsFromInstitution;
        private $stmtGetPermissionsFromName;
        private $stmtGetModuleImageFromID;
        private $stmtGetInstitutionsFromUserID;
        private $stmtGetHighestIndexFromChapter;
        private $stmtGetIndexFromID;
        private $stmtSearchUsers;
        private $stmtGetInstitutionFromGroup;
	    private $stmtGetGroupIDFromLink;
        private $stmtGetInstitutionIDFromLink;
        private $stmtGetHandIn;
        private $stmtGetFortschritt;
        private $stmtGetModuleFromGroup;
        private $stmtGetChapterIDFromIndex;
        private $stmtGetPermissionNames;
        
        private $stmtGetAllInstitutions;
		private $stmtGetAllUsers;
        private $stmtGetAllModules;
        private $stmtGetAllGroups;
        private $stmtGetAllUsersFromInstitutionNotInGroup;
        private $stmtGetAllUsersNotInInstitution;
        private $stmtGetAllLinksFromGroup;
        private $stmtGetAllLinksFromInstitution;
        private $stmtGetAllAktiveLinksFromGroup;
        private $stmtGetAllAktiveLinksFromInstitution;
        private $stmtGetAllChaptersFromModuleID;
        private $stmtGetAllInstitutionsFromLeader;
        
        private $stmtCountInstitutions;
        private $stmtCountUsers;
        private $stmtCountGroups;
        private $stmtCountModules;
        private $stmtCountInstitutionsFromUser;
        private $stmtCountUsersFromInstitution;
        private $stmtCountGroupsFromInstitution;
        private $stmtCountModulesFromInstitution;
        private $stmtCountUsersFromModule;
        private $stmtCountUsersFromGroup;
        private $stmtCountUsersFromPermission;
        private $stmtCountSearchedUsers;
        private $stmtCountAllUsersFromInstitutionNotInGroup;
        private $stmtCountAllUsersNotInInstitution;
        private $stmtCountAllLinksFromGroup;
        private $stmtCountAllLinksFromInstitution;
        private $stmtCountAllAktiveLinksFromGroup;
        private $stmtCountAllAktiveLinksFromInstitution;
        private $stmtCountAllChaptersFromModuleID;
        private $stmtCountAllInstitutionsFromLeader;
        
        //--------------------------------------------------
        
        private $stmtSetProfilePic;
        private $stmtSetFortschrittFromUserinGroup;
        private $stmtSetFortschrittforallUsersinGroup;
        private $stmtSetUsernameFromID;
        private $stmtSetFirstNameFromID;
        private $stmtSetLastNameFromID;
        private $stmtSetEMailFromID;
        private $stmtSetPasswordFromID;
        private $stmtSetModuleNameFromID;
        private $stmtSetModuleDescriptionFromID;
        private $stmtSetChapterTextFromID;
        private $stmtSetModuleImageFromID;
        private $stmtSetChapterIndexFromID;
        private $stmtMakeUsertoTrainer;
        private $stmtMakeUsertoNotTrainer;
        private $stmtAcceptHandIn;
        private $stmtUpdatePermissionView;
        private $stmtUpdatePermissionEdit; 
        private $stmtUpdatePermissionCreate;
        private $stmtUpdatePermissionDelete;
        private $stmtSetChapterHeadlineFromID;
        
        //------------------------------------------------
        
        private $stmtaddUser;
        private $stmtaddForeignUser;
        private $stmtaddInstitution;
        private $stmtaddHandIn;
        private $stmtaddGroup;
        private $stmtaddModule;
        private $stmtaddChaptertoModule;
        private $stmtaddTrainertoGroup;
        private $stmtaddUsertoGroup;
        private $stmtaddLeadertoInstitution;
        private $stmtaddUsertoInstitution;
        private $stmtaddGroupInvitationLink;
        private $stmtaddInstitutionInvitationLink;
        private $stmtaddPermission;
        
        //------------------------------------------------
        
        private $stmtdeleteUser;
        private $stmtdeleteChapter;
        private $stmtrejectHandIn;
        private $stmtdeletePermission;
        

        private function query($statement) {
            return mysqli_query($this->db_connection, $statement);
        }

        public function __construct(){
            $configs = include('config.php');
            $this->db_connection = mysqli_connect($configs['host'], $configs['username'], $configs['password'] , $configs['database']);
            if (!$this->db_connection->set_charset("utf8")) {
                printf("Error loading character set utf8: %s\n", $this->db_connection->error);
                exit();
            }
            
            //--------------------------------------------------------- IS/HAS SELECTS --------------------------------------------------------------
            
            $this->stmtExistsAccountWithForeignID = $this->db_connection->prepare("SELECT ID FROM users WHERE foreignID = ? AND bIsDeleted = 0");
			$this->stmtisEmailTaken = $this->db_connection->prepare("SELECT sEMail FROM users WHERE UPPER(users.sEMail) = UPPER(?) AND bIsDeleted = 0");
			$this->stmtisUsernameTaken = $this->db_connection->prepare("SELECT sUsername FROM users WHERE users.sUsername = ? AND bIsDeleted = 0");
            $this->stmtisUsernameFromID = $this->db_connection->prepare("SELECT ID FROM users WHERE sUsername = ? AND bIsDeleted = 0");
            $this->stmtisEMailFromID = $this->db_connection->prepare("SELECT ID FROM users WHERE UPPER(users.sEMail) = UPPER(?) AND bIsDeleted = 0");
            $this->stmtisUserinGroup = $this->db_connection->prepare("SELECT * FROM usertogroup WHERE UserID = ? AND GroupID = ?");
            $this->stmtisUserinInstitution = $this->db_connection->prepare("SELECT * FROM usertoinstitution WHERE UserID = ? AND InstitutionID = ?");
            $this->stmtisTrainerofGroup = $this->db_connection->prepare("SELECT * FROM usertogroup WHERE UserID = ? AND GroupID = ? AND bIsTrainer = 1 ");
			$this->stmtisNewHandIn = $this->db_connection->prepare("SELECT * FROM handins WHERE UserID = ? AND GroupID = ? AND ChapterID = ? AND bIsAccepted = 0 AND isRejected = 0");
            $this->stmthasPermissiontoView = $this->db_connection->prepare("SELECT * FROM rights WHERE UserID = ? AND Name = ? AND (ID = ? OR ID IS NULL) AND canView = 1 AND isDeleted = 0");
            $this->stmthasPermissiontoEdit = $this->db_connection->prepare("SELECT * FROM rights WHERE UserID = ? AND Name = ? AND (ID = ? OR ID IS NULL) AND canEdit = 1 AND isDeleted = 0");
            $this->stmthasPermissiontoCreate = $this->db_connection->prepare("SELECT * FROM rights WHERE UserID = ? AND Name = ? AND (ID = ? OR ID IS NULL) AND canCreate = 1 AND isDeleted = 0");
            $this->stmthasPermissiontoDelete = $this->db_connection->prepare("SELECT * FROM rights WHERE UserID = ? AND Name = ? AND (ID = ? OR ID IS NULL) AND canDelete = 1 AND isDeleted = 0");
            $this->stmtisGroupLink = $this->db_connection->prepare("SELECT * FROM registrationlinkgroup WHERE Link = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            $this->stmtisInstitutionLink = $this->db_connection->prepare("SELECT * FROM registrationlinkinstitution WHERE Link = ?");
            $this->stmtisGroupLinkgueltig = $this->db_connection->prepare("SELECT * FROM registrationlinkgroup WHERE Link = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            $this->stmtisInstitutionLinkgueltig = $this->db_connection->prepare("SELECT * FROM registrationlinkinstitution WHERE Link = ? AND StartDatum >= CURDATE() AND EndDatum <= CURDATE()");
            $this->stmtisUserDeleted = $this->db_connection->prepare("SELECT * FROM users WHERE ID = ? AND bIsDeleted = 1");
            $this->stmtisAdmin = $this->db_connection->prepare("SELECT * FROM rights WHERE UserID = ? AND Name = 'Admin' AND isDeleted = 0");
            $this->stmtisEditor = $this->db_connection->prepare("SELECT * FROM rights WHERE UserID = ? AND Name = 'canEdit' AND isDeleted = 0");
            $this->stmtisInstitutionsLeader = $this->db_connection->prepare("SELECT * FROM usertoinstitution WHERE UserID = ? AND bIsInstitutionleader = 1");
            $this->stmtisGroupLinkTaken = $this->db_connection->prepare("SELECT * FROM registrationlinkgroup WHERE Link = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            $this->stmtisInstitutionLinkTaken = $this->db_connection->prepare("SELECT * FROM registrationlinkinstitution WHERE Link = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            //---------------------------------------------------------- SELECTS ----------------------------------------------------------------
            
			$this->stmtGetUserFromID = $this->db_connection->prepare("SELECT * FROM users WHERE users.ID = ? AND bIsDeleted = 0");
            $this->stmtGetUserIDFromForeignID = $this->db_connection->prepare("SELECT ID FROM users WHERE foreignID = ? AND bIsDeleted = 0");
			$this->stmtGetInstitutionFromID = $this->db_connection->prepare("SELECT * FROM institutions WHERE ID = ? AND bIsDeleted = 0");
            $this->stmtGetInstitutionIDFromCodeClub = $this->db_connection->prepare("SELECT ID FROM institutions WHERE sName = 'CodeClubMG'");
            $this->stmtGetInstitutionIDFromGAG = $this->db_connection->prepare("SELECT ID FROM institutions WHERE sName = 'Gymnasium Am Geroweiher'");
			$this->stmtGetUserFromUsername = $this->db_connection->prepare("SELECT * FROM users WHERE sUsername = ? AND bIsDeleted=0");
			$this->stmtGetGroupFromID = $this->db_connection->prepare("SELECT * FROM groups WHERE ID = ? AND bIsDeleted = 0");
            $this->stmtGetGroupIDFromName = $this->db_connection->prepare("SELECT ID FROM groups WHERE sName = ? AND bIsDeleted=0");
            $this->stmtGetGroupIDFromTutorialModule = $this->db_connection->prepare("SELECT groups.ID AS GroupID FROM groups INNER JOIN modules ON modules.ID = groups.ModulID WHERE modules.sName = 'iiigel' AND groups.bIsDeleted = 0 ");
			$this->stmtGetGroupsFromUserID = $this->db_connection->prepare("SELECT GroupID FROM usertogroup INNER JOIN groups ON groups.ID = usertogroup.GroupID WHERE UserID = ? AND groups.bIsDeleted = 0");
            $this->stmtGetTrainerofGroup = $this->db_connection->prepare("SELECT * FROM users INNER JOIN usertogroup ON usertogroup.UserID = users.ID WHERE bIsTrainer = 1 AND GroupID = ? AND users.bIsDeleted = 0");
			$this->stmtGetModuleFromID = $this->db_connection->prepare("SELECT * FROM modules WHERE ID = ?");
            $this->stmtGetModuleIDFromName = $this->db_connection->prepare("SELECT ID FROM modules WHERE sName = ?");
			$this->stmtGetChapterFromID = $this->db_connection->prepare("SELECT * FROM chapters WHERE ID = ?");
            $this->stmtGetProfilePicFromUserID = $this->db_connection->prepare("SELECT sProfilePicture FROM users WHERE ID = ? AND bIsDeleted = 0");
            $this->stmtGetIDFromUsername = $this->db_connection->prepare("SELECT ID FROM users WHERE sUsername = ? AND bIsDeleted = 0");
            $this->stmtGetModuleImageFromID = $this->db_connection->prepare("SELECT sPfadBild FROM modules WHERE ID = ?");
            $this->stmtGetInstitutionFromGroup = $this->db_connection->prepare("SELECT InstitutionsID FROM groups WHERE ID = ?");
            $this->stmtGetGroupIDFromLink = $this->db_connection->prepare("SELECT GroupID FROM registrationlinkgroup WHERE Link = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            $this->stmtGetInstitutionIDFromLink = $this->db_connection->prepare("SELECT InstitutionID FROM registrationlinkinstitution WHERE Link = ?");
            $this->stmtGetHandIn = $this->db_connection->prepare("SELECT * FROM handins WHERE UserID = ? AND GroupID = ? AND ChapterID = ? AND bIsAccepted = 0 AND isRejected = 0");
            $this->stmtGetFortschritt = $this->db_connection->prepare("SELECT iFortschritt FROM usertogroup WHERE UserID = ? AND GroupID = ?");
            $this->stmtGetModuleFromGroup = $this->db_connection->prepare("SELECT ModulID From groups WHERE ID = ?");
            $this->stmtGetChapterIDFromIndex = $this->db_connection->prepare("SELECT ID FROM chapters WHERE iIndex = ? AND ModulID = ? AND bIsDeleted = 0");
            $this->stmtGetIndexFromID = $this->db_connection->prepare("SELECT iIndex FROM chapters WHERE ID = ?");
            
            //------------------------------------------------------- COUNT ---------------------------------------------------------------------
            
            $this->stmtCountInstitutions = $this->db_connection->prepare("SELECT COUNT(ID) FROM institutions");
            $this->stmtCountUsers = $this->db_connection->prepare("SELECT COUNT(ID) FROM users WHERE bIsDeleted = 0");
            $this->stmtCountGroups = $this->db_connection->prepare("SELECT COUNT(ID) FROM groups");
            $this->stmtCountModules = $this->db_connection->prepare("SELECT COUNT(ID) FROM modules");
            $this->stmtCountInstitutionsFromUser = $this->db_connection->prepare("SELECT COUNT(InstitutionID) FROM usertoinstitution WHERE UserID = ?");
            $this->stmtCountUsersFromInstitution = $this->db_connection->prepare("SELECT COUNT(ID) FROM users INNER JOIN usertoinstitution ON users.ID = usertoinstitution.UserID WHERE InstitutionID = ? AND bIsDeleted = 0");
            $this->stmtCountModulesFromInstitution = $this->db_connection->prepare("SELECT COUNT(ModuleID) FROM moduletoinstitution WHERE InstitutionID = ?");
            $this->stmtCountGroupsFromInstitution = $this->db_connection->prepare("SELECT COUNT(ID) FROM groups WHERE InstitutionsID = ? AND bIsDeleted = 0");
            $this->stmtCountSearchedUsers = $this->db_connection->prepare("SELECT COUNT(ID) FROM users WHERE (sUsername LIKE ? OR sFirstName LIKE ? OR sLastName LIKE ?) AND bIsDeleted = 0 ORDER BY sFirstName,sLastName");
            $this->stmtCountUsersFromModule = $this->db_connection->prepare("SELECT COUNT(UserID) FROM usertogroup INNER JOIN groups ON usertogroup.GroupID = groups.ID WHERE ModulID = ?");
            $this->stmtCountUsersFromGroup = $this->db_connection->prepare("SELECT COUNT(ID) FROM users INNER JOIN usertogroup ON usertogroup.UserID = users.ID WHERE GroupID = ? AND bIsDeleted = 0");
            $this->stmtCountUsersFromPermission = $this->db_connection->prepare("SELECT COUNT(Name) FROM users INNER JOIN rights ON rights.UserID = users.ID WHERE rights.Name = ? AND users.bIsDeleted = 0 AND rights.isDeleted = 0");
            $this->stmtCountAllUsersFromInstitutionNotInGroup = $this->db_connection->prepare("SELECT COUNT(ID) FROM users Left Join (SELECT * FROM usertogroup WHERE GroupID = ?) AS usertogroupSubset On usertogroupSubset.UserID = users.ID Left Join usertoinstitution ON usertoinstitution.UserID = users.ID WHERE GroupID IS NULL AND InstitutionID = ? AND bIsDeleted = 0");
            $this->stmtCountAllUsersNotInInstitution = $this->db_connection->prepare("SELECT COUNT(ID) FROM users LEFT JOIN (SELECT * FROM usertoinstitution WHERE InstitutionID = ?) AS usertoinstitutionSubset ON usertoinstitutionSubset.UserID = users.ID WHERE usertoinstitutionSubset.InstitutionID IS NULL AND bIsDeleted = 0");
            $this->stmtCountAllLinksFromGroup = $this->db_connection->prepare("SELECT COUNT(ID) FROM registrationlinkgroup WHERE GroupID = ?");
            $this->stmtCountAllLinksFromInstitution = $this->db_connection->prepare("SELECT COUNT(ID) FROM registrationlinkinstitution WHERE InstitutionID = ?");
            $this->stmtCountAllAktiveLinksFromGroup = $this->db_connection->prepare("SELECT COUNT(ID) FROM registrationlinkgroup WHERE GroupID = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            $this->stmtCountAllAktiveLinksFromInstitution = $this->db_connection->prepare("SELECT COUNT(ID) FROM registrationlinkinstitution WHERE InstitutionID = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            $this->stmtCountAllChaptersFromModuleID = $this->db_connection->prepare("SELECT COUNT(ID) FROM chapters WHERE ModulID = ?");
            $this->stmtCountAllInstitutionsFromLeader = $this->db_connection->prepare("SELECT COUNT(UserID) FROM usertoinstitution WHERE UserID = ? AND bIsInstitutionleader = 1");
            $this->stmtSetChapterHeadlineFromID = $this->db_connection->prepare("UPDATE chapters SET sTitle = ? WHERE ID = ?");
            
            //---------------------------------------------------------- SELECT ALL -------------------------------------------------------------
            
            $this->stmtGetAllInstitutions = $this->db_connection->prepare("SELECT * FROM institutions WHERE bIsDeleted = 0 ORDER BY sName");
            $this->stmtGetAllUsers = $this->db_connection->prepare("SELECT * FROM users WHERE bIsDeleted = 0 ORDER BY sFirstName,sLastName");
            $this->stmtGetAllGroups = $this->db_connection->prepare("SELECT * FROM groups WHERE bIsDeleted = 0 ORDER BY sName");
            $this->stmtGetAllModules = $this->db_connection->prepare("SELECT * FROM modules WHERE bIsDeleted = 0 ORDER BY sName");
            $this->stmtGetAllUsersFromInstitutionNotInGroup = $this->db_connection->prepare("SELECT users.* FROM users LEFT JOIN (SELECT * FROM usertogroup WHERE GroupID = ?) AS usertogroupSubset ON usertogroupSubset.UserID = users.ID LEFT JOIN usertoinstitution ON usertoinstitution.UserID = users.ID WHERE GroupID IS NULL AND InstitutionID = ? AND bIsDeleted = 0 ORDER BY sFirstName,sLastName");
            $this->stmtGetAllUsersNotInInstitution = $this->db_connection->prepare("SELECT users.* FROM users LEFT JOIN (SELECT * FROM usertoinstitution WHERE InstitutionID = ?) AS usertoinstitutionSubset ON usertoinstitutionSubset.UserID = users.ID WHERE usertoinstitutionSubset.InstitutionID IS NULL AND bIsDeleted = 0 ORDER BY sFirstName,sLastName");
            $this->stmtGetAllLinksFromGroup = $this->db_connection->prepare("SELECT * FROM registrationlinkgroup WHERE GroupID = ?");
            $this->stmtGetAllLinksFromInstitution = $this->db_connection->prepare("SELECT * FROM registrationlinkinstitution WHERE InstitutionID = ?");
            $this->stmtGetAllAktiveLinksFromGroup = $this->db_connection->prepare("SELECT * FROM registrationlinkgroup WHERE GroupID = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            $this->stmtGetAllAktiveLinksFromInstitution = $this->db_connection->prepare("SELECT * FROM registrationlinkinstitution WHERE InstitutionID = ? AND CURRENT_DATE() BETWEEN StartDatum AND EndDatum");
            $this->stmtGetAllChaptersFromModuleID = $this->db_connection->prepare("SELECT * FROM chapters WHERE ModulID = ?");
            $this->stmtGetAllInstitutionsFromLeader = $this->db_connection->prepare("SELECT * FROM usertoinstitution INNER JOIN institutions ON usertoinstitution.InstitutionID = institutions.ID WHERE UserID = ? AND bIsInstitutionleader = 1 AND bIsDeleted = 0");
            
            //-----------------------------------------------------------------------------------------------------------------------------------
            
            $this->stmtGetInstitutionsFromUserID = $this->db_connection->prepare("SELECT * FROM institutions INNER JOIN usertoinstitution ON institutions.ID = usertoinstitution.InstitutionID WHERE UserID = ?");
            $this->stmtGetUsersFromInstitution = $this->db_connection->prepare("SELECT * FROM users INNER JOIN usertoinstitution ON users.ID = usertoinstitution.UserID WHERE InstitutionID = ? AND bIsDeleted = 0 ORDER BY sFirstName,sLastName");
            $this->stmtGetUsersFromGroup = $this->db_connection->prepare("SELECT * FROM users INNER JOIN usertogroup ON usertogroup.UserID = users.ID WHERE GroupID = ? AND bIsDeleted = 0 ORDER BY sFirstName,sLastName");
            $this->stmtGetUsersFromPermission = $this->db_connection->prepare("SELECT * FROM users INNER JOIN rights ON rights.UserID = users.ID WHERE rights.Name = ? AND users.bIsDeleted = 0 AND rights.isDeleted = 0 ORDER BY sFirstName,sLastName");
            $this->stmtGetModulesFromInstitution = $this->db_connection->prepare("SELECT * FROM modules INNER JOIN moduletoinstitution ON modules.ID = moduletoinstitution.ModuleID WHERE InstitutionID = ? ORDER BY modules.sName");
            $this->stmtGetGroupsFromInstitution = $this->db_connection->prepare("SELECT * FROM groups WHERE InstitutionsID = ? AND bIsDeleted = 0 ORDER BY sName");
            $this->stmtGetHighestIndexFromChapter = $this->db_connection->prepare("SELECT MAX(iIndex) FROM chapters WHERE ModulID = ? AND bIsDeleted=0");
            $this->stmtSearchUsers = $this->db_connection->prepare("SELECT * FROM users WHERE (sUsername LIKE ? OR sFirstName LIKE ? OR sLastName LIKE ?) AND bIsDeleted = 0 ORDER BY sFirstName,sLastName");
            $this->stmtGetPermissionsFromName = $this->db_connection->prepare("SELECT *, rights.ID AS pID FROM rights INNER JOIN users ON rights.userID = users.ID WHERE rights.Name = ? AND rights.isDeleted = 0 AND users.bIsDeleted = 0");
            $this->stmtGetPermissionNames = $this->db_connection->prepare("SELECT DISTINCT Name FROM rights WHERE isDeleted = 0 ORDER BY Name");
            
            //--------------------------------------------------------- UPDATES -----------------------------------------------------------------
            $this->stmtSetProfilePic = $this->db_connection->prepare("UPDATE users SET sProfilePicture = ? WHERE ID = ?");
            $this->stmtSetFortschrittFromUserinGroup = $this->db_connection->prepare("UPDATE usertogroup SET iFortschritt = iFortschritt + 1 WHERE GroupID = ? AND UserID = ?");
            $this->stmtSetFortschrittforallUsersinGroup = $this->db_connection->prepare("UPDATE usertogroup SET iFortschritt = ? WHERE GroupID = ? AND iFortschritt < ?");
            $this->stmtSetUsernameFromID = $this->db_connection->prepare("UPDATE users SET sUsername = ? WHERE ID = ?");
            $this->stmtSetFirstNameFromID = $this->db_connection->prepare("UPDATE users SET sFirstName = ? WHERE ID = ?");
            $this->stmtSetLastNameFromID = $this->db_connection->prepare("UPDATE users SET sLastName = ? WHERE ID = ?");
            $this->stmtSetEMailFromID = $this->db_connection->prepare("UPDATE users SET sEMail = ? WHERE ID = ?");
            $this->stmtSetPasswordFromID = $this->db_connection->prepare("UPDATE users SET sHashedPassword = ? WHERE ID = ?");
            $this->stmtSetModuleNameFromID = $this->db_connection->prepare("UPDATE modules SET sName = ? WHERE ID = ?");
            $this->stmtSetModuleDescriptionFromID = $this->db_connection->prepare("UPDATE modules SET sDescription = ? WHERE ID = ? ");
            $this->stmtSetChapterTextFromID = $this->db_connection->prepare("UPDATE chapters SET sText = ? WHERE ID = ?");
            $this->stmtSetModuleImageFromID = $this->db_connection->prepare("UPDATE modules SET sPfadBild = ? WHERE ID = ?");
            $this->stmtSetChapterIndexFromID = $this->db_connection->prepare("UPDATE chapters SET iIndex = ? WHERE ID = ?");
            $this->stmtMakeUsertoTrainer = $this->db_connection->prepare("UPDATE usertogroup SET bIsTrainer = 1 WHERE UserID = ? AND GroupID = ?");
            $this->stmtMakeUsertoNotTrainer = $this->db_connection->prepare("UPDATE usertogroup SET bIsTrainer = 0 WHERE UserID = ? AND GroupID = ?");
            $this->stmtAcceptHandIn = $this->db_connection->prepare("UPDATE handins SET bIsAccepted = 1 WHERE UserID = ? AND GroupID = ? AND ChapterID = ? AND ID = ?");
            $this->stmtUpdatePermissionView = $this->db_connection->prepare("UPDATE rights SET canView = ? WHERE UserID = ? AND Name = ? AND (ID = ? OR ID IS NULL)");
            $this->stmtUpdatePermissionEdit = $this->db_connection->prepare("UPDATE rights SET canEdit = ? WHERE UserID = ? AND Name = ? AND (ID = ? OR ID IS NULL) ");
            $this->stmtUpdatePermissionCreate = $this->db_connection->prepare("UPDATE rights SET canCreate = ? WHERE UserID = ? AND Name = ? AND (ID = ? OR ID IS NULL) ");
            $this->stmtUpdatePermissionDelete = $this->db_connection->prepare("UPDATE rights SET canDelete = ? WHERE UserID = ? AND Name = ? AND (ID = ? OR ID IS NULL)");
            $this->stmtUpdateCompletePermission = $this->db_connection->prepare("UPDATE rights SET UserID = ?, Name = ?, ID = ?, canView = ?, canEdit = ?, canCreate = ?, canDelete = ? WHERE UserID = ? AND Name = ? AND ID = ? AND canView = ? AND canEdit = ? AND canCreate = ? AND canDelete = ? AND isDeleted = 0");
            
            //------------------------------------------------------- INSERTS -------------------------------------------------------------------
            
            $this->stmtaddUser = $this->db_connection->prepare("INSERT INTO users (sUsername,sFirstName,sLastName,sEMail,sHashedPassword,sProfilePicture) VALUES (?,?,?,?,?,'../ProfilePics/generalpic.png')");
            $this->stmtaddForeignUser = $this->db_connection->prepare("INSERT INTO users (sUsername,sFirstName,sLastName,sEMail,sProfilePicture,bIsForeignAccount,foreignID) VALUES (?,?,?,?,'../ProfilePics/generalpic.png',1,?)");
            $this->stmtaddHandIn = $this->db_connection->prepare("INSERT INTO handins (UserID,GroupID,ChapterID,sText) VALUES (?,?,?,?)");
            $this->stmtaddInstitution = $this->db_connection->prepare("INSERT INTO institutions (sName,bIsDeleted) VALUES (?,0)");
            $this->stmtaddGroup = $this->db_connection->prepare("INSERT INTO groups (ModulID,InstitutionsID,sName,bIsDeleted) VALUES (?,?,?,0)");
            $this->stmtaddModule = $this->db_connection->prepare("INSERT INTO modules (sName,sLanguage) VALUES (?,?)");
            $this->stmtaddChaptertoModule = $this->db_connection->prepare("INSERT INTO chapters (iIndex,sTitle,sText,ModulID) VALUES (?,?,?,?)"); 
            $this->stmtaddTrainertoGroup = $this->db_connection->prepare("INSERT INTO usertogroup VALUES (?,?,0,1)");
            $this->stmtaddUsertoGroup = $this->db_connection->prepare("INSERT INTO usertogroup VALUES (?,?,0,0)");
            $this->stmtaddUsertoInstitution = $this->db_connection->prepare("INSERT INTO usertoinstitution VALUES (?,?,0)");
            $this->stmtaddLeadertoInstitution = $this->db_connection->prepare("INSERT INTO usertoinstitution VALUES (?,?,1)");
            $this->stmtaddGroupInvitationLink = $this->db_connection->prepare("INSERT INTO registrationlinkgroup(Link,GroupID,StartDatum,EndDatum) VALUES (?,?,?,?)");
            $this->stmtaddInstitutionInvitationLink = $this->db_connection->prepare("INSERT INTO registrationlinkinstitution (Link,InstitutionID,StartDatum,EndDatum) VALUES (?,?,?,?)");
            $this->stmtaddPermission = $this->db_connection->prepare("INSERT INTO rights (UserID,Name,ID,canView,canEdit,canCreate,canDelete,isDeleted)  VALUES (?,?,?,?,?,?,?,0)");
            
            //------------------------------------------------------- DELETES ------------------------------------------------------------------
            
            $this->stmtdeleteUser = $this->db_connection->prepare("UPDATE users SET bIsDeleted = 1 WHERE ID = ?");
            $this->stmtrejectHandIn = $this->db_connection->prepare("UPDATE handins SET isRejected = 1 WHERE UserID = ? AND GroupID = ? AND ChapterID = ? AND ID = ?");
            $this->stmtdeletePermission = $this->db_connection->prepare("UPDATE rights SET isDeleted=1 WHERE UserID = ? AND Name = ? AND ID = ?");
            $this->stmtdeleteChapter = $this->db_connection->prepare("UPDATE chapters SET bIsDeleted = 1 WHERE ID= ?");
        }
        
        
        
        
        
        
        //--------------------------------------------------------- ERSETZE TAGS ---------------------------------------------------------------
        
        public function replaceTags ($_sContent){
            
             $sMyDocument = str_replace('\r','<br>', str_replace('\n','<br>', str_replace("]\n",']' ,str_replace('<', '&lt;', str_replace('>', '&gt;', $_sContent)))));
            
            $sTags =$this->query('SELECT sTagFrom,sTagInto,sParam FROM transcribedtags');  
            for ($x = 0; $x <= mysqli_num_rows($sTags);$x++) {
                $aRow =  mysqli_fetch_assoc($sTags);
                if ($aRow['sParam']=="") {
                    $sMyDocument =  str_replace ($aRow['sTagFrom'],$aRow['sTagInto'],$sMyDocument);
                } else {   
                    $iOffset = 0;
              
                    $sMyWorkStr= '';
                 
                        while ( substr_count($sMyDocument, $aRow['sTagFrom']) > 0 ){ 
                            $iOffset = strpos($sMyDocument,$aRow['sTagFrom']);
                    
                            if (substr($sMyDocument,$iOffset+strlen($aRow['sTagFrom']) ,1)=='{'){

                                $sMyParam = substr($sMyDocument,strpos($sMyDocument,'{' , $iOffset)+1,strpos ($sMyDocument,'}',$iOffset)-1-strpos($sMyDocument,'{' , $iOffset));
                             
                                $sTest = $sMyParam;
                                $iParamOffset = 0;
                                $sMyWorkStr ='';
                                for ($e = 0; $e <= substr_count($sMyParam, ';')+1;$e++){                               
                                    if (strpos($sMyParam,';') > 0) {    
                                        $sOneParam = substr($sMyParam,$iParamOffset,strpos($sMyParam,';',$iParamOffset)-$iParamOffset);
                               
                                        $iParamOffset = strpos($sMyParam,$sOneParam,$iParamOffset);
                                        $sMyParam = preg_replace('/'.preg_quote($sOneParam .';', '/').'/','',$sMyParam);
                                     
                                    } else {
                                        $sOneParam = substr($sMyParam,0,strlen($sMyParam)-1);
                                        $sMyParam= preg_replace('/'.preg_quote($sOneParam, '/').'/','',$sMyParam);
                                    }
                                    if (strpos('#' . $aRow['sParam'],substr($sOneParam,0,strpos($sOneParam,'=')))> 0){
                                        $ishortOffset = strpos($sOneParam,'"')+1;   
                                        $sMyWorkStr = $sMyWorkStr . substr($sOneParam,0,strpos($sOneParam,'"',$ishortOffset)+1) . ' '; 
                                    }

                                }
                            }
                            $sToReplace = $aRow['sTagInto'];
                            $sTrReplace = str_replace('>',' ' . $sMyWorkStr,$sToReplace);
                            $sTrReplace = $sTrReplace . ">";
                            $iReplaceOffset = strpos($sMyDocument,'}',$iOffset)+1-$iOffset;
                            $sMyDocument = str_replace(substr($sMyDocument,$iOffset,$iReplaceOffset),$sTrReplace,$sMyDocument);
                        
                    }
                }
            }
            return $sMyDocument;
        }
        
        public function getUserDataFromCodingSpace($authToken){
            $url = "https://www.codeclub.de/internal/?page=answerToIiigel&";
            $url .= "activeToken=".$authToken."&";                            //das authentication Token wird überliefert & an die URL gehangen
            $url .= "HMACchecksumme=".hash_hmac('sha1', $authToken, "geheimgeheimeinhorn"); //token wird gehasht und übergeben
            $ch = curl_init();  //Initialisierung des Transfers
            curl_setopt($ch, CURLOPT_URL, $url);  //url wird erstellt
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //string wird übergeben
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $output = json_decode(curl_exec($ch),true); //Ausführung und Dekodierung der Daten
            if (FALSE === $output)
                throw new Exception(curl_error($ch), curl_errno($ch));
            curl_close($ch); //Beenden des Transfers
            if ($output!=NULL){
                if ($output['status']==="success"){
                    $ID = $output['result']['UId'];
                    $userName = $output['result']['userName'];
                    $firstName = $output['result']['firstname'];
                    $lastName = $output['result']['lastname'];
                    $email = $output['result']['email'];
                    $isAGUser = $output['result']['AGUser'];
                    $isIFUser = $output['result']['IFUser'];
                    //$profilePicture = $output['result']['link'];
                    $existingID = $this->existsAccountWithForeignID($ID);
                    if ($existingID != false){
                        return $existingID; 
                    } else {
                        $newuserID = $this->addForeignUser($userName,$firstName,$lastName,$email,$ID,$isAGUser,$isIFUser);
                        return $newuserID;
                    }
                } else {
                    return false;
                }
            }
        }
        
        
        
        //--------------------------------------------------------- ABFRAGEN OB ... -------------------------------------------------------------
        
        public function existsAccountWithForeignID($ID){
            $this->stmtExistsAccountWithForeignID->bind_param("i",$ID);
            $this->stmtExistsAccountWithForeignID->execute();
            $res = $this->stmtExistsAccountWithForeignID->get_result();
            if (mysqli_num_rows($res) != 0) {
                $row = mysqli_fetch_array($res);
                    return $row['ID'];
            } else {
                return false;  
            }
        }
        
        public function isUsernameTaken($sUsername){
            $this->stmtisUsernameTaken->bind_param("s",$sUsername);	
			$this->stmtisUsernameTaken->execute();
			$res = $this->stmtisUsernameTaken->get_result();
            $iNumberOfUsersWithThisUsername = mysqli_num_rows($res);
			if ($iNumberOfUsersWithThisUsername != 0) {
                return true;
            } else {
                return false;  
            }
        }
        
        public function isEmailTaken($sEmail){
			$this->stmtisEmailTaken->bind_param("s",$sEmail);	
			$this->stmtisEmailTaken->execute();
			$res = $this->stmtisEmailTaken->get_result();
			$iAmountOfThisEmail = mysqli_num_rows($res);
            if ($iAmountOfThisEmail != 0) {
                return true;
            } else {
                return false;
            }
        }
        
        public function isGroupLinkTaken($sLink){
			$this->stmtisGroupLinkTaken->bind_param("s",$sLink);	
			$this->stmtisGroupLinkTaken->execute();
			$res = $this->stmtisGroupLinkTaken->get_result();
			$iAmountOfThisGroupLink = mysqli_num_rows($res);
            if ($iAmountOfThisGroupLink != 0) {
                return true;
            } else {
                return false;
            }
        }
        
        public function isInstitutionLinkTaken($sLink){
			$this->stmtisInstitutionLinkTaken->bind_param("s",$sLink);	
			$this->stmtisInstitutionLinkTaken->execute();
			$res = $this->stmtisInstitutionLinkTaken->get_result();
			$iAmountOfThisInstitutionLink = mysqli_num_rows($res);
            if ($iAmountOfThisInstitutionLink != 0) {
                return true;
            } else {
                return false;
            }
        }
        
        public function isViableUsername($ID,$Username){
            $this->stmtisUsernameFromID->bind_param("s",$Username);
            $this->stmtisUsernameFromID->execute();
            $res = $this->stmtisUsernameFromID->get_result();
            $row = mysqli_fetch_array($res);
            if (mysqli_num_rows($res)==0) {
                return true;
            } elseif ($row['ID']==$ID){
                return true;
            } else {
                return false;
            }
        }
        
        public function isViableEMail($ID,$EMail){
            $this->stmtisEMailFromID->bind_param("s",$EMail);
            $this->stmtisEMailFromID->execute();
            $res = $this->stmtisEMailFromID->get_result();
            $row = mysqli_fetch_array($res);
            if (mysqli_num_rows($res)==0) {
                return true;
            } elseif ($row['ID']==$ID){
                return true;
            } else {
                return false;
            }
        }
        
        public function isUserinGroup($UserID,$GroupID){
            $this->stmtisUserinGroup->bind_param("ii",$UserID,$GroupID); 
            $this->stmtisUserinGroup->execute();
            $res = $this->stmtisUserinGroup->get_result();
            if (mysqli_num_rows($res)==1){
                return true;
            } else {
                return false;
            }
        }
        
        public function isUserinInstitution($UserID,$InstitutionID){
            $this->stmtisUserinInstitution->bind_param("ii",$UserID,$InstitutionID); 
            $this->stmtisUserinInstitution->execute();
            $res = $this->stmtisUserinInstitution->get_result();
            if (mysqli_num_rows($res)==1){
                return true;
            } else {
                return false;
            }
        }
        
        public function isTrainerofGroup($UserID,$GroupID){
            $this->stmtisTrainerofGroup->bind_param("ii",$UserID,$GroupID); 
            $this->stmtisTrainerofGroup->execute();
            $res = $this->stmtisTrainerofGroup->get_result();
            if (mysqli_num_rows($res)==1){
                return true;
            } else {
                return false;
            }
        }
		
		public function isNewHandIn($UserID,$GroupID){
            $Index = $this->getFortschritt($UserID,$GroupID)+1;
            $ModulID = $this->getModuleFromGroup($GroupID);
            $ChapterID = $this->getChapterIDFromIndex($Index,$ModulID);
			$this->stmtisNewHandIn->bind_param("iii",$UserID,$GroupID,$ChapterID);
			$this->stmtisNewHandIn->execute();
			$res = $this->stmtisNewHandIn->get_result();
			if (mysqli_num_rows($res) != 0) {
                return true;
			} else{
				return false;			
			}
		}
        
        public function isEditor($UserID){
            $this->stmtisEditor->bind_param("i",$UserID);
			$this->stmtisEditor->execute();
			$res = $this->stmtisEditor->get_result();
			if (mysqli_num_rows($res) == 1) {
				return true;
			} else{
				return false;
			} 
        }
        
        public function isAdmin($UserID){
            $this->stmtisAdmin->bind_param("i",$UserID);
			$this->stmtisAdmin->execute();
			$res = $this->stmtisAdmin->get_result();
			if (mysqli_num_rows($res) == 1) {
				return true;
			} else{
				return false;
			} 
        }
         
        public function hasPermission($UserID,$Name,$whichPermission,$ID=NULL){
           if ($this->isAdmin($UserID)){
               return true;
           } else {
                switch ($whichPermission) {
                    case "view":
                        $toExecute = $this->stmthasPermissiontoView;
                        break;
                    case "edit":
                        $toExecute = $this->stmthasPermissiontoEdit;
                        break;
                    case "create":
                        $toExecute = $this->stmthasPermissiontoCreate;
                        break;
                    case "delete":
                        $toExecute = $this->stmthasPermissiontoDelete;
                        break;
                    default:
                       throw new Exception('Nur view, edit, create oder delete als Parameter für whichPermission erlaubt.');
                       return false;
                } 
			

                if (isset($toExecute)){
                   $toExecute->bind_param("isi",$UserID,$Name,$ID);
                   $toExecute->execute();
                   $res = $toExecute->get_result();
                    if (mysqli_num_rows($res) >= 1){
                      return true;
                    } else {
                      return false;  
                    }
                }
           }
            
        }
        
        public function isGroupLink($Link){
            $this->stmtisGroupLink->bind_param("s",$Link);
            $this->stmtisGroupLink->execute();
            $res = $this->stmtisGroupLink->get_result();
            if (mysqli_num_rows($res) == 1){
                return true;
            } else {
                return false;
            }
        }
        
        public function isInstitutionLink($Link){
            $this->stmtisInstitutionLink->bind_param("s",$Link);
            $this->stmtisInstitutionLink->execute();
            $res = $this->stmtisInstitutionLink->get_result();
            if (mysqli_num_rows($res) == 1){
                return true;
            } else {
                return false;
            }
        }
        
        public function isGroupLinkgueltig($Link){
            $this->stmtisGroupLinkgueltig->bind_param("s",$Link);
            $this->stmtisGroupLinkgueltig->execute();
            $res = $this->stmtisGroupLinkgueltig->get_result();
            if (mysqli_num_rows($res) == 1){
                return true;
            } else {
                return false;
            }
        }
        
        public function isInstitutionLinkgueltig($Link){
            $this->stmtisInstitutionLinkgueltig->bind_param("s",$Link);
            $this->stmtisInstitutionLinkgueltig->execute();
            $res = $this->stmtisInstitutionLinkgueltig->get_result();
            if (mysqli_num_rows($res) == 1){
                return true;
            } else {
                return false;
            }
        }
        
        public function isUserDeleted($UserID){
            $this->stmtisUserDeleted->bind_param("i",$UserID);
            $this->stmtisUserDeleted->execute();
            $res = $this->stmtisUserDeleted->get_result();
            if (mysqli_num_rows($res) == 1){
                return true;
            } else {
                return false;
            }
        }
        
        public function isInstitutionsLeader($UserID){
            $this->stmtisInstitutionsLeader->bind_param("i",$UserID);
			$this->stmtisInstitutionsLeader->execute();
			$res = $this->stmtisInstitutionsLeader->get_result();
			if (mysqli_num_rows($res) != 0) {
				return true;
			} else{
				return false;
			} 
        }
        
        //----------------------------------------------------------- INSERTS -------------------------------------------------------------------
        
        public function addUser($Username,$FirstName,$LastName,$Email,$Password){
            $this->stmtaddUser->bind_param("sssss",$Username,$FirstName,$LastName,$Email,$Password);
            if ($this->stmtaddUser->execute()){
                $GroupID = $this->getGroupIDFromTutorialModule();
                $User= $this->getUserFromUsername($Username);
                $this->addUserToGroup($User->getID(),$GroupID);
                return true;
            }else{
                return false;
            } 
        }
        
        public function addForeignUser($Username,$FirstName,$LastName,$email,$ID,$isAGUser,$isIFUser){
            $this->stmtaddForeignUser->bind_param("ssssi",$Username,$FirstName,$LastName,$email,$ID);
            $this->stmtaddForeignUser->execute();
            $UserID = $this->getUserIDFromForeignID($ID);
            $InstitutionIDCCMG = $this->getInstitutionIDFromCodeClub();
            $InstitutionIDGAG = $this->getInstitutionIDFromGAG();
            $GroupID = $this->getGroupIDFromTutorialModule();
            if ($isAGUser) {
                $this->addUsertoInstitution($UserID,$InstitutionIDCCMG);  
            }
            if ($isIFUser) {
                $this->addUsertoInstitution($UserID,$InstitutionIDGAG);
            }
            $this->addUserToGroup($UserID,$GroupID);
            return $UserID;
        }
        
        public function addInstitution($sName){
            $this->stmtaddInstitution->bind_param("s",$sName);
            $this->stmtaddInstitution->execute();
        }
        
        public function addHandIn($UserID,$GroupID,$ChapterID,$Text){
            $this->stmtaddHandIn->bind_param("iiis",$UserID,$GroupID,$ChapterID,$Text);
            $this->stmtaddHandIn->execute();
        }
        
        public function addGroup($ModulID,$InstitutionID,$Name){
            $this->stmtaddGroup->bind_param("iis",$ModulID,$InstitutionID,$Name);
            $this->stmtaddGroup->execute();
        }
        
        public function addModule($Name,$Language){
            $this->stmtaddModule->bind_param("ss",$Name,$Language);
            $this->stmtaddModule->execute();
            $ID = $this->getModuleIDFromName($Name);
            mkdir("../Images/ChapterResources/".$ID, 0644);
        }
        
        public function addChaptertoModule($Index,$Title,$Text,$ModulID){
            $this->stmtaddChaptertoModule->bind_param("issi",$Index,$Title,$Text,$ModulID);
            $this->stmtaddChaptertoModule->execute();
        }
        
        public function addTrainertoGroup($UserID,$GroupID){
            $ModulID = $this->getModuleFromGroup($GroupID);
            $this->addPermission($UserID,'ModulChapter',$ModulID,1,0,0,0);
            $this->stmtaddTrainertoGroup->bind_param("ii",$UserID,$GroupID);
            $this->stmtaddTrainertoGroup->execute();
        }
        
        public function addUsertoGroup($UserID,$GroupID){
            
            $ModulID = $this->getModuleFromGroup($GroupID);
            $this->addPermission($UserID,'ModulChapter',$ModulID,1,0,0,0);
            $this->stmtaddUsertoGroup->bind_param("ii",$UserID,$GroupID);
            $this->stmtaddUsertoGroup->execute();
        }
        
        public function addLeadertoInstitution($UserID,$InstitutionID){
            $this->stmtaddLeadertoInstitution->bind_param("ii",$UserID,$InstitutionID);
            $this->stmtaddLeadertoInstitution->execute();
        }
        
        public function addUsertoInstitution($UserID,$InstitutionID){
            $this->stmtaddUsertoInstitution->bind_param("ii",$UserID,$InstitutionID);
            $this->stmtaddUsertoInstitution->execute();
        }
        
        public function addGroupInvitationLink($Link,$GroupID,$Startdatum,$Enddatum){
            $this->stmtaddGroupInvitationLink->bind_param("siss",$Link,$GroupID,$Startdatum,$Enddatum);
            $this->stmtaddGroupInvitationLink->execute();
        }
        
        public function addInstitutionInvitationLink($Link,$InstitutionID,$Startdatum,$Enddatum){
            $this->stmtaddInstitutionInvitationLink->bind_param("siss",$Link,$InstitutionID,$Startdatum,$Enddatum);
            $this->stmtaddInstitutionInvitationLink->execute();
        }
        
        public function addPermission($UserID,$Name,$ID,$canView,$canEdit,$canCreate,$canDelete){
            $this->stmtaddPermission->bind_param("isiiiii",$UserID,$Name,$ID,$canView,$canEdit,$canCreate,$canDelete);
            $this->stmtaddPermission->execute();
        }
        
        public function processRegistrationLink($UserID,$Link){
            $isGroupLink = $this->isGroupLink($Link);
            $isInstitutionLink = $this->isInstitutionLink($Link);
            $isGroupLinkgueltig = $this->isGroupLinkgueltig($Link);
            $isInstitutionLinkgueltig = $this->isInstitutionLinkgueltig($Link);
            
            if (($isGroupLink==true) && ($isGroupLinkgueltig==true)){
                $GroupID = $this->getGroupIDFromLink($Link); 
                $InstitutionID = $this->getInstitutionFromGroup($GroupID);
                $isUserinGroup = $this->isUserinGroup($UserID,$GroupID);
                $isUserinInstitution = $this->isUserinInstitution($UserID,$InstitutionID);
                if ($isUserinGroup == false) {
                    $this->addUsertoGroup($UserID,$GroupID);
                    if ($isUserinInstitution == false) {
                        $this->addUsertoInstitution($UserID,$InstitutionID,$UserID,$InstitutionID);
                    }
                
                }
            } elseif($isInstitutionLinkgueltig){
                $InstitutionID = $this->getInstitutionIDFromLink($Link);
                if ($isUserinInstitution == false) {
                    $this->addUsertoInstitution($UserID,$InstitutionID,$UserID,$InstitutionID);
        
                }
            } else {
                throw new exception('Link ist ungültig');
            }
        }
        
        //----------------------------------------------------------- SELECTS -------------------------------------------------------------------
        
        public function getIDFromUsername($Username){
            $this->stmtGetIDFromUsername->bind_param("s",$Username); 
            $this->stmtGetIDFromUsername->execute();
            $res = $this->stmtGetIDFromUsername->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return $row['ID'];
            } else {
                throw new exception('Mehr als ein User mit dieser ID');        
            }
        } 
        
        public function getUserIDFromForeignID($ID){
            $this->stmtGetUserIDFromForeignID->bind_param("i",$ID);
            $this->stmtGetUserIDFromForeignID->execute();
            $res = $this->stmtGetUserIDFromForeignID->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                return $row['ID'];
            }
        }
        
        public function getUserFromId($ID){	
			$this->stmtGetUserFromID->bind_param("i",$ID);	
			$this->stmtGetUserFromID->execute();
			$res = $this->stmtGetUserFromID->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return new User($row['ID'],$row['sID'],$row['sUsername'],$row['sFirstName'],
                                    $row['sLastName'],$row['sEMail'],$row['sHashedPassword'],
                                    $row['sProfilePicture'],$row['bIsVerified'],$row['bIsOnline']);
            } else {
                throw new exception('Mehr als ein User oder kein User mit dieser ID');        
            }	
            
        }
        
        public function getInstitutionFromID($ID){
			$this->stmtGetInstitutionFromID->bind_param("i",$ID);	
			$this->stmtGetInstitutionFromID->execute();
            $res = $this->stmtGetInstitutionFromID->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return new Institution($row['ID'],$row['sID'],$row['sName'],$row['bIsDeleted']);
            } else {
                throw new exception('Mehr als eine Institution mit dieser ID');        
            }
        }
        
        public function getInstitutionIDFromCodeClub(){
            $this->stmtGetInstitutionIDFromCodeClub->execute();
            $res = $this->stmtGetInstitutionIDFromCodeClub->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return $row['ID'];
            } else {
                throw new exception('Keine oder mehrere Institutionen namens CodeClubMG vorhanden.');        
            }
        }
        
        public function getInstitutionIDFromGAG(){
            $this->stmtGetInstitutionIDFromGAG->execute();
            $res = $this->stmtGetInstitutionIDFromGAG->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return $row['ID'];
            } else {
                throw new exception('Keine oder mehrere Institutionen namens Gymnasium Am Geroweiher vorhanden.');        
            }
        }
         
        public function getUserFromUsername($Username){
            $this->stmtGetUserFromUsername->bind_param("s",$Username);	
			$this->stmtGetUserFromUsername->execute();
            $res = $this->stmtGetUserFromUsername->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return new User($row['ID'],$row['sID'],$row['sUsername'],$row['sFirstName'],
                                    $row['sLastName'],$row['sEMail'],$row['sHashedPassword'],
                                    $row['sProfilePicture'],$row['bIsVerified'],$row['bIsOnline']);
            } else {
                return false;        
            }
        }
        
        public function getGroupFromID($ID){
			$this->stmtGetGroupFromID->bind_param("i",$ID);	
			$this->stmtGetGroupFromID->execute();
            $res = $this->stmtGetGroupFromID->get_result();
            $iNumResults = mysqli_num_rows($res);
            if ($iNumResults == 1) {
                $oTeilnehmerOfGroupResult = $this->query(
                    "SELECT users.ID,users.sID,users.sUsername,users.sFirstName,users.sLastName,users.sEMail," .
                    "users.sHashedPassword,users.sProfilePicture,users.bIsVerified," .
                    "users.bIsOnline,usertogroup.iFortschritt,usertogroup.bIsTrainer " .
                    "FROM users INNER JOIN usertogroup ON users.ID = usertogroup.UserID " .
                    "WHERE usertogroup.GroupID = " . $ID . " AND users.bIsDeleted=0 ORDER BY users.sFirstName ASC");
                $aTeilnehmerOfGroup = [];
                while (($oTeilnehmer = mysqli_fetch_row($oTeilnehmerOfGroupResult)) != NULL) {
                    //ToDo: switch to non-indice based access of db-column
                    $aTeilnehmerOfGroup[] = new Teilnehmer($oTeilnehmer[0], $oTeilnehmer[1], $oTeilnehmer[2],
                        $oTeilnehmer[3], $oTeilnehmer[4], $oTeilnehmer[5], $oTeilnehmer[6], $oTeilnehmer[7],
                        $oTeilnehmer[8], $oTeilnehmer[9], $oTeilnehmer[10], $oTeilnehmer[11]);
                }
                $row = mysqli_fetch_array($res);
                return new Group($row['ID'], $row['ModulID'], $row['InstitutionsID'], $row['sName'], $row['bIsDeleted'],
                    $aTeilnehmerOfGroup);
            } else if ($iNumResults == 0) {
                throw new exception('Keine Gruppe mit dieser ID in der Datenbank');
            } else {
                throw new exception('Mehr als eine Gruppe mit dieser ID');        
            }
        }
        
        public function getGroupIDFromName($Name){
            $this->stmtGetGroupIDFromName->bind_param("s",$Name);	
			$this->stmtGetGroupIDFromName->execute();
            $res = $this->stmtGetGroupIDFromName->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return $row['ID'];
            } else {
                return false;        
            }  
        }
        
        public function getGroupIDFromTutorialModule(){
            $this->stmtGetGroupIDFromTutorialModule->execute();
            $res = $this->stmtGetGroupIDFromTutorialModule->get_result(); 
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return $row['GroupID'];
            } else {
                throw new exception('Kein oder mehrere Module namens iiigel vorhanden.');        
            }
        }
         
        public function getGroupsFromUserID($ID){
            $this->stmtGetGroupsFromUserID->bind_param("i",$ID);	
			$this->stmtGetGroupsFromUserID->execute();
            $res = $this->stmtGetGroupsFromUserID->get_result();
            $aGroups = [];
            while (($row = mysqli_fetch_row($res)) != NULL) {
                $aGroups[] = $this->getGroupFromID($row[0]); 
            }
            return $aGroups;
                
        }
        
        public function getTrainerofGroup($GroupID){
            $this->stmtGetTrainerofGroup->bind_param("i",$GroupID); 
            $this->stmtGetTrainerofGroup->execute();
            $res = $this->stmtGetTrainerofGroup->get_result();
            $row = mysqli_fetch_array($res);
            if (mysqli_num_rows($res)!=0){
                return new User($row['ID'],$row['sID'],$row['sUsername'],$row['sFirstName'],
                                $row['sLastName'],$row['sEMail'],$row['sHashedPassword'],
                                $row['sProfilePicture'],$row['bIsVerified'],$row['bIsOnline']);
            } else {
                return false;
            }
        }
        
        public function getModuleFromID($ID){
            $this->stmtGetModuleFromID->bind_param("i",$ID);	
			$this->stmtGetModuleFromID->execute();
            $res = $this->stmtGetModuleFromID->get_result();
            $iNumResults = mysqli_num_rows($res);

            if ($iNumResults == 1) {
                $oModuleRow = mysqli_fetch_array($res);
                $oChaptersResult = $this->query("SELECT * FROM chapters WHERE ModulID = " . $ID . " AND bIsDeleted = 0 ORDER BY iIndex");
                $aChapters = [];
                while (($row = mysqli_fetch_row($oChaptersResult)) != NULL) {
                    //ToDo: switch to non-indice based access of db-column
                    $aChapters[] = new Chapter($row[0], $row[1], $row[2], $row[3],
                        $row[4], $row[5], $row[6], $row[7], $row[8],
                        $row[9], $row[10], $row[11], $row[12]);
                }
                return new Module($oModuleRow['ID'], $oModuleRow['sID'], $oModuleRow['sName'],
                    $oModuleRow['sDescription'], $oModuleRow['sLanguage'],
                    $oModuleRow['bIsDeleted'], $oModuleRow['bIsLive'], $aChapters);
            } else if ($iNumResults == 0) {
                throw new exception('Kein Modul mit dieser ID in der Datenbank');
            } else {
                throw new exception('Mehr als ein Modul mit dieser ID');        
            }
        }
        
        public function getModuleIDFromName($Name){
            $this->stmtGetModuleIDFromName->bind_param("s",$Name);
            $this->stmtGetModuleIDFromName->execute();
            $res = $this->stmtGetModuleIDFromName->get_result();
            if (mysqli_num_rows($res) == 1) {
                $row = mysqli_fetch_array($res);
                return $row['ID'];
            } else {
                throw new exception("Mehrere Module mit diesem Namen");
            }
        }

        public function getChapterFromID($ID){
			$this->stmtGetChapterFromID->bind_param("i",$ID);	
			$this->stmtGetChapterFromID->execute();
            $res = $this->stmtGetChapterFromID->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                return new Chapter($row['ID'],$row['sID'],$row['iIndex'],$row['sTitle'],$row['sText'],$row['sNote'],$row['ModulID'],$row[ 'sInterpreter'], $row[ 'bIsMandatoryHandIn'], $row[ 'bIsLive'],$row[ 'bLiveInterpretation'],$row[ 'bShowCloud'],$row[ 'bIsDeleted'] );
            } else {
                throw new exception('Mehr als ein Chapter mit dieser ID');        
            }
        }

        public function getProfilePicFromID($ID){
            $this->stmtGetProfilePicFromUserID ->bind_param("i",$ID);
            $this->stmtGetProfilePicFromUserID->execute();
            $res = $this->stmtGetProfilePicFromUserID->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return $row['sProfilePicture'];
            } else {
                throw new exception('Mehr als ein User mit dieser ID');        
            }
        }
        
        public function getModuleImageFromID($ID){
            $this->stmtGetModuleImageFromID->bind_param("i",$ID);
            $this->stmtGetModuleImageFromID->execute();
            $res = $this->stmtGetModuleImageFromID->get_result();
            if (mysqli_num_rows($res)==1){
                $row = mysqli_fetch_array($res);
                    return $row['sPfadBild'];
            } else {
                throw new exception('Mehr als ein Modul mit dieser ID');        
            }
        }
        
        public function getHighestIndexFromChapter($ModulID){
            $this->stmtGetHighestIndexFromChapter->bind_param("i",$ModulID);
            $this->stmtGetHighestIndexFromChapter->execute();
            $res = $this->stmtGetHighestIndexFromChapter->get_result();
            $row = mysqli_fetch_array($res);
            return $row['MAX(iIndex)'];
        }
        
        public function getInstitutionsFromUserID($UserID){
            $this->stmtGetInstitutionsFromUserID->bind_param("i",$UserID);
            $this->stmtGetInstitutionsFromUserID->execute();
            $res = $this->stmtGetInstitutionsFromUserID->get_result();
            $anz = $this->countInstitutionsFromUser($UserID);
            $row = [];
            $ins = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res); 
                $ins[$i] = new Institution($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sName'],$row[$i]['bIsDeleted']);
            } 
            
            return $ins;
        }
        
        public function getUsersFromInstitution($InstitutionID){
            $this->stmtGetUsersFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtGetUsersFromInstitution->execute();
            $res = $this->stmtGetUsersFromInstitution->get_result();
            $anz = $this->countUsersFromInstitution($InstitutionID);
            $row = [];
            $users = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res); 
                $users[$i] = new User($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sUsername'],$row[$i]['sFirstName'],
                                      $row[$i]['sLastName'],$row[$i]['sEMail'],$row[$i]['sHashedPassword'],
                                      $row[$i]['sProfilePicture'],$row[$i]['bIsVerified'],$row[$i]['bIsOnline']);
            } 
            
            return $users;
        }
        
        public function getUsersFromGroup($GroupID){
            $this->stmtGetUsersFromGroup->bind_param("i",$GroupID);
            $this->stmtGetUsersFromGroup->execute();
            $res = $this->stmtGetUsersFromGroup->get_result();
            $anz = $this->countUsersFromGroup($GroupID);
           
            $row = [];
            $users = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res); 
                $users[$i] = new Teilnehmer($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sUsername'],$row[$i]['sFirstName'],
                                      $row[$i]['sLastName'],$row[$i]['sEMail'],$row[$i]['sHashedPassword'],
                                      $row[$i]['sProfilePicture'],$row[$i]['bIsVerified'],$row[$i]['bIsOnline'],$row[$i]['iFortschritt'],
                                      $row[$i]['bIsTrainer']);
            } 
            
            return $users;
        }
        
        public function getUsersFromPermission($PermissionName){
            $this->stmtGetUsersFromPermission->bind_param("s",$PermissionName);
            $this->stmtGetUsersFromPermission->execute();
            $res = $this->stmtGetUsersFromPermission->get_result();
            $anz = $this->countUsersFromPermission($PermissionName);
           
            $row = [];
            $users = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res); 
                $users[$i] = new User($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sUsername'],$row[$i]['sFirstName'],
                                   $row[$i]['sLastName'],$row[$i]['sEMail'],$row[$i]['sHashedPassword'],
                                   $row[$i]['sProfilePicture'],$row[$i]['bIsVerified'],$row[$i]['bIsOnline']);
            } 
            
            return $users;
        }
        
        public function getPermissionsFromName($PermissionName){
            $this->stmtGetPermissionsFromName->bind_param("s",$PermissionName);
            $this->stmtGetPermissionsFromName->execute();
            $res = $this->stmtGetPermissionsFromName->get_result(); 
            
            return $res;
        }
        
        public function getPermissionsFromUser($UserID){
            
            if($this->isAdmin($UserID)){
                return "Admin"; 
            }else if($this->isEditor($UserID)){
                return "Editor";
            }else{
                return "Teilnehmer";
            }
        }
        
        public function getModulesFromInstitution($InstitutionID){
            $this->stmtGetModulesFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtGetModulesFromInstitution->execute();
            $res = $this->stmtGetModulesFromInstitution->get_result();
            $anz = $this->countModulesFromInstitution($InstitutionID);
            $row = [];
            $modules = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res); 
                $modules[$i] = new Module($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sName'],$row[$i]['sDescription'],$row[$i]['sLanguage'],$row[$i]['sIcon'],$row[$i]['bIsDeleted'],$row[$i]['bIsLive'],$row[$i]['sPfadBild']);
            } 
            
            return $modules;
        }
        
        public function getGroupsFromInstitution($InstitutionID){
            $this->stmtGetGroupsFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtGetGroupsFromInstitution->execute();
            $res = $this->stmtGetGroupsFromInstitution->get_result();
            $anz = $this->countGroupsFromInstitution($InstitutionID);
            $row = [];
            $groups = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res); 
                $groups[$i] = $this->getGroupFromID($row[$i]['ID']);
            } 
            
            return $groups;
        }
        
        public function searchUsers($Eingabe){
            $this->stmtSearchUsers->bind_param("sss",$Eingabe,$Eingabe,$Eingabe);
            $this->stmtSearchUsers->execute();
            $res = $this->stmtSearchUsers->get_result();
            $anz = $this->countsearchedUsers($Eingabe,$Eingabe,$Eingabe);
            $row = [];
            $users = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $users[$i] = new User($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sUsername'],$row[$i]['sFirstName'],
                                   $row[$i]['sLastName'],$row[$i]['sEMail'],$row[$i]['sHashedPassword'],
                                   $row[$i]['sProfilePicture'],$row[$i]['bIsVerified'],$row[$i]['bIsOnline']);
            }
            
            return $users;
        }
        
        public function getInstitutionFromGroup($GroupID){
            $this->stmtGetInstitutionFromGroup->bind_param("i",$GroupID);
            $this->stmtGetInstitutionFromGroup->execute();
            $res = $this->stmtGetInstitutionFromGroup->get_result();
            if ( mysqli_num_rows($res) == 1){
                $row = mysqli_fetch_array($res);
                return $row['InstitutionsID'];
            } else {
                throw new exception('Mehr als eine Gruppe mit dieser ID');
            }
        }
	    
        public function getGroupIDFromLink($Link){
            $this->stmtGetGroupIDFromLink->bind_param("s",$Link);
            $this->stmtGetGroupIDFromLink->execute();
            $res = $this->stmtGetGroupIDFromLink->get_result();
            if (mysqli_num_rows($res) == 1){
                $row = mysqli_fetch_array($res);
                return $row['GroupID'];
            } else {
                throw new exception('Mehrere Links mit dieser GroupID');
            }
        }

        public function getInstitutionIDFromLink($Link){
            $this->stmtGetInstitutionIDFromLink->bind_param("s",$Link);
            $this->stmtGetInstitutionIDFromLink->execute();
            $res = $this->stmtGetInstitutionIDFromLink->get_result();
            if (mysqli_num_rows($res) == 1){
                $row = mysqli_fetch_array($res);
                return $row['InstitutionID'];
            } else {
                throw new exception('Mehrere Links mit dieser InstitutionID');
            }
        }
        
        public function getHandIn($UserID,$GroupID){
			if ($this->isNewHandIn($UserID,$GroupID)) {
				$Fortschritt = $this->getFortschritt($UserID,$GroupID);
				$ModulID = $this->getModuleFromGroup($GroupID);
				$ChapterID = $this->getChapterIDFromIndex($Fortschritt+1,$ModulID);
				$this->stmtGetHandIn->bind_param("iii",$UserID,$GroupID,$ChapterID);
				$this->stmtGetHandIn->execute();
				$res = $this->stmtGetHandIn->get_result();
				if (mysqli_num_rows($res) == 1){
					$row = mysqli_fetch_array($res);
                    $handin = new HandIn ($row['ID'],$row['sID'],$row['Date'],$row['UserID'],$row['GroupID'],$row['ChapterID'],$row['bIsAccepted'],$row['bIsUnderReview'],$row['isRejected'],$row['sText']);
					return $handin;
				} elseif (mysqli_num_rows($res) > 1) {
                    $row = [];
                    $handins = [];
				    for ($i=0;$i<mysqli_num_rows($res);$i++){
                        $row[$i]=mysqli_fetch_array($res);
                        $handins[$i] = new HandIn ($row[$i]['ID'],$row[$i]['sID'],$row[$i]['Date'],$row[$i]['UserID'],$row[$i]['GroupID'],$row[$i]['ChapterID'],$row[$i]['bIsAccepted'],$row[$i]['bIsUnderReview'],$row[$i]['isRejected'],$row[$i]['sText']);
                    }
                    $firsthandinindex = 0;
                    $firsthandindate = $row[0]['Date'];
                    for ($i=0;$i<mysqli_num_rows($res);$i++){
                        if ($row[$i]['Date']<$firsthandindate){
                            $firsthandindate = $row[$i]['Date'];
                            $firsthandinindex = $i;
                        } 
                    }  
                    return $handins[$firsthandinindex];
				} else {
                  return null;  
                }
			}
        }
        
        public function getFortschritt($UserID,$GroupID){

            if($_SESSION['lastDeletedUser']!=$UserID){
            $this->stmtGetFortschritt->bind_param("ii",$UserID,$GroupID);
            $this->stmtGetFortschritt->execute();
            $res = $this->stmtGetFortschritt->get_result();
            if (mysqli_num_rows($res) == 1){
                $row = mysqli_fetch_array($res);
                return $row['iFortschritt'];
            } else {
              throw new exception('User ist nicht in dieser Gruppe.');
            }
        }}
        
        public function getModuleFromGroup($GroupID){
            $this->stmtGetModuleFromGroup->bind_param("i",$GroupID);
            $this->stmtGetModuleFromGroup->execute();
            $res = $this->stmtGetModuleFromGroup->get_result();
            if (mysqli_num_rows($res) == 1){
                $row = mysqli_fetch_array($res);
                return $row['ModulID'];
            } else {
              throw new exception('Keine Gruppe oder mehrere Gruppen mit dieser ID');  
            }
        }
        
        public function getChapterIDFromIndex($Index,$ModulID){
            $this->stmtGetChapterIDFromIndex->bind_param("ii",$Index,$ModulID);
            $this->stmtGetChapterIDFromIndex->execute();
            $res = $this->stmtGetChapterIDFromIndex->get_result();
            if (mysqli_num_rows($res) == 1){
                $row = mysqli_fetch_array($res);
                return $row['ID'];
            } else {
              throw new exception('Kein Chapter oder mehrere Chapter mit diesem Index und diesem Modul');  
            }
        }
        
        public function getIndexFromChapterID($ID){
            $this->stmtGetIndexFromID->bind_param("i",$ID);
            $this->stmtGetIndexFromID->execute();
            $res = $this->stmtGetIndexFromID->get_result();
            if (mysqli_num_rows($res) == 1){
                $row = mysqli_fetch_array($res);
                return $row['iIndex'];
            } else {
              throw new exception('Kein Chapter oder mehrere Chapter mit dieser ID');  
            }
        }
        
        public function getPermissionNames(){
            $this->stmtGetPermissionNames->execute();
            $res = $this->stmtGetPermissionNames->get_result();
            
            return $res;
        }
        
        // ---------------- COUNT -------------------------
        
        public function countInstitutions(){
            $this->stmtCountInstitutions->execute();
            $res = $this->stmtCountInstitutions->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countUsers(){
            $this->stmtCountUsers->execute();
            $res = $this->stmtCountUsers->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countGroups(){
            $this->stmtCountGroups->execute();
            $res = $this->stmtCountGroups->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countModules(){
            $this->stmtCountModules->execute();
            $res = $this->stmtCountModules->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countInstitutionsFromUser($UserID){
            $this->stmtCountInstitutionsFromUser->bind_param("i",$UserID);
            $this->stmtCountInstitutionsFromUser->execute();
            $res = $this->stmtCountInstitutionsFromUser->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(InstitutionID)'];
        }
        
        public function countUsersFromInstitution($InstitutionID){
            $this->stmtCountUsersFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtCountUsersFromInstitution->execute();
            $res = $this->stmtCountUsersFromInstitution->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countModulesFromInstitution($InstitutionID){
            $this->stmtCountModulesFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtCountModulesFromInstitution->execute();
            $res = $this->stmtCountModulesFromInstitution->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ModuleID)'];
        }
        
        public function countGroupsFromInstitution($InstitutionID){
            $this->stmtCountGroupsFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtCountGroupsFromInstitution->execute();
            $res = $this->stmtCountGroupsFromInstitution->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countUsersFromModule($ModulID){
            $this->stmtCountUsersFromModule->bind_param("i",$ModulID);
            $this->stmtCountUsersFromModule->execute();
            $res = $this->stmtCountUsersFromModule->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(UserID)'];
        }
        
        public function countUsersFromGroup($GroupID){
            $this->stmtCountUsersFromGroup->bind_param("i",$GroupID);
            $this->stmtCountUsersFromGroup->execute();
            $res = $this->stmtCountUsersFromGroup->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countUsersFromPermission($PermissionName){
            $this->stmtCountUsersFromPermission->bind_param("s",$PermissionName);
            $this->stmtCountUsersFromPermission->execute();
            $res = $this->stmtCountUsersFromPermission->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(Name)'];
        }
        
        public function countsearchedUsers($Username,$FirstName,$LastName){
            $this->stmtCountSearchedUsers->bind_param("sss",$Username,$FirstName,$LastName);
            $this->stmtCountSearchedUsers->execute();
            $res = $this->stmtCountSearchedUsers->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countAllUsersFromInstitutionNotInGroup($InstitutionID,$GroupID){
            $this->stmtCountAllUsersFromInstitutionNotInGroup->bind_param("ii",$GroupID,$InstitutionID);
            $this->stmtCountAllUsersFromInstitutionNotInGroup->execute();
            $res = $this->stmtCountAllUsersFromInstitutionNotInGroup->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        private function countAllUsersNotInInstitution($InstitutionID){
            $this->stmtCountAllUsersNotInInstitution->bind_param("i",$InstitutionID);
            $this->stmtCountAllUsersNotInInstitution->execute();
            $res = $this->stmtCountAllUsersNotInInstitution->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        private function countAllLinksFromGroup($GroupID){
            $this->stmtCountAllLinksFromGroup->bind_param("i",$GroupID);
            $this->stmtCountAllLinksFromGroup->execute();
            $res = $this->stmtCountAllLinksFromGroup->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        private function countAllLinksFromInstitution($InstitutionID){
            $this->stmtCountAllLinksFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtCountAllLinksFromInstitution->execute();
            $res = $this->stmtCountAllLinksFromInstitution->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        private function countAllAktiveLinksFromGroup($GroupID){
            $this->stmtCountAllAktiveLinksFromGroup->bind_param("i",$GroupID);
            $this->stmtCountAllAktiveLinksFromGroup->execute();
            $res = $this->stmtCountAllAktiveLinksFromGroup->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        private function countAllAktiveLinksFromInstitution($InstitutionID){
            $this->stmtCountAllAktiveLinksFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtCountAllAktiveLinksFromInstitution->execute();
            $res = $this->stmtCountAllAktiveLinksFromInstitution->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countAllChaptersFromModuleID($ModulID) {
            $this->stmtCountAllChaptersFromModuleID->bind_param("i",$ModulID);
            $this->stmtCountAllChaptersFromModuleID->execute();
            $res = $this->stmtCountAllChaptersFromModuleID->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(ID)'];
        }
        
        public function countAllInstitutionsFromLeader($UserID) {
            $this->stmtCountAllInstitutionsFromLeader->bind_param("i",$UserID);
            $this->stmtCountAllInstitutionsFromLeader->execute();
            $res = $this->stmtCountAllInstitutionsFromLeader->get_result();
            $row = mysqli_fetch_array($res);
            return $row['COUNT(UserID)'];
        }
        
        // ---------------------- SELECT ALL ------------------------
        
        public function getAllInstitutions(){
            $this->stmtGetAllInstitutions->execute();
            $res = $this->stmtGetAllInstitutions->get_result();
            $anz = $this->countInstitutions();
            $row = [];
            $ins = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res); 
                $ins[$i] = new Institution($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sName'],$row[$i]['bIsDeleted']);
            } 
            
            return $ins;
            
        }
        
        public function getAllUsers(){
            $this->stmtGetAllUsers->execute();
            $res = $this->stmtGetAllUsers->get_result();
            $anz = $this->countUsers();
            $row = [];
            $users = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $users[$i] =  new User($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sUsername'],$row[$i]['sFirstName'],
                                   $row[$i]['sLastName'],$row[$i]['sEMail'],$row[$i]['sHashedPassword'],
                                   $row[$i]['sProfilePicture'],$row[$i]['bIsVerified'],$row[$i]['bIsOnline']); 
            }
            
            return $users;
            
        }
        
        public function getAllGroups(){
            $this->stmtGetAllGroups->execute();
            $res = $this->stmtGetAllGroups->get_result();
            $anz = $this->countGroups();
            $row = [];
            $groups = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $groups[$i] = $this->getGroupFromID($row[$i]['ID']); 
            }
            
            return $groups;
            
        }
        
        public function getAllModules(){
            $this->stmtGetAllModules->execute();
            $res = $this->stmtGetAllModules->get_result();
            $anz = $this->countModules();
            $row = [];
            $modules = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $modules[$i] = $this->getModuleFromID($row[$i]['ID']); 
            }
            
            return $modules;
            
        }
        
        public function getAllUsersFromInstitutionNotInGroup($InstitutionID,$GroupID){
            $this->stmtGetAllUsersFromInstitutionNotInGroup->bind_param("ii",$GroupID,$InstitutionID);
            $this->stmtGetAllUsersFromInstitutionNotInGroup->execute();
            $res = $this->stmtGetAllUsersFromInstitutionNotInGroup->get_result();
            $anz = $this->countAllUsersFromInstitutionNotInGroup($InstitutionID,$GroupID);
            $row = [];
            $users = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $users[$i] =  new User($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sUsername'],$row[$i]['sFirstName'],
                                   $row[$i]['sLastName'],$row[$i]['sEMail'],$row[$i]['sHashedPassword'],
                                   $row[$i]['sProfilePicture'],$row[$i]['bIsVerified'],$row[$i]['bIsOnline']); 
            }
            
            return $users;
        }
        
        public function getAllUsersNotInInstitution($InstitutionID){
            $this->stmtGetAllUsersNotInInstitution->bind_param("i",$InstitutionID);
            $this->stmtGetAllUsersNotInInstitution->execute();
            $res = $this->stmtGetAllUsersNotInInstitution->get_result();
            $anz = $this->countAllUsersNotInInstitution($InstitutionID);
            $row = [];
            $users = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $users[$i] =  new User($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sUsername'],$row[$i]['sFirstName'],
                                   $row[$i]['sLastName'],$row[$i]['sEMail'],$row[$i]['sHashedPassword'],
                                   $row[$i]['sProfilePicture'],$row[$i]['bIsVerified'],$row[$i]['bIsOnline']); 
            }
            
            return $users;
        }
        
        public function getAllLinksFromGroup($GroupID){
            $this->stmtGetAllLinksFromGroup->bind_param("i",$GroupID);
            $this->stmtGetAllLinksFromGroup->execute();
            $res = $this->stmtGetAllLinksFromGroup->get_result();
            $anz = $this->countAllLinksFromGroup($GroupID);
            $row = [];
            $links = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $links[$i] = new RegistrationLink($row[$i]['ID'],$row[$i]['Link'],$row[$i]['GroupID'],$row[$i]['StartDatum'],
                                                  $row[$i]['EndDatum']); 
            }
            
            return $links;
        }
        
        public function getAllLinksFromInstitution($InstitutionID){
            $this->stmtGetAllLinksFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtGetAllLinksFromInstitution->execute();
            $res = $this->stmtGetAllLinksFromInstitution->get_result();
            $anz = $this->countAllLinksFromInstitution($InstitutionID);
            $row = [];
            $links = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $links[$i] = new RegistrationLink($row[$i]['ID'],$row[$i]['Link'],$row[$i]['InstitutionID'],$row[$i]['StartDatum'],
                                                  $row[$i]['EndDatum']); 
            }
            
            return $links;
        }
        
        public function getAllAktiveLinksFromGroup($GroupID){
            $this->stmtGetAllAktiveLinksFromGroup->bind_param("i",$GroupID);
            $this->stmtGetAllAktiveLinksFromGroup->execute();
            $res = $this->stmtGetAllAktiveLinksFromGroup->get_result();
            $anz = $this->countAllAktiveLinksFromGroup($GroupID);
            $row = [];
            $links = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $links[$i] = new RegistrationLink($row[$i]['ID'],$row[$i]['Link'],$row[$i]['GroupID'],$row[$i]['StartDatum'],
                                                  $row[$i]['EndDatum']); 
            }
            
            return $links;
        }
        
        public function getAllAktiveLinksFromInstitution($InstitutionID){
            $this->stmtGetAllAktiveLinksFromInstitution->bind_param("i",$InstitutionID);
            $this->stmtGetAllAktiveLinksFromInstitution->execute();
            $res = $this->stmtGetAllAktiveLinksFromInstitution->get_result();
            $anz = $this->countAllAktiveLinksFromInstitution($InstitutionID);
            $row = [];
            $links = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $links[$i] = new RegistrationLink($row[$i]['ID'],$row[$i]['Link'],$row[$i]['InstitutionID'],$row[$i]['StartDatum'],
                                                  $row[$i]['EndDatum']); 
            }
            
            return $links;
        }
        
        public function getAllPicsFromModuleID($ModulID){
            $pics = [];
            $imagepath = "../Images/ChapterResources/".$ModulID."/";
            foreach(glob($imagepath.'*')as $filename){
                array_push($pics,$filename);
            }
            return $pics;
        }
        
        public function getAllChaptersFromModuleID($ModulID){
            $this->stmtGetAllChaptersFromModuleID->bind_param("i",$ModulID);
            $this->stmtGetAllChaptersFromModuleID->execute();
            $res = $this->stmtGetAllChaptersFromModuleID->get_result();
            $anz = $this->countAllChaptersFromModuleID($ModulID);
            $row = [];
            $chapters = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $chapters[$i] = new Chapter($row[$i]['ID'],$row[$i]['sID'],$row[$i]['iIndex'],$row[$i]['sTitle'],$row[$i]['sText'],$row[$i]['sNote'],$row[$i]['ModulID'],$row[$i][ 'sInterpreter'], $row[$i][ 'bIsMandatoryHandIn'],$row[$i]['bIsLive'],$row[$i]['bLiveInterpretation'],$row[$i]['bShowCloud'],$row[$i]['bIsDeleted']); 
            }
            
            return $chapters;
        }
        
        public function getAllInstitutionsFromLeader($UserID){
            $this->stmtGetAllInstitutionsFromLeader->bind_param("i",$UserID);
            $this->stmtGetAllInstitutionsFromLeader->execute();
            $res = $this->stmtGetAllInstitutionsFromLeader->get_result();
            /*$anz = $this->countAllInstitutionsFromLeader($UserID);
            $row = [];
            $institutions = [];
            for ($i=0;$i<$anz;$i++){
                $row[$i] = mysqli_fetch_array($res);
                $institutions[$i] = new Institution($row[$i]['ID'],$row[$i]['sID'],$row[$i]['sName'],$row[$i]['bIsDeleted'],$row[$i]['UserID']);
            }
            
            return $institutions;*/
            return $res;
        }
        
        //---------------------------------------------------------- UPDATE ---------------------------------------------------------------------
        
        public function setProfilePic($sProfilePic,$ID){
            $this->stmtSetProfilePic->bind_param("si",$sProfilePic,$ID);
            $this->stmtSetProfilePic->execute();  
        }
        
        public function setFortschrittFromUserinGroup($UserID,$GroupID){
            $moduleID = $this->getModuleFromGroup($GroupID);
            $highestindex = $this->getHighestIndexFromChapter($moduleID);
            $lastchapter = $highestindex - 1;
            $currentprogress = $this->getFortschritt($UserID,$GroupID);
            if ($currentprogress!=$lastchapter){
                $this->stmtSetFortschrittFromUserinGroup->bind_param("ii",$GroupID,$UserID);
                $this->stmtSetFortschrittFromUserinGroup->execute();   
            }
            
        }
        
        public function setFortschrittforallUsersinGroup($Fortschritt,$GroupID){
            $this->stmtSetFortschrittforallUsersinGroup->bind_param("iii",$Fortschritt,$GroupID,$Fortschritt);
            $this->stmtSetFortschrittforallUsersinGroup->execute();
        }
        
        public function setUsernameFromID($Username,$ID){
            $this->stmtSetUsernameFromID->bind_param("si",$Username,$ID);
            $this->stmtSetUsernameFromID->execute();
        }
        
        public function setFirstNameFromID($FirstName,$ID){
            $this->stmtSetFirstNameFromID->bind_param("si",$FirstName,$ID);
            $this->stmtSetFirstNameFromID->execute();
        }
        
        public function setLastNameFromID($LastName,$ID){
            $this->stmtSetLastNameFromID->bind_param("si",$LastName,$ID);
            $this->stmtSetLastNameFromID->execute();
        }
        
        public function setEMailFromID($Email,$ID){
            $this->stmtSetEMailFromID ->bind_param("si",$Email,$ID);
            $this->stmtSetEMailFromID ->execute();
        }
        
        public function setPasswordFromID($Password,$ID){
            $this->stmtSetPasswordFromID->bind_param("si",$Password,$ID);
            $this->stmtSetPasswordFromID->execute();
        }
        
        public function setModuleNameFromID($ModuleName,$ID){
            $this->stmtSetModuleNameFromID->bind_param("si",$ModuleName,$ID);
            $this->stmtSetModuleNameFromID->execute();
        }
        
        public function setModuleDescriptionFromID($ModuleDescription,$ID){
            $this->stmtSetModuleDescriptionFromID->bind_param("si",$ModuleDescription,$ID);    
            $this->stmtSetModuleDescriptionFromID->execute();
        }
        
        public function setChapterTextFromID($Text,$ID){
            $this->stmtSetChapterTextFromID->bind_param("si",$Text,$ID);
            $this->stmtSetChapterTextFromID->execute();
        }
        
        public function setModuleImageFromID($PfadBild,$ID){
            $this->stmtSetModuleImageFromID->bind_param("si",$PfadBild,$ID);
            $this->stmtSetModuleImageFromID->execute();
        }
        
        public function setChapterIndexFromID($Index,$ID){
            $this->stmtSetChapterIndexFromID->bind_param("ii",$Index,$ID);
            $this->stmtSetChapterIndexFromID->execute();
        }
        
        public function makeUsertoTrainerorNotTrainer($UserID,$GroupID){
            $isTrainer = $this->isTrainerofGroup($UserID,$GroupID);
            if ($isTrainer){
              $this->stmtMakeUsertoNotTrainer->bind_param("ii",$UserID,$GroupID);
              $this->stmtMakeUsertoNotTrainer->execute();  
            } else {
              $this->stmtMakeUsertoTrainer->bind_param("ii",$UserID,$GroupID);
              $this->stmtMakeUsertoTrainer->execute(); 
            }
        }
        
        public function setChapterHeadlineFromID($Headline,$ID){
            $this->stmtSetChapterHeadlineFromID->bind_param("si",$Headline,$ID);
            $this->stmtSetChapterHeadlineFromID->execute(); 
        }
        
        public function acceptHandIn($UserID,$GroupID,$ID){
            $Fortschritt = $this->getFortschritt($UserID,$GroupID);
            $ModulID = $this->getModuleFromGroup($GroupID);
            $ChapterID = $this->getChapterIDFromIndex($Fortschritt+1,$ModulID);
            $this->stmtAcceptHandIn->bind_param("iiii",$UserID,$GroupID,$ChapterID,$ID);
            $this->stmtAcceptHandIn->execute();
        }
        
        public function updatePermission($UserID,$Name,$whichPermission,$value,$ID=NULL){
            switch ($whichPermission) {
                    case "view":
                        $toExecute = $this->stmtUpdatePermissionView;
                        break;
                    case "edit":
                        $toExecute = $this->stmtUpdatePermissionEdit;
                        break;
                    case "create":
                        $toExecute = $this->stmtUpdatePermissionCreate;
                        break;
                    case "delete":
                        $toExecute = $this->stmtUpdatePermissionDelete;
                        break;
                    default:
                       throw new Exception('Nur view, edit, create oder delete als Parameter für whichPermission erlaubt.');
                       return false;
                } 

                if (isset($toExecute)){
                   $toExecute->bind_param("iisi",$value,$UserID,$Name,$ID);
                   $toExecute->execute();
                }    
        }
        
        public function updateCompletePermission($newUserID,$newName,$newID,$newcanView,$newcanEdit,$newcanCreate,$newcanDelete,$UserID,$Name,$ID,$canView,$canEdit,$canCreate,$canDelete){
            $this->stmtUpdateCompletePermission->bind_param("isiiiiiisiiiii",$newUserID,$newName,$newID,$newcanView,$newcanEdit,$newcanCreate,$newcanDelete,$UserID,$Name,$ID,$canView,$canEdit,$canCreate,$canDelete);
           return $this->stmtUpdateCompletePermission->execute();
        }
        
        //------------------------------------------------------- DELETE ------------------------------------------------------------------------
        
        public function deleteUser($ID){
            $this->stmtdeleteUser->bind_param("i",$ID);
            $this->stmtdeleteUser->execute();
        }
        
        public function rejectHandIn($UserID,$GroupID,$ID){
            $Fortschritt = $this->getFortschritt($UserID,$GroupID);
            $ModulID = $this->getModuleFromGroup($GroupID);
            $ChapterID = $this->getChapterIDFromIndex($Fortschritt+1,$ModulID);
            $this->stmtrejectHandIn->bind_param("iiii",$UserID,$GroupID,$ChapterID,$ID);
            $this->stmtrejectHandIn->execute();
        }
        
        public function deletePermission($UserID,$Name,$ID=NULL){
            $this->stmtdeletePermission->bind_param("isi",$UserID,$Name,$ID);
            $this->stmtdeletePermission->execute();
        }
        public function deleteChapter($ID){
            $this->stmtdeleteChapter->bind_param("i",$ID);
            $this->stmtdeleteChapter->execute();
        }
		
    }

    global $ODB;
    $ODB = new Database();

?>
