<?php
// Step 1: Database Configuration
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "minds_club"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs and sanitize them
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $reason = htmlspecialchars(trim($_POST['reason']));

    // Validation
    if (empty($name) || empty($email) || empty($reason)) {
        echo "<h3>Error: All fields are required!</h3>";
        echo "<a href='membership.html'>Go back to the Membership Page</a>";
        exit;
    }

    // Step 3: Save to Database
    $stmt = $conn->prepare("INSERT INTO memberships (name, email, reason) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $reason);

    if ($stmt->execute()) {
        // Step 4: Send Email Notifications
        $to_admin = "mugiwaranodev56@gmail.com"; // Replace with the admin email
        $subject_admin = "New Membership Application";
        $message_admin = "A new member has applied:\n\nName: $name\nEmail: $email\nReason: $reason";
        mail($to_admin, $subject_admin, $message_admin);

        $subject_user = "Your MINDS Club Membership Application";
        $message_user = "Hi $name,\n\nThank you for applying to join MINDS Club. We have received your application and will review it shortly.";
        mail($email, $subject_user, $message_user);

        // Success Message
        echo "<h3>Thank you for applying, $name!</h3>";
        echo "<p>Your application has been submitted successfully.</p>";
        echo "<a href='index.html'>Return to Homepage</a>";
    } else {
        echo "<h3>Error: Unable to save your application. Please try again.</h3>";
    }
    $stmt->close();
}

$conn->close();
?>