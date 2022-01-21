<?php

class Employee extends Event {

    const TBL_EMPLOYEE = "tbl_employee";
    const TBL_PARTICPANT = "tbl_participant";

    public function setEmployeeID($val) {
        $this->emp_id = $val;
    }

    public function getEmployeeID() {
        return $this->emp_id;
    }

    public function setEmployeeName($val) {
        $this->emp_name = $val;
    }

    public function getEmployeeName() {
        return $this->emp_name;
    }

    public function setEmployeeEmail($val) {
        $this->emp_email = $val;
    }

    public function getEmployeeEmail() {
        return $this->emp_email;
    }

    public function fInsert() {
        $vName = $this->getEmployeeName();
        $vEmail = $this->getEmployeeEmail();
        $aBindParam = [];
        $sBindString = "";

        $sQuery = "INSERT INTO " . Employee::TBL_EMPLOYEE . " " . chr(10);
        $sQuery .= " ( `emp_name`, `emp_email`) " . chr(10);
        $sQuery .= " VALUES (?, ?)";
        $aBindParam[] = $vName;
        $aBindParam[] = $vEmail;
        $sBindString .= "ss";
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
        $vEmployeeID = $this->getEmployeeID();
        $vName = $this->getEmployeeName();
        $vEmail = $this->getEmployeeEmail();
        $aBindParam = [];
        $sBindString = "";
        $aResult = [];

        $sQuery = "SELECT id,`emp_name`, `emp_email` FROM " . Employee::TBL_EMPLOYEE . " "
                . " WHERE emp_status <> 5 "; //5:deleted

        if ($vEmployeeID) {
            $sBindString .= "i";
            $aBindParam[] = $vEmployeeID;
            $sQuery .= " AND id = ?";
        }
        if ($vName) {
            $sBindString .= "s";
            $aBindParam[] = "%" . $vName . "%";
            $sQuery .= " AND emp_name LIKE ?";
        }
        if ($vEmail) {
            $sBindString .= "s";
            $aBindParam[] = $vEmail;
            $sQuery .= " AND emp_email = ?";
        }

        try {
            $vConn = $this->getConnection();
            $vStatement = $vConn->prepare($sQuery);
            if (!empty($aBindParam))
                $vStatement->bind_param($sBindString, ...$aBindParam);

            $vStatement->execute();
            $vStatement->bind_result($id, $ev_name, $ev_email);
            while ($vStatement->fetch()) {
                $aResult[] = (object) [
                            "id" => $id,
                            "emp_name" => $ev_name,
                            "emp_email" => $ev_email
                ];
            }
            $vStatement->close();
        } catch (Exception $excep) {
            trigger_error("Selection failed with Error: " . $excep->getMessage() . " (" . $excep->getCode() . ")", E_USER_ERROR);
        }

        return $aResult;
    }

    public function fInsertParticipant() {
        $vEmployeeID = $this->getEmployeeID();
        $vEventID = $this->getEventID();
        $aBindParam = [];
        $sBindString = "";

        $sQuery = "INSERT INTO " . Employee::TBL_PARTICPANT . " " . chr(10);
        $sQuery .= " ( `event_id`, `emp_id`) " . chr(10);
        $sQuery .= " VALUES (?, ?)";
        $aBindParam[] = $vEventID;
        $aBindParam[] = $vEmployeeID;
        $sBindString .= "ii";
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

    function fListParticpant() {
        $vEmployeeID = $this->getEmployeeID();
        $vEventID = $this->getEventID();
        $vEmployeeName = $this->getEmployeeName();
        $vEventName = $this->getEventName();
        $vEventDate = $this->getEventDate();
        $aBindParam = [];
        $sBindString = "";
        $aResult = [];

        $sQuery = "SELECT part.`event_id`, part.`emp_id`,emp.emp_name,emp.emp_email,ev.ev_name,ev.ev_fee,ev.ev_date   "
                . "FROM " . Employee::TBL_PARTICPANT . " part "
                . "LEFT JOIN " . Employee::TBL_EVENT . " ev on ev.id=part.event_id "
                . "LEFT JOIN " . Employee::TBL_EMPLOYEE . " emp on emp.id=part.emp_id "
                . " WHERE part.status  <> 5 "; //5:deleted

        if ($vEventID) {
            $sBindString .= "i";
            $aBindParam[] = $vEventID;
            $sQuery .= " AND part.event_id = ?";
        }
        if ($vEmployeeID) {
            $sBindString .= "i";
            $aBindParam[] = $vEmployeeID;
            $sQuery .= " AND part.emp_id = ?";
        }
        
        if ($vEmployeeName) {
            $sBindString .= "s";
            $aBindParam[] = "%" . $vEmployeeName . "%";
            $sQuery .= " AND emp.emp_name LIKE ?";
        }
        
        if (!empty($vEventName)) {
            $sBindString .= "s";
            $aBindParam[] = "%" . $vEventName . "%";
            $sQuery .= " AND ev.ev_name LIKE ?";
        }
        if (!empty($vEventDate)) {
            $sBindString .= "s";
            $aBindParam[] = "%" . $vEventDate . "%";
            $sQuery .= " AND ev.ev_date LIKE ?";
        }


        try {
            $vConn = $this->getConnection();
            $vStatement = $vConn->prepare($sQuery);
            if (!empty($aBindParam))
                $vStatement->bind_param($sBindString, ...$aBindParam);
            if ($this->debug) {
                print_r($sQuery);
                print_r($aBindParam);
                print_r($sBindString);
            }
            $vStatement->execute();
            $vStatement->bind_result($ev_id, $ev_emp, $emp_name, $emp_email, $ev_name, $ev_fee, $ev_date);
            while ($vStatement->fetch()) {
                $aResult[] = (object) [
                            "event_id" => $ev_id,
                            "event_name" => $ev_name,
                            "event_date" => $ev_date,
                            "emp_id" => $ev_emp,
                            "event_fee" => $ev_fee,
                            "emp_name" => $emp_name,
                            "emp_email" => $emp_email
                ];
            }
            $vStatement->close();
        } catch (Exception $excep) {
            trigger_error("Selection failed with Error: " . $excep->getMessage() . " (" . $excep->getCode() . ")", E_USER_ERROR);
        }

        return $aResult;
    }

}
