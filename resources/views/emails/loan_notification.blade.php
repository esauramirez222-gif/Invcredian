<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #0F4E88;">Sistema de Inventario Credian</h2>
        <hr style="border: 1px solid #eee;">
    </div>

    @if($type === 'new_request')
        <h3 style="color: #33AD72;">¡Hay una nueva solicitud de préstamo!</h3>
        <p><strong>{{ $loan->applicant_name }} {{ $loan->applicant_last_name }}</strong> ha solicitado los siguientes equipos. Por favor ingresa al sistema para aprobar o rechazar la solicitud.</p>
    
    @elseif($type === 'approved')
        <h3 style="color: #33AD72;">¡Excelentes noticias, {{ $loan->applicant_name }}!</h3>
        <p>Tu solicitud de préstamo ha sido <strong>APROBADA</strong>. Ya puedes pasar a recoger los siguientes equipos:</p>
    
    @elseif($type === 'rejected')
        <h3 style="color: #e53e3e;">Hola, {{ $loan->applicant_name }}.</h3>
        <p>Lamentablemente tu solicitud de préstamo ha sido <strong>RECHAZADA</strong></p>
        <p>Esta es la lista de lo que habías solicitado:</p>

    @elseif($type === 'returned')
        <h3 style="color: #0F4E88;">Confirmación de Devolución</h3>
        <p>Hola, {{ $loan->applicant_name }}. Hemos registrado exitosamente la devolución de los siguientes equipos. ¡Gracias por cuidarlos!</p>
    @endif

    <!-- Lista de Equipos -->
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-top: 20px;">
        <h4 style="margin-top: 0; color: #4a5568;">Detalle de los equipos:</h4>
        <ul style="list-style-type: none; padding-left: 0;">
            @foreach($loan->items as $item)
                <li style="padding: 8px 0; border-bottom: 1px solid #edf2f7;">
                    <span style="font-weight: bold; color: #33AD72;">{{ $item->quantity }}x</span> 
                    {{ $item->resource->name }}
                </li>
            @endforeach
        </ul>
        @if($loan->notes)
            <p style="font-size: 14px; color: #718096; margin-top: 15px;"><strong>Motivo del préstamo:</strong> {{ $loan->notes }}</p>
        @endif
    </div>

    <p style="font-size: 12px; color: #a0aec0; text-align: center; margin-top: 30px;">
        Este es un correo automático, por favor no respondas a este mensaje.
    </p>
</body>
</html>