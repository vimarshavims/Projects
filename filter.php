<?php
$conn = new mysqli("localhost", "root", "", "nlc_patient_details");
if ($conn->connect_error) { die("Connection Failed: " . $conn->connect_error); }

$search = "";
if(isset($_POST['search'])) {
    $search = $_POST['search'];
}
$query = "SELECT * FROM update_detail WHERE NAME_FIRST LIKE '%$search%' OR NAME_LAST LIKE '%$search%'";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Filter Patients - NLC Hospital</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f0f2f5; padding: 20px; }
        .table-container { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-search { background: #fda085; border: none; color: #fff; }
        .btn-search:hover { background: #f6d365; }
    </style>
</head>
<body>
    <div class="container table-container">
        <h2 class="text-center mb-4">Filter Patients</h2>
        <form method="post" class="form-inline mb-3">
            <input type="text" name="search" class="form-control mr-2" placeholder="Search by First or Last Name" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-search">Search</button>
        </form>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Patient ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Contact</th>
                    <th>Gender</th>
                    <th>Relation</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<tr>
                            <td>{$row['ID_NO_NAME']}</td>
                            <td>{$row['NAME_FIRST']}</td>
                            <td>{$row['NAME_LAST']}</td>
                            <td>{$row['DATE_BIRTH']}</td>
                            <td>{$row['CELLPHONE_1_NR']}</td>
                            <td>{$row['SEX']}</td>
                            <td>{$row['RELATION']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No Records Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="home.html" class="btn btn-primary mt-3">Back to Home</a>
    </div>
</body>
</html>
