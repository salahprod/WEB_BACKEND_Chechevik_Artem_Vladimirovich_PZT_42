<?php
// Полный обработчик CAR MUSC
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ПУТЬ К ПАПКЕ (как на твоем скрине)
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Данные
$name    = isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : 'Аноним';
$phone   = isset($_POST['user_phone']) ? htmlspecialchars($_POST['user_phone']) : 'Нет данных';
$message = isset($_POST['user_message']) ? htmlspecialchars($_POST['user_message']) : '';

$subject = "Car Musc: Заявка от $name";
$email_body = "<h3>Новая заявка Car Musc</h3><p><b>Имя:</b> $name</p><p><b>Телефон:</b> $phone</p><p><b>Вопрос:</b> $message</p>";

// ЗАДАНИЕ 1: mail()
$to = "artemcesevik655@gmail.com"; 
$headers  = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: info@carmusc.ru\r\n";
mail($to, $subject, $email_body, $headers);

// ЗАДАНИЕ 2: PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'artemcesevik655@gmail.com';     
    $mail->Password   = 'zhgz yplp ewvp zblj'; // Твой 16-значный код
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('artemcesevik655@gmail.com', 'Car Musc Detailing');
    
    // ЗАДАНИЕ 2.3: Рассылка
    $mail->addAddress('artemcesevik655@gmail.com');
    $mail->addAddress('serychboyfriend@gmail.com'); 

    // ЗАДАНИЕ 2.2: Вложение
    if (isset($_FILES['user_file']) && $_FILES['user_file']['error'] === UPLOAD_ERR_OK) {
        $mail->addAttachment($_FILES['user_file']['tmp_name'], $_FILES['user_file']['name']);
    }

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $email_body;

    $mail->send();
    echo "<h1 style='color:green; text-align:center;'>УСПЕШНО ОТПРАВЛЕНО!</h1>";
} catch (Exception $e) {
    echo "Ошибка: {$mail->ErrorInfo}";
}

header("Refresh: 3; url=index.html");
?>