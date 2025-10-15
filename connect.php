<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetching data from the form
$ID_NO_NAME = $_POST['PatientID'] ?? '';
$NAME_FIRST = $_POST['FirstName'] ?? '';
$NAME_MIDDLE = $_POST['MiddleName'] ?? '';
$NAME_LAST = $_POST['LastName'] ?? '';
$DATE_BIRTH = $_POST['DateOfBirth'] ?? '';
$BLOOD_GROUP = $_POST['BloodGroup'] ?? '';
$ADDR_STR = $_POST['Address'] ?? '';
$ADDR_STR_NR = $_POST['PinCode'] ?? '';
$CELLPHONE_1_NR = $_POST['ContactNumber1'] ?? '';
$CELLPHONE_2_NR = $_POST['ContactNumber2'] ?? '';
$SEX = $_POST['Gender'] ?? '';
$TITLE = $_POST['Title'] ?? '';
$REG_CATEG_IDNO = $_POST['Relation'] ?? '';
$USER_NAME = $_POST['Relation'] ?? '';
$DESIGNATION = $_POST['Des'] ?? '';
$EMP_GRADE = $_POST['Grade'] ?? '';
$EXTENSION_CREATE_DATE = $_POST['ECD'] ?? '';
$EXTENSION_VALIDITY_DATE = $_POST['EVD'] ?? '';

// Database connection
$conn = new mysqli('localhost', 'root', '', 'nlc_patient_details');

// Check connection
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Check if PatientID exists
$check_patientid = $conn->prepare('SELECT ID_NO_NAME FROM detail WHERE ID_NO_NAME = ?');
if ($check_patientid === false) {
    die("Prepare failed: " . $conn->error);
}

$check_patientid->bind_param("s", $ID_NO_NAME);
if ($check_patientid->execute() === false) {
    die("Execution failed: " . $check_patientid->error);
}

$result = $check_patientid->get_result();
$exists = $result->num_rows > 0;

$check_patientid->close();
$act = 'Y';
$t = $ID_NO_NAME . $USER_NAME;
$n = $NAME_FIRST . $NAME_MIDDLE . $NAME_LAST;
if ($exists) {
    // Update operation
    $Uq = "UPDATE detail SET NAME_FIRST=?, NAME_MIDDLE=?, NAME_LAST=?, DATE_BIRTH=?, BLOOD_GROUP=?, ADDR_STR=?, ADDR_STR_NR=?, CELLPHONE_1_NR=?, CELLPHONE_2_NR=?, SEX=?, TITLE=?, RELATION=?, REG_CATEG_IDNO=?, USER_NAME=?, DESIGNATION=?, EMP_GRADE=?, EXTENSION_CREATE_DATE=?, EXTENSION_VALIDITY_DATE=? WHERE ID_NO_NAME=?";
    $stmt = $conn->prepare($Uq);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $U = "Update";

    $stmt->bind_param("sssssssssssssssssss", $NAME_FIRST, $NAME_MIDDLE, $NAME_LAST, $DATE_BIRTH, $BLOOD_GROUP, $ADDR_STR, $ADDR_STR_NR, $CELLPHONE_1_NR, $CELLPHONE_2_NR, $SEX, $TITLE, $n, $t, $t, $DESIGNATION, $EMP_GRADE, $EXTENSION_CREATE_DATE, $EXTENSION_VALIDITY_DATE, $ID_NO_NAME);

    if ($stmt->execute() === false) {
        die("Execution failed In Detail Table: " . $stmt->error);
    } else {
        echo "Data Updated Successfully";
    }

    $cstmt = $conn->prepare("UPDATE control SET LOG_TYPE=?, LOG_PROCESSED_FLAG=?, LOG_REMARKS=?, LOG_DT=?, LOG_CRT_DT=? WHERE LOG_PATIENT_NO=?");
    if ($cstmt === false) {
        die("Control Update Query Failed: " . $conn->error);
    }
    $text = "DATA TO BE UPDATED";
    $cstmt->bind_param("ssssss", $U, $act, $text, $EXTENSION_CREATE_DATE, $EXTENSION_CREATE_DATE, $ID_NO_NAME);

    if ($cstmt->execute() === false) {
        die("Execution Failed in Control Table: " . $cstmt->error);
    }
} else {
    // Insert operation
    $Iquery = "INSERT INTO detail (ID_NO_NAME, NAME_FIRST, NAME_MIDDLE, NAME_LAST, DATE_BIRTH, BLOOD_GROUP, ADDR_STR, ADDR_STR_NR, CELLPHONE_1_NR, CELLPHONE_2_NR, SEX, TITLE, RELATION, REG_CATEG_IDNO, USER_NAME, DESIGNATION, EMP_GRADE, EXTENSION_CREATE_DATE, EXTENSION_VALIDITY_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($Iquery);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssssssss", $ID_NO_NAME, $NAME_FIRST, $NAME_MIDDLE, $NAME_LAST, $DATE_BIRTH, $BLOOD_GROUP, $ADDR_STR, $ADDR_STR_NR, $CELLPHONE_1_NR, $CELLPHONE_2_NR, $SEX, $TITLE, $n, $t, $t, $DESIGNATION, $EMP_GRADE, $EXTENSION_CREATE_DATE, $EXTENSION_VALIDITY_DATE);

    if ($stmt->execute() === false) {
        die("Execution failed: " . $stmt->error);
    } else {
        echo "Data Stored Successfully";
    }

    $cstmt = $conn->prepare("INSERT INTO control (LOG_PATIENT_NO, LOG_PROCESSED_FLAG,LOG_DT,LOG_CRT_DT) VALUES (?, ?, ?, ?)");
    if ($cstmt === false) {
        die("Control Insert Query Failed: " . $conn->error);
    }

    $cstmt->bind_param("ssss", $ID_NO_NAME, $act, $EXTENSION_CREATE_DATE, $EXTENSION_CREATE_DATE);

    if ($cstmt->execute() === false) {
        die("Insert Failed in Control Table: " . $cstmt->error);
    }
}

$stmt->close();
$cstmt->close();
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