<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'فقط درخواست POST مجاز است']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// اعتبارسنجی ساده
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'success' => false, 
        'message' => 'لطفاً همه فیلدهای ضروری را پر کنید'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false, 
        'message' => 'فرمت ایمیل صحیح نیست'
    ]);
    exit;
}

// تنظیمات ایمیل
$to = 'your-email@gmail.com'; // ← ایمیل خودت رو بزار
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();

$body = "پیام جدید از وب‌سایت:\n\n";
$body .= "نام: $name\n";
$body .= "ایمیل: $email\n";
$body .= "موضوع: $subject\n";
$body .= "پیام:\n$message\n";

$subject_line = $subject ?: 'تماس از وب‌سایت';

if (mail($to, $subject_line, $body, $headers)) {
    echo json_encode([
        'success' => true, 
        'message' => 'پیام شما با موفقیت ارسال شد! به‌زودی پاسخ می‌دهم.'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'خطا در ارسال. لطفاً دوباره تلاش کنید.'
    ]);
}
?>
