<?php

// Database connection
$conn = mysqli_connect("localhost", 'root', "", "nlc_patient_details");

// Check connection
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Handle form submission for login
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare SQL statement to retrieve hashed password
    $stmt = $conn->prepare("SELECT password FROM login WHERE username = ?");

    if ($stmt === false) {
        die("Statement Error: " . $conn->error);
    }

    // Bind parameter and execute statement
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind result variable
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            header("Location:home.html");
            exit();
        } else {
            echo "Invalid Password";
        }
    } else {
        echo "Invalid Username or Password";
    }

    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();
} else {
    // Handle invalid request method
    echo "Invalid Request Method.";
}
