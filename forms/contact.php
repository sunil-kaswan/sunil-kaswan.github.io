<?php
  // Contact form handler - Direct form submission to PHP
  echo 'Method Not Allowed';
    exit;
  // Set headers
  header('Content-Type: text/plain; charset=utf-8');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Content-Type');
  
  // Your email where messages will be sent
  $receiving_email_address = 'kaswansunil26@gmail.com';
  
  // Only accept POST requests
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
  }
  
  // Basic validation
  $name    = isset($_POST['name']) ? trim($_POST['name']) : '';
  $email   = isset($_POST['email']) ? trim($_POST['email']) : '';
  $subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'New Contact Message';
  $message = isset($_POST['message']) ? trim($_POST['message']) : '';
  
  if ($name === '' || $email === '' || $message === '') {
    http_response_code(400);
    echo 'Please fill in all required fields.';
    exit;
  }
  
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Please enter a valid email address.';
    exit;
  }
  
  // Build email content
  $email_subject = '[Portfolio Contact] ' . $subject;
  $email_body  = "You have received a new message from your website contact form.\n\n";
  $email_body .= "Name: " . $name . "\n";
  $email_body .= "Email: " . $email . "\n";
  $email_body .= "Subject: " . $subject . "\n\n";
  $email_body .= "Message:\n" . $message . "\n";
  
  // Try using PHPMailer if available
  $phpmailer_path = __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
  $use_phpmailer = file_exists($phpmailer_path);
  
  $success = false;
  
  if ($use_phpmailer) {
    try {
      require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
      require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
      require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
      
      use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\Exception;
      
      $mail = new PHPMailer(true);
      
      // SMTP Configuration (Update these with your email settings)
      // For Gmail: Use App Password (not regular password)
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'kaswansunil26@gmail.com';  // Your Gmail address
      $mail->Password   = 'rtzlehhixczhdkby';         // Gmail App Password (generate from Google Account settings)
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587;
      $mail->CharSet    = 'UTF-8';
      
      // Recipients
      $mail->setFrom($email, $name);
      $mail->addAddress($receiving_email_address, 'Sunil Kaswan');
      $mail->addReplyTo($email, $name);
      
      // Content
      $mail->isHTML(false);
      $mail->Subject = $email_subject;
      $mail->Body    = $email_body;
      
      $mail->send();
      $success = true;
    } catch (Exception $e) {
      // If PHPMailer fails, fall back to mail() function
      $success = false;
    }
  }
  
  // Fallback to simple mail() function if PHPMailer is not available or failed
  if (!$success) {
    $headers   = "From: " . $name . " <" . $email . ">\r\n";
    $headers  .= "Reply-To: " . $email . "\r\n";
    $headers  .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers  .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    $success = @mail($receiving_email_address, $email_subject, $email_body, $headers);
  }
  
  // Check if this is an AJAX request
  $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  
  // Return response
  if ($success) {
    if ($is_ajax) {
      // AJAX response
      echo 'OK';
    } else {
      // Traditional form submission - redirect back with success message
      header('Location: ../contact.html?status=success&message=' . urlencode('Your message has been sent successfully!'));
      exit;
    }
  } else {
    if ($is_ajax) {
      // AJAX error response
      http_response_code(500);
      echo 'Failed to send email. Please check your server configuration or PHPMailer settings.';
    } else {
      // Traditional form submission - redirect back with error message
      header('Location: ../contact.html?status=error&message=' . urlencode('Failed to send email. Please try again later.'));
      exit;
    }
  }
?>
