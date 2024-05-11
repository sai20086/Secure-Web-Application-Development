<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    // Validate form data (e.g., check if fields are not empty)
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } else {
        // Sanitize user inputs
        $username = htmlspecialchars($username);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // Hash the password for storage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Connect to the database (replace with your database credentials)
        $conn = new mysqli("localhost", "root", "", "mywebapp_db");
        
        // Check database connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Prepare SQL statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        
        // Bind parameters to the prepared statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        // Close the prepared statement and database connection
        $stmt->close();
        $conn->close();
    }
}
?>
