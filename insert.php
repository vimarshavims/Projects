<?php
$conn = new mysqli("localhost", "root", "", "nlc_patient_details");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patients'])) {
    $patientDetails = json_decode($_POST['patients'], true);

    if (!empty($patientDetails)) {
        // Define the insert query with correct column names
        $query = $conn->prepare("INSERT INTO update_detail (
            ID_NO_NAME, 
            NAME_FIRST,
            DATE_REG,
            REG_CATEG_IDNO,
            USER_NAME,
            IS_ENTITLED,
            SEL_DEPARTMENT,
            DESIGNATION,
            EMP_GRADE,
            ID_TYPE,
            NAME_MIDDLE,
            NAME_LAST,
            DATE_BIRTH,
            BLOOD_GROUP,
            ADDR_STR,
            ADDR_STR_NR,
            AREA,
            ADDR_ZIP,
            CITIZENSHIP,
            CELLPHONE_1_NR,
            CELLPHONE_2_NR,
            CIVIL_STATUS,
            SEX,
            TITLE,
            RELATION,
            EMP_INCOME,
            EXTENSION_CREATE_DATE,
            EXTENSION_VALIDITY_DATE
        ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");

        if ($query === false) {
            die("Query Failed: " . $conn->error);
        }

        // Define the update query for the control table
        $fquery = $conn->prepare("UPDATE control SET LOG_PROCESSED_FLAG=? WHERE LOG_PATIENT_NO=?");

        if ($fquery === false) {
            die("Flag Query Failed" . $conn->error);
        }
        $flag = 'N';

        foreach ($patientDetails as $patient) {
            // Delete any existing record with the same ID_NO_NAME to prevent duplication
            $check = $conn->prepare("DELETE FROM update_detail WHERE ID_NO_NAME = ?");
            if ($check === false) {
                die("Data Duplication Failed" . $conn->error);
            }
            $check->bind_param("s", $patient['ID_NO_NAME']);

            if ($check->execute() === false) {
                die("Delete Execution Failed: " . $check->error);
            }

            // Bind parameters for the insert query
            $query->bind_param(
                "ssssssssssssssssssssssssssss",
                $patient['ID_NO_NAME'],
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
                $patient['EXTENSION_VALIDITY_DATE']
            );

            if ($query->execute() === false) {
                die("Execution Failed: " . $query->error);
            }

            // Bind parameters and execute the update query for control table
            $fquery->bind_param("ss", $flag, $patient["ID_NO_NAME"]);

            if ($fquery->execute() === false) {
                die("Flag Execution Error: " . $fquery->error);
            }
        }

        echo "Data Stored Successfully in Update_detail Table";
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