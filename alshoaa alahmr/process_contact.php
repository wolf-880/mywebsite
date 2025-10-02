<?php
require_once 'includes/config.php';
require_once 'includes/contact_handler.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    
    // Validate form data
    if (empty($name)) {
        $response['message'] = 'Please enter your name.';
    } elseif (empty($email)) {
        $response['message'] = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
    } elseif (empty($message)) {
        $response['message'] = 'Please enter your message.';
    } else {
        // Save message to database
        if (saveContactMessage($name, $email, $subject, $message)) {
            $response['success'] = true;
            $response['message'] = 'Thank you for your message. We will get back to you soon.';
            
            // Optional: Send email notification
            $to = CONTACT_EMAIL;
            $emailSubject = 'New Contact Form Submission: ' . $subject;
            $emailBody = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message";
            $headers = "From: $email";
            
            // Uncomment to enable email sending
            // mail($to, $emailSubject, $emailBody, $headers);
        } else {
            $response['message'] = 'Sorry, there was an error saving your message. Please try again later.';
        }
    }
}

// Return JSON response for AJAX requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Redirect for regular form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set session flash message
    $_SESSION['contact_form_message'] = $response['message'];
    $_SESSION['contact_form_success'] = $response['success'];
    
    // Redirect back to contact page
    header('Location: contact.html');
    exit;
}
