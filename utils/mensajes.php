<?php
function mensajeRecepcion($fecha, $monto) {
    return "✅ ¡Recibimos tu solicitud de Retiro!\n" .
           "🗓 Fecha: $fecha\n" .
           "💰 Monto: $monto BOB\n\n" .
           "🔔 Estamos revisando tu solicitud. No te preocupes, te notificaremos una vez que esté completada.\n\n" .
           "🔔 Recuerda: este es un canal de notificaciones automáticas.";
}

function mensajeCompletado() {
    return "¡Es oficial! Dinero en mano, campeón.\n\n" .
           "Disfruta de tu premio y sigue apostando con la emoción de un verdadero campeón. En Winsgana, siempre hay más oportunidades de ganar.\n\n" .
           "🔔 Recuerda: este es un canal de notificaciones automáticas.";
}

function mensajeRechazado() {
    return "⚠️ Tu solicitud no pudo ser procesada.\n\n" .
           "Tu retiro ha sido rechazado porque no cumples con las políticas de Winsgana o tu cuenta aún no ha sido verificada.\n\n" .
           "📞 Contáctanos para más información:\n" .
           "📱 WhatsApp: +59162162190\n" .
           "📧 Correo: soporte@winsgana.com\n\n" .
           "🔔 Recuerda: este es un canal de notificaciones automáticas.";
}
?>
