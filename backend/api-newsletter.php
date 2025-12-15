<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid security token. Please refresh the page and try again.'
    ]);
    exit;
}

// Sanitize and validate email
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode([
        'success' => false, 
        'message' => 'Please enter a valid email address.'
    ]);
    exit;
}

// Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    error_log("Newsletter DB Connection Failed: " . $conn->connect_error);
    echo json_encode([
        'success' => false, 
        'message' => 'Service temporarily unavailable. Please try again later.'
    ]);
    exit;
}

// Check if email already exists
$checkStmt = $conn->prepare("SELECT id, is_active FROM newsletter_subscribers WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    $existing = $result->fetch_assoc();
    
    if ($existing['is_active']) {
        echo json_encode([
            'success' => false, 
            'message' => 'This email is already subscribed to our newsletter.'
        ]);
    } else {
        // Reactivate subscription
        $updateStmt = $conn->prepare("UPDATE newsletter_subscribers SET is_active = TRUE, subscribed_at = NOW() WHERE email = ?");
        $updateStmt->bind_param("s", $email);
        
        if ($updateStmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Welcome back! Your subscription has been reactivated.'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'An error occurred. Please try again.'
            ]);
        }
        $updateStmt->close();
    }
    
    $checkStmt->close();
    $conn->close();
    exit;
}

$checkStmt->close();

// Insert new subscriber
$insertStmt = $conn->prepare("INSERT INTO newsletter_subscribers (email, subscribed_at, is_active) VALUES (?, NOW(), TRUE)");
$insertStmt->bind_param("s", $email);

if ($insertStmt->execute()) {
    $subscriberId = $conn->insert_id;
    
    // Send welcome email (optional)
    sendWelcomeEmail($email);
    
    // Send notification to admin
    sendAdminNotification($email, $subscriberId);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you for subscribing! You\'ll receive our latest RCM insights and industry updates.'
    ]);
} else {
    error_log("Newsletter Insert Failed: " . $insertStmt->error);
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred. Please try again or contact us directly.'
    ]);
}

$insertStmt->close();
$conn->close();

/**
 * Send welcome email to new subscriber
 */
function sendWelcomeEmail($email) {
    require_once '../vendor/autoload.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
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
        $mail->setFrom(SMTP_USER, 'IntelliRCM');
        $mail->addAddress($email);
        
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to IntelliRCM Insights!';
        $mail->Body = getWelcomeEmailTemplate($email);
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Newsletter Welcome Email Failed: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Send notification to admin about new subscriber
 */
function sendAdminNotification($email, $subscriberId) {
    require_once '../vendor/autoload.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
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
        
        $mail->isHTML(true);
        $mail->Subject = "New Newsletter Subscriber (ID: $subscriberId)";
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px;'>
                <h2 style='color: #E63946;'>New Newsletter Subscription</h2>
                <p><strong>Subscriber ID:</strong> $subscriberId</p>
                <p><strong>Email:</strong> <a href='mailto:$email'>$email</a></p>
                <p><strong>Subscribed:</strong> " . date('F j, Y g:i A') . "</p>
                <hr>
                <p style='color: #666; font-size: 12px;'>This notification was sent from the IntelliRCM website newsletter signup form.</p>
            </div>
        ";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Newsletter Admin Notification Failed: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Get welcome email HTML template
 */
function getWelcomeEmailTemplate($email) {
    $unsubscribeLink = SITE_URL . "/unsubscribe.php?email=" . urlencode($email);
    
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to IntelliRCM Insights</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #0A0E1A;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0A0E1A; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #151923; border-radius: 20px; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #E63946 0%, #9D4EDD 100%); padding: 40px; text-align: center;">
                            <img src="https://www.intellircm.com/wp-content/uploads/2021/03/IntelliRCM-Logo-1.png" alt="IntelliRCM" style="height: 50px; margin-bottom: 20px;">
                            <h1 style="color: white; margin: 0; font-size: 32px;">Welcome to Our Community!</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px; color: #B4B9C8;">
                            <h2 style="color: #00D9FF; margin-top: 0;">Thank You for Subscribing!</h2>
                            <p style="font-size: 16px; line-height: 1.8; margin-bottom: 20px;">
                                You're now part of an exclusive community receiving expert insights on revenue cycle management, medical coding best practices, denial management strategies, and industry trends.
                            </p>
                            
                            <div style="background-color: #0A0E1A; padding: 25px; border-radius: 15px; margin: 30px 0;">
                                <h3 style="color: #FFFFFF; margin-top: 0;">What to Expect:</h3>
                                <ul style="color: #B4B9C8; line-height: 2;">
                                    <li>📊 Monthly RCM performance insights</li>
                                    <li>💡 Coding and compliance updates</li>
                                    <li>🎯 Denial prevention strategies</li>
                                    <li>📈 Industry trends and best practices</li>
                                    <li>🔥 Exclusive webinar invitations</li>
                                </ul>
                            </div>
                            
                            <p style="font-size: 16px; line-height: 1.8; margin-bottom: 30px;">
                                In the meantime, explore our latest resources:
                            </p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="https://www.intellircm.com/blog" style="display: inline-block; background: linear-gradient(135deg, #E63946, #C62936); color: white; padding: 15px 40px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 16px;">Read Our Latest Articles</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 16px; line-height: 1.8; margin-top: 30px;">
                                Have questions? We're here to help. Reply to this email or call us at <a href="tel:+13177081048" style="color: #00D9FF; text-decoration: none;">+1 (317) 708-1048</a>.
                            </p>
                            
                            <p style="font-size: 16px; line-height: 1.8; margin-bottom: 0;">
                                <strong style="color: #FFFFFF;">The IntelliRCM Team</strong><br>
                                <span style="font-size: 14px; color: #7A8199;">Better Revenue Starts Here</span>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #0A0E1A; padding: 30px; text-align: center; border-top: 1px solid #252B3B;">
                            <p style="color: #7A8199; font-size: 14px; margin: 0 0 10px 0;">
                                IntelliRCM, a brand of Mangalam Information Technologies Pvt. Ltd.<br>
                                Houston, TX | New York, NY | Ahmedabad, India
                            </p>
                            <p style="color: #7A8199; font-size: 12px; margin: 10px 0;">
                                <a href="$unsubscribeLink" style="color: #00D9FF; text-decoration: none;">Unsubscribe</a> | 
                                <a href="https://www.intellircm.com/privacy-policy" style="color: #00D9FF; text-decoration: none;">Privacy Policy</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}
?>
