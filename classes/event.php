<?php

class Event {

    const TBL_EVENT = "tbl_event";

    public $debug = false;

    public function setEventID($val) {
        $this->event_id = $val;
    }

    public function getEventID() {
        return $this->event_id;
    }

    public function setEventName($val) {
        $this->event_name = $val;
    }

    public function getEventName() {
        return $this->event_name;
    }

    public function setEventDate($val) {
        $this->event_date = $val;
    }

    public function getEventDate() {
        return $this->event_date;
    }

    public function setEventParticipationFee($val) {
        $this->event_fee = $val;
    }

    public function getEventParticipationFee() {
        return $this->event_fee;
    }

    public function setConnection($val) {
        $this->con = $val;
    }

    public function getConnection() {
        return $this->con;
    }

    public function fInsert() {
        $vName = $this->getEventName();
        $vDate = $this->getEventDate();
        $vFee = $this->getEventParticipationFee();
        $aBindParam = [];
        $sBindString = "";

        $sQuery = "INSERT INTO " . Event::TBL_EVENT . " " . chr(10);
        $sQuery .= " (`ev_name`, `ev_fee`, `ev_date`) " . chr(10);
        $sQuery .= " VALUES (?, ?, ?)";
        $aBindParam[] = $vName;
        $aBindParam[] = $vFee;
        $aBindParam[] = $vDate;
        $sBindString .= "sds";
        if ($this->debug) {
            print_r($sQuery);
            print_r($aBindParam);
            print_r($sBindString);
        }
        try {
            $vConn = $this->getConnection();
            $vStatement = $vConn->prepare($sQuery);
            if (!empty($aBindParam))
                $vStatement->bind_param($sBindString, ...$aBindParam);
            $vStatement->execute();
            if ($vConn->insert_id)
                return $vConn->insert_id;
            else
                return false;
        } catch (Exception $excep) {
            trigger_error(
                    "Error in Inserting: " . $excep->getMessage() . " (" . $excep->getCode() . ")",
                    E_USER_ERROR
            );
        }
    }

    function fList() {
        $vEventID = $this->getEventID();
        $vName = $this->getEventName();
        $vDate = $this->getEventDate();
        $vFee = $this->getEventParticipationFee();
        $aBindParam = [];
        $sBindString = "";
        $aResult = [];

        $sQuery = "SELECT  id,`ev_name`, `ev_fee`, `ev_date` FROM " . Event::TBL_EVENT . " "
                . " WHERE ev_status <> 5 "; //5:deleted

        if ($vEventID) {
            $sBindString .= "i";
            $aBindParam[] = $vEventID;
            $sQuery .= " AND id = ?";
        }
        if (!empty($vName)) {
            $sBindString .= "s";
            $aBindParam[] = "%" . $vName . "%";
            $sQuery .= " AND ev_name LIKE ?";
        }
        if (!empty($vDate)) {
            $sBindString .= "s";
            $aBindParam[] = "%" . $vDate . "%";
            $sQuery .= " AND ev_date LIKE ?";
        }
        if ((float) $vFee >= 0) {
            $sBindString .= "d";
            $aBindParam[] = $vFee;
            $sQuery .= " AND ev_fee = ?";
        }
        if ($this->debug) {
            print_r($sQuery);
            print_r($aBindParam);
            print_r($sBindString);
        }

        try {
            $vConn = $this->getConnection();
            $vStatement = $vConn->prepare($sQuery);
            if (!empty($aBindParam))
                $vStatement->bind_param($sBindString, ...$aBindParam);

            $vStatement->execute();
            $vStatement->bind_result($id, $ev_name, $ev_fee, $ev_date);
            while ($vStatement->fetch()) {
                $aResult[] = (object) [
                            "id" => $id,
                            "event_name" => $ev_name,
                            "event_fee" => $ev_fee,
                            "event_date" => $ev_date
                ];
            }
            $vStatement->close();
        } catch (Exception $excep) {
            trigger_error("Selection failed with Error: " . $excep->getMessage() . " (" . $excep->getCode() . ")", E_USER_ERROR);
        }

        return $aResult;
    }

    public function fReadEventsJson($vPath = "assets/", $vFileName = "events.json") {
        $vResult = [];
        $strJsonFileContents = file_get_contents($vPath . $vFileName);
        $vResult = json_decode($strJsonFileContents, true);

        return $vResult;
    }

}
