<?php
session_start();
require_once 'config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh the page and try again.']);
    exit;
}

// Sanitize and validate inputs
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

$firstName = clean($_POST['firstName'] ?? '');
$lastName = clean($_POST['lastName'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = clean($_POST['phone'] ?? '');
$practiceType = clean($_POST['practiceType'] ?? '');
$specialty = clean($_POST['specialty'] ?? '');
$interest = clean($_POST['interest'] ?? '');
$message = clean($_POST['message'] ?? '');

// Validation
if (empty($firstName) || empty($lastName) || !$email || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection error. Please try again later.']);
    exit;
}

// Check for duplicate email
$checkStmt = $conn->prepare("SELECT id, created_at FROM leads WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    $existing = $result->fetch_assoc();
    $created = new DateTime($existing['created_at']);
    $now = new DateTime();
    $daysSince = $now->diff($created)->days;
    
    if ($daysSince < 30) {
        echo json_encode([
            'success' => false, 
            'message' => 'We already have your information. Our team will contact you soon!'
        ]);
        $checkStmt->close();
        $conn->close();
        exit;
    }
}
$checkStmt->close();

// Insert lead into database
$stmt = $conn->prepare(
    "INSERT INTO leads (first_name, last_name, email, phone, practice_type, specialty, interest, message, created_at) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())"
);
$stmt->bind_param("ssssssss", $firstName, $lastName, $email, $phone, $practiceType, $specialty, $interest, $message);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please call us at +1 (317) 708-1048']);
    $stmt->close();
    $conn->close();
    exit;
}

$leadId = $conn->insert_id;
$stmt->close();
$conn->close();

// Send email notification using PHPMailer
$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;
    
    // Email content
    $mail->setFrom(SMTP_USER, 'IntelliRCM Website');
    $mail->addAddress('rcmsales@mangalaminfotech.com');
    $mail->addReplyTo($email, "$firstName $lastName");
    
    $mail->isHTML(true);
    $mail->Subject = "New Contact Form Submission - IntelliRCM (Lead #$leadId)";
    $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px;'>
            <h2 style='color: #E63946;'>New Contact Form Submission</h2>
            <p><strong>Lead ID:</strong> $leadId</p>
            <hr>
            <h3>Contact Information</h3>
            <p><strong>Name:</strong> $firstName $lastName</p>
            <p><strong>Email:</strong> <a href='mailto:$email'>$email</a></p>
            <p><strong>Phone:</strong> <a href='tel:$phone'>$phone</a></p>
            
            <h3>Practice Details</h3>
            <p><strong>Practice Type:</strong> $practiceType</p>
            <p><strong>Specialty:</strong> $specialty</p>
            <p><strong>Interest:</strong> $interest</p>
            
            <h3>Message</h3>
            <p style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>$message</p>
            
            <hr>
            <p style='color: #666; font-size: 12px;'><strong>Submitted:</strong> " . date('F j, Y g:i A') . "</p>
        </div>
    ";
    
    $mail->send();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you for reaching out! Our team will contact you within 24 hours to discuss how we can help optimize your revenue cycle.'
    ]);
    
} catch (Exception $e) {
    // Even if email fails, lead is saved
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you! Your information has been received. We will contact you within 24 hours.'
    ]);
}
?>
