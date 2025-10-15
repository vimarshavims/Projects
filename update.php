<?php
$conn = new mysqli("localhost", "root", "", "nlc_patient_details");
$message = "";
$alertType = "info";

if ($conn->connect_error) { die("Connection Failed: " . $conn->connect_error); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patients'])) {
    $patientDetails = json_decode($_POST['patients'], true);

    if (!empty($patientDetails)) {
        $query = $conn->prepare("UPDATE update_detail SET  
            NAME_FIRST=?, DATE_REG=?, REG_CATEG_IDNO=?, USER_NAME=?, IS_ENTITLED=?,
            SEL_DEPARTMENT=?, DESIGNATION=?, EMP_GRADE=?, ID_TYPE=?, NAME_MIDDLE=?, NAME_LAST=?,
            DATE_BIRTH=?, BLOOD_GROUP=?, ADDR_STR=?, ADDR_STR_NR=?, AREA=?, ADDR_ZIP=?, CITIZENSHIP=?,
            CELLPHONE_1_NR=?, CELLPHONE_2_NR=?, CIVIL_STATUS=?, SEX=?, TITLE=?, RELATION=?,
            EMP_INCOME=?, EXTENSION_CREATE_DATE=?, EXTENSION_VALIDITY_DATE=? WHERE ID_NO_NAME=?");

        $fquery = $conn->prepare("UPDATE control SET LOG_PROCESSED_FLAG=? WHERE LOG_PATIENT_NO=?");
        $flag = 'N';

        foreach ($patientDetails as $patient) {
            $check_patientid = $conn->prepare("SELECT ID_NO_NAME FROM update_detail WHERE ID_NO_NAME=?");
            $check_patientid->bind_param("s", $patient['ID_NO_NAME']);
            $check_patientid->execute();
            $result = $check_patientid->get_result();
            $exists = $result->num_rows > 0;

            if ($exists) {
                $query->bind_param(
                    "ssssssssssssssssssssssssssss",
                    $patient['NAME_FIRST'], $patient['EXTENSION_CREATE_DATE'], $patient['REG_CATEG_IDNO'], $patient['USER_NAME'],
                    $patient['IS_ENTITLED'], $patient['SEL_DEPARTMENT'], $patient['DESIGNATION'], $patient['EMP_GRADE'],
                    $patient['ID_TYPE'], $patient['NAME_MIDDLE'], $patient['NAME_LAST'], $patient['DATE_BIRTH'],
                    $patient['BLOOD_GROUP'], $patient['ADDR_STR'], $patient['ADDR_STR_NR'], $patient['AREA'],
                    $patient['ADDR_ZIP'], $patient['CITIZENSHIP'], $patient['CELLPHONE_1_NR'], $patient['CELLPHONE_2_NR'],
                    $patient['CIVIL_STATUS'], $patient['SEX'], $patient['TITLE'], $patient['RELATION'], $patient['EMP_INCOME'],
                    $patient['EXTENSION_CREATE_DATE'], $patient['EXTENSION_VALIDITY_DATE'], $patient['ID_NO_NAME']
                );

                if ($query->execute() === false) {
                    $message = "Update Failed: " . $query->error;
                    $alertType = "danger";
                    break;
                }

                $fquery->bind_param("ss", $flag, $patient['ID_NO_NAME']);
                $fquery->execute();
                $message = "Data Updated Successfully!";
                $alertType = "success";
            } else {
                $message = $patient['ID_NO_NAME'] . " Record not found.";
                $alertType = "warning";
                break;
            }
        }
    } else {
        $message = "Patient details are not received.";
        $alertType = "warning";
    }
} else {
    $message = "No Patient details received.";
    $alertType = "info";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Patient Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f0f2f5; display:flex; justify-content:center; align-items:center; height:100vh;">
    <div class="container text-center">
        <div class="alert alert-<?php echo $alertType; ?>" role="alert">
            <?php echo $message; ?>
        </div>
        <a href="home.html" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>
