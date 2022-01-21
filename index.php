<?php

//require files
require 'config/db_config.php';
require 'classes/event.php';
require 'classes/employee.php';

//objects
$objEvent = new Event();
$objEvent->setConnection($mysqli);
$objEmp = new Employee();
$objEmp->setConnection($mysqli);
//$objEmp->debug = true;
$objEmp->setEventID(null);
$objEmp->setEmployeeID(null);
$objEmp->setEventName(null);
$objEmp->setEmployeeName(null);
$objEmp->setEventDate(null);

if (isset($_POST["search"])) {

    if (!empty($_POST["emp_name"]))
        $objEmp->setEmployeeName($_POST["emp_name"]);
    if (!empty($_POST["event_name"]))
        $objEmp->setEventName($_POST["event_name"]);
    if (!empty($_POST["date"]))
        $objEmp->setEventDate($_POST["date"]);
}
$aParticipants = $objEmp->fListParticpant();
$vTotalFees = 0.0;
?>

<html>
    <title>Code Challenge</title>
    <head>
        <link href="css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="js/jquery-3.5.1.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="js/custom.js" type="text/javascript"></script>
    </head>

    <body>
        <div class="container">
            <h1>Code Challenge</h1>
            <form method="POST">
                <div class="row" style="margin-top:20px;">

                    <div class="col-3">
                        <input type="text" placeholder="Employee Name" name="emp_name">
                    </div>
                    <div class="col-3">
                        <input type="text" placeholder="Event Name" name="event_name">
                    </div>
                    <div class="col-3">
                        <input type="date" name="date">
                    </div>
                    <div class="col-3">
                        <input type="submit" class="btn btn-primary" value="Search" name="search">
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row" style="margin-top:20px;">
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Employee Email</th>
                                <th>Event Title</th>
                                <th>Event Fee</th>
                                <th>Event Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            foreach ($aParticipants as $index => $thisParticipant) {
                                $vTotalFees += $thisParticipant->event_fee;
                                ?>
                                <tr>
                                    <td><?php echo $thisParticipant->emp_name; ?></td>
                                    <td><?php echo $thisParticipant->emp_email; ?></td>
                                    <td><?php echo $thisParticipant->event_name; ?></td>
                                    <td><?php echo $thisParticipant->event_fee; ?></td>
                                    <td><?php echo $thisParticipant->event_date; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                                
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total:<?php echo $vTotalFees ?></td>
                                <td></td>
                            </tr>

                        </tbody>

                    </table>
                </div>
        </div>


    </body>
</html>