<?php
    //JN- erstelt
    $stmtGetUserFromID;
    $this->stmtdeletGroupsRegistrationLinks = $this->db_connection->prepare("DELETE FROM `registrationlinkgroup` WHERE DATE(`EndDatum`) < DATE(NOW())");
    function deletGroupsRegistrationLinks{
        $this->stmtdeletGroupsRegistrationLinks->execute();
    }
    deletGroupsRegistrationLinks ();
?>