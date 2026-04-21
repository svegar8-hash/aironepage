<?php
declare(strict_types=1);

$recipient = 'diego.sandoval@busesjm.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html#contacto');
    exit;
}

$nombre = trim((string) ($_POST['nombre'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$asunto = trim((string) ($_POST['asunto'] ?? ''));
$mensaje = trim((string) ($_POST['mensaje'] ?? ''));

$safeNombre = str_replace(["\r", "\n"], ' ', $nombre);
$safeEmail = filter_var($email, FILTER_SANITIZE_EMAIL) ?: '';
$safeAsunto = str_replace(["\r", "\n"], ' ', $asunto);

$mailSubject = $safeAsunto !== '' ? $safeAsunto : 'Formulario de contacto';
$mailBody = "Nombre: " . $safeNombre . PHP_EOL
    . "Email: " . $safeEmail . PHP_EOL
    . "Asunto: " . $safeAsunto . PHP_EOL . PHP_EOL
    . "Mensaje:" . PHP_EOL
    . $mensaje . PHP_EOL;

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: Consorcio Andino <no-reply@consorcioandino.cl>',
];

if ($safeEmail !== '' && filter_var($safeEmail, FILTER_VALIDATE_EMAIL)) {
    $headers[] = 'Reply-To: ' . $safeEmail;
}

@mail($recipient, $mailSubject, $mailBody, implode("\r\n", $headers));

header('Location: index.html#contacto');
exit;
