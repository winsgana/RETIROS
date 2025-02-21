<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Configuración del bot de Telegram
$TOKEN = "7957554764:AAHUzfquZDDVEiwOy_u292haqMmPK2uCKDI";  
$CHAT_ID = "-4633546693";  // Chat de administradores

// Solo aceptar solicitudes POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["message" => "Método no permitido"]);
    exit;
}

// Verificar que se haya subido un archivo
if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(["message" => "No se ha subido ningún archivo"]);
    exit;
}

// Para depuración: registrar contenido de $_FILES
// file_put_contents("files_debug.txt", print_r($_FILES, true));

$nombreArchivo = $_FILES["file"]["name"];
$rutaTemporal = $_FILES["file"]["tmp_name"];
$fecha = date('Y-m-d H:i:s');  

// Datos del usuario
$docNumber = isset($_POST['docNumber']) ? substr(trim($_POST['docNumber']), 0, 12) : "No proporcionado";
$monto = isset($_POST['monto']) ? trim($_POST['monto']) : "No proporcionado";

// URL de Telegram para enviar el mensaje con documento
$url = "https://api.telegram.org/bot$TOKEN/sendDocument";

// Texto del mensaje
$caption = "📎 *Nuevo comprobante de pago recibido:*\n\n" .
           "📝 *Archivo:* $nombreArchivo\n" .
           "📅 *Fecha:* $fecha\n" .
           "🪪 *Documento:* $docNumber\n" .
           "💰 *Monto:* $monto\n\n" .
           "🔔 *Marcar como pagado o rechazado:*";

// Botones de Pagado y Rechazado (para administradores)
$keyboard = json_encode([
    "inline_keyboard" => [
        [["text" => "✅ Pagado", "callback_data" => "pagado"]],
        [["text" => "❌ Rechazado", "callback_data" => "rechazado"]]
    ]
]);

// Preparar los datos para enviar
$postData = [
    "chat_id" => $CHAT_ID,
    "document" => new CURLFile($rutaTemporal, mime_content_type($rutaTemporal), $nombreArchivo),
    "caption" => $caption,
    "parse_mode" => "Markdown",
    "reply_markup" => $keyboard
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$curl_error = curl_error($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Para depuración: registrar la respuesta de Telegram en un archivo
file_put_contents("telegram_response.txt", "HTTP Status: $http_status\nResponse: $response\nCurl Error: $curl_error\n", FILE_APPEND);

if ($http_status != 200) {
    http_response_code(500);
    echo json_encode([
        "message"    => "❌ Error al enviar a Telegram",
        "curl_error" => $curl_error,
        "http_status"=> $http_status,
        "response"   => $response
    ]);
    exit;
}

echo json_encode(["message" => "✅ Comprobante enviado a administradores en Telegram"]);
?>
