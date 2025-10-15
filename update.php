<?php
$conn = new mysqli("localhost", "root", "", "nlc_patient_details");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patients'])) {
    $patientDetails = json_decode($_POST['patients'], true);

    if (!empty($patientDetails)) {
        $query = $conn->prepare("UPDATE Update_detail SET  
            NAME_FIRST=?,
            DATE_REG=?,
            REG_CATEG_IDNO=?,
            USER_NAME=?,
            IS_ENTITLED=?,
            SEL_DEPARTMENT=?,
            DESIGNATION=?,
            EMP_GRADE=?,
            ID_TYPE=?,
            NAME_MIDDLE=?,
            NAME_LAST=?,
            DATE_BIRTH=?,
            BLOOD_GROUP=?,
            ADDR_STR=?,
            ADDR_STR_NR=?,
            AREA=?,
            ADDR_ZIP=?,
            CITIZENSHIP=?,
            CELLPHONE_1_NR=?,
            CELLPHONE_2_NR=?,
            CIVIL_STATUS=?,
            SEX=?,
            TITLE=?,
            RELATION=?,
            EMP_INCOME=?,
            EXTENSION_CREATE_DATE=?,
            EXTENSION_VALIDITY_DATE=?
            WHERE ID_NO_NAME=? ");

        if ($query === false) {
            die("Query Failed: " . $conn->error);
        }
        $fquery = $conn->prepare("UPDATE control SET LOG_PROCESSED_FLAG=? WHERE LOG_PATIENT_NO=?");

        if ($fquery === false) {
            die("Flag Query Failed" . $conn->error);
        }
        $flag = 'N';
        foreach ($patientDetails as $patient) {

            $check_patientid = $conn->prepare('SELECT ID_NO_NAME FROM Update_detail WHERE ID_NO_NAME = ?');
            if ($check_patientid === false) {
                die("Prepare failed: " . $conn->error);
            }

            $check_patientid->bind_param("s", $patient['ID_NO_NAME']);
            if ($check_patientid->execute() === false) {
                die("Patient Record not Executive properly: " . $check_patientid->error);
            }

            $result = $check_patientid->get_result();
            $exists = $result->num_rows > 0;

            if ($exists > 0) {

                $query->bind_param(
                    "ssssssssssssssssssssssssssss",
                    $patient['NAME_FIRST'],
                    $patient['EXTENSION_CREATE_DATE'],
                    $patient['REG_CATEG_IDNO'],
                    $patient['USER_NAME'],
                    $patient['IS_ENTITLED'],
                    $patient['SEL_DEPARTMENT'],
                    $patient['DESIGNATION'],
                    $patient['EMP_GRADE'],
                    $patient['ID_TYPE'],
                    $patient['NAME_MIDDLE'],
                    $patient['NAME_LAST'],
                    $patient['DATE_BIRTH'],
                    $patient['BLOOD_GROUP'],
                    $patient['ADDR_STR'],
                    $patient['ADDR_STR_NR'],
                    $patient['AREA'],
                    $patient['ADDR_ZIP'],
                    $patient['CITIZENSHIP'],
                    $patient['CELLPHONE_1_NR'],
                    $patient['CELLPHONE_2_NR'],
                    $patient['CIVIL_STATUS'],
                    $patient['SEX'],
                    $patient['TITLE'],
                    $patient['RELATION'],
                    $patient['EMP_INCOME'],
                    $patient['EXTENSION_CREATE_DATE'],
                    $patient['EXTENSION_VALIDITY_DATE'],
                    $patient['ID_NO_NAME']
                );

                if ($query->execute() === false) {
                    die("Execution Failed: " . $query->error);
                }

                $fquery->bind_param('ss', $flag, $patient['ID_NO_NAME']);
                if ($fquery->execute() === false) {
                    die('Flag Execution Failed ' . $fquery->error);
                }
            } else {
                die($patient['ID_NO_NAME'] . " Recode not Found in Update_detail Table");
            }
        }

        echo "Data Updated Successfully in Update_detail Table";
    } else {
        die("Patient details are not received.");
    }
} else {
    echo "No Patient details received.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="text-align:center; background-color:blanchedalmond">
    <center>
        <button type="button" onclick="goHome()">Back to Home Page</button>
    </center>
    <script>
        function goHome() {
            window.location.href = "home.html";
        }
    </script>
</body>

</html>