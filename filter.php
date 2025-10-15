<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert or Update Patient Data</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #D5F5E3;
            color: #333;
        }

        header {
            background-color: #4caf50;
            padding: 25px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        header nav ul li {
            display: inline;
            margin: 0 15px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        header nav ul li a:hover {
            color: #ffdd57;
        }

        main {
            padding: 20px;
            max-width: auto;
            margin: 0 auto;
        }

        h1 {
            font-size: 2.5em;
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #E5E8E8;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #4CAF50;
        }

        select,
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .can {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
        }

        footer h2 {
            margin: 0;
            padding: 0;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header nav ul li {
                display: block;
                margin: 10px 0;
            }

            form,
            table {
                width: 100%;
            }

            main {
                padding: 10px;
            }

            table th,
            table td {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="home.html">HOME</a></li>
                <li><a href="form.html">Registration</a></li>
                <li><a href="filter.php">Filter</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="login.html">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main class="container">
        <h1>Insert or Update Patient Data</h1>

        <form id="operationForm" action="filter.php" method="post">
            <label for="operation">Select Operation:</label>
            <select id="operation" name="operation">
                <option value="">Select operation...</option>
                <option value="Insert">Insert</option>
                <option avalue="Update">Update</option>
            </select>
            <input type="submit" value="Submit">
        </form>

        <?php
        $conn = new mysqli("localhost", "root", "", "nlc_patient_details");

        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }

        $patients = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST["operation"])) {
                $operation = $_POST["operation"];
                $operation = $conn->real_escape_string($operation); // Sanitize the input

                $sql = "SELECT * FROM detail WHERE ID_NO_NAME in (SELECT LOG_PATIENT_NO FROM control WHERE LOG_TYPE='$operation' and LOG_PROCESSED_FLAG='Y')";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $patients[] = $row;
                    }
                }
            }
        }

        $conn->close();
        ?>
        <?php if (!empty($patients)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Date of Birth</th>
                        <th>Blood Group</th>
                        <th>Address</th>
                        <th>Pin Code</th>
                        <th>Contact Number 1</th>
                        <th>Contact Number 2</th>
                        <th>Gender</th>
                        <th>Title</th>
                        <th>Relative Name</th>
                        <th>Relation ID</th>
                        <th>Designation</th>
                        <th>Grade</th>
                        <th>Extension Create Date</th>
                        <th>Extension Validity Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($patient['ID_NO_NAME']); ?></td>
                            <td><?php echo htmlspecialchars($patient['NAME_FIRST']); ?></td>
                            <td><?php echo htmlspecialchars($patient['NAME_MIDDLE']); ?></td>
                            <td><?php echo htmlspecialchars($patient['NAME_LAST']); ?></td>
                            <td><?php echo htmlspecialchars($patient['DATE_BIRTH']); ?></td>
                            <td><?php echo htmlspecialchars($patient['BLOOD_GROUP']); ?></td>
                            <td><?php echo htmlspecialchars($patient['ADDR_STR']); ?></td>
                            <td><?php echo htmlspecialchars($patient['ADDR_STR_NR']); ?></td>
                            <td><?php echo htmlspecialchars($patient['CELLPHONE_1_NR']); ?></td>
                            <td><?php echo htmlspecialchars($patient['CELLPHONE_2_NR']); ?></td>
                            <td><?php echo htmlspecialchars($patient['SEX']); ?></td>
                            <td><?php echo htmlspecialchars($patient['TITLE']); ?></td>
                            <td><?php echo htmlspecialchars($patient['RELATION']); ?></td>
                            <td><?php echo htmlspecialchars($patient['USER_NAME']); ?></td>
                            <td><?php echo htmlspecialchars($patient['DESIGNATION']); ?></td>
                            <td><?php echo htmlspecialchars($patient['EMP_GRADE']); ?></td>
                            <td><?php echo htmlspecialchars($patient['EXTENSION_CREATE_DATE']); ?></td>
                            <td><?php echo htmlspecialchars($patient['EXTENSION_VALIDITY_DATE']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form id="patientForm" action="insert.php" method="post" style="display:none;">
                <input type="hidden" name="patients" value='<?php echo json_encode($patients); ?>'>
            </form>
            <form id="patientForm1" action="update.php" method="post" style="display:none;">
                <input type="hidden" name="patients" value='<?php echo json_encode($patients); ?>'>
            </form>
        <?php else : ?>
            <p style="text-align:center">No records found for the selected operation.</p>
        <?php endif; ?>
        <center>
            <div>
                <button type="button" onclick="submitPatientForm('Insert')">Insert</button>
                <button type="button" onclick="submitPatientForm('Update')">Update</button>
            </div>
        </center>
    </main>
    <footer>
        <h2>Thank You!</h2>
    </footer>
    <script>
        function submitPatientForm(action) {
            if (action === "Insert") {
                document.getElementById('patientForm').submit();
            }
            if (action === "Update") {
                document.getElementById("patientForm1").submit();
            }
        }
    </script>
</body>

</html>