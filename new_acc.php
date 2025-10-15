<?php

// Database connection
$conn = mysqli_connect("localhost", 'root', "", "nlc_patient_details");

// Check connection
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO login(username, password) VALUES (?, ?)");

    if ($stmt === false) {
        die("Statement Error: " . $conn->error);
    }

    // Bind parameters and execute statement
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location:home.html");
        exit();
    } else {
        echo "Execution Failed: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();
} else {
    // Handle invalid request method
    echo "Invalid Request Method.";
}
