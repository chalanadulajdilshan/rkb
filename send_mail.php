<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = isset($_POST["name"]) ? trim($_POST["name"]) : '';
  $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
  $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : '';
  $subject = isset($_POST["subject"]) ? trim($_POST["subject"]) : '';
  $message = isset($_POST["message"]) ? trim($_POST["message"]) : '';
  $honeypot = isset($_POST["website"]) ? trim($_POST["website"]) : '';

  // Honeypot check (should remain empty for humans)
  if ($honeypot !== '') {
    // Silently pretend success to not tip off bots
    echo "<script>alert('Message sent successfully!'); window.history.back();</script>";
    exit;
  }

  if ($name === '' || $email === '' || $phone === '' || $subject === '' || $message === '') {
    echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
    exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Please provide a valid email address.'); window.history.back();</script>";
    exit;
  }

  if (mb_strlen($name) < 2) {
    echo "<script>alert('Name must be at least 2 characters.'); window.history.back();</script>";
    exit;
  }
  if (mb_strlen($subject) < 3) {
    echo "<script>alert('Subject must be at least 3 characters.'); window.history.back();</script>";
    exit;
  }
  if (mb_strlen($message) < 10) {
    echo "<script>alert('Message must be at least 10 characters.'); window.history.back();</script>";
    exit;
  }
  if (!preg_match('/^[0-9+\-()\s]{7,}$/', $phone)) {
    echo "<script>alert('Please enter a valid phone number.'); window.history.back();</script>";
    exit;
  }

  $safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
  $safe_email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
  $safe_phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
  $safe_subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
  $safe_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

  $to = "info@rkb-global.com";
  $email_subject = "New Contact Form Submission: " . $safe_subject;
  $email_body = "Name: {$safe_name}\r\nEmail: {$safe_email}\r\nPhone: {$safe_phone}\r\n\r\nMessage:\r\n{$safe_message}";

  $headers = '';
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
  // Use a domain address in From to pass SPF/DMARC; keep user in Reply-To
  $headers .= "From: RKB Global Academy <info@rkb-global.com>\r\n";
  $headers .= "Reply-To: {$safe_email}\r\n";
  $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

  // Encode subject in case of non-ASCII
  $encoded_subject = '=?UTF-8?B?' . base64_encode($email_subject) . '?=';

  if (mail($to, $encoded_subject, $email_body, $headers)) {
    echo "<script>alert('Message sent successfully! We will get back to you soon.'); window.history.back();</script>";
  } else {
    echo "<script>alert('We could not send your message at the moment. Please try again later or email info@rkb-global.com.'); window.history.back();</script>";
  }
}
?>
