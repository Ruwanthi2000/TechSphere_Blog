<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (!$input) {
        echo json_encode(["error" => "Invalid JSON input"]);
        exit;
    }
    
    $name = htmlspecialchars($input["name"] ?? "");
    $email = filter_var($input["email"] ?? "", FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($input["message"] ?? "");
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Invalid email format"]);
        exit;
    }
    
    $to = "your-email@example.com"; // Replace with your email
    $subject = "New Contact Form Message from $name";
    $headers = "From: $email\r\nReply-To: $email";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["success" => "Message sent successfully"]);
    } else {
        echo json_encode(["error" => "Message sending failed"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>