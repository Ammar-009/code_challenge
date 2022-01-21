<?php

require 'config/db_config.php';
require 'classes/event.php';
require 'classes/employee.php';

$objEvent = new Event();
$objEvent->setConnection($mysqli);
$objEmp = new Employee();
$objEmp->setConnection($mysqli);
//$objEvent->debug = true;
$aRows = $objEvent->fReadEventsJson($vPath = "assets/", $vFileName = "events.json");
$aFaults = [];


//print_r(count($aRows));exit;
foreach ($aRows as $index => $thisRow) {
    $vProceed = true;
    $vEventDate = $thisRow["event_date"];

    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $vEventDate)) {
        $aFaults[] = array("record" => $thisRow, "reason" => "Event date format is invalid!");
    } else if (!is_numeric($thisRow["participation_id"])) {
        $aFaults[] = array("record" => $thisRow, "reason" => "Particaption id is invalid!");
    } else if (!is_numeric($thisRow["event_id"])) {
        $aFaults[] = array("record" => $thisRow, "reason" => "Event id is invalid!");
    } else if (!is_numeric($thisRow["participation_fee"])) {
        $aFaults[] = array("record" => $thisRow, "reason" => "Event Fee is invalid!");
    } else if (!filter_var($thisRow["employee_mail"], FILTER_VALIDATE_EMAIL)) {
        $aFaults[] = array("record" => $thisRow, "reason" => "Employee email is invalid!");
    } else {
        // print_r($thisRow);
        //add event if not exist
        $objEvent->setEventName($thisRow["event_name"]);
        $objEvent->setEventDate($thisRow["event_date"]);
        $objEvent->setEventParticipationFee($thisRow["participation_fee"]);
        $objEvent->setEventID(NULL);
        $objEmp->setEventID(NULL);
        $aEvent = $objEvent->fList();

        if (empty($aEvent)) {
            $vAddedID = $objEvent->fInsert();
            if ($vAddedID)
                $objEmp->setEventID($vAddedID);
        } else {
            $objEmp->setEventID($aEvent[0]->id);
        }
        // echo $objEmp->getEventID() . "____";
        //employee add if not exist
        $objEmp->setEmployeeName($thisRow["employee_name"]);
        $objEmp->setEmployeeEmail($thisRow["employee_mail"]);
        $objEmp->setEmployeeID(NULL);
        $aEmployee = $objEmp->fList();
        if (empty($aEmployee)) {
            $vAddedID = $objEmp->fInsert();
            if ($vAddedID)
                $objEmp->setEmployeeID($vAddedID);
        } else {

            $objEmp->setEmployeeID($aEmployee[0]->id);
        }
        // add as particpant if not
        $aParticipant = $objEmp->fListParticpant();
        if (empty($aParticipant)) {
            $vAddedID = $objEmp->fInsertParticipant();
        }
    }
    echo "<br><br>";
    if (!empty($aFaults))
        echo "<pre>" . print_r($aFaults) . "</pre>";
}
?>

