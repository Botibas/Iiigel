<?php
    //JN- erstelt
    private $stmtGetUserFromID;
    $this->stmtdeletGroupsRegistrationLinks = $this->db_connection->prepare("DELETE FROM `registrationlinkgroup` WHERE DATE(`EndDatum`) < DATE(NOW())");
    public function deletGroupsRegistrationLinks{
        $this->stmtdeletGroupsRegistrationLinks->execute();
    }
    deletGroupsRegistrationLinks ();
?>