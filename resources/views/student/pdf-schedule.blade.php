<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario de Clases</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1e3a8a; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e3a8a; margin-bottom: 5px; }
        .title { font-size: 18px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8fafc; color: #1e3a8a; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
        .time { color: #3b82f6; font-weight: bold; font-size: 11px; }
        .subject { font-weight: bold; font-size: 14px; margin: 4px 0; }
        .room { color: #64748b; font-size: 12px; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #94a3b8; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">UniPortal Global Education</div>
        <div class="title">Horario Oficial de Clases - Período Académico</div>
        <div style="font-size: 12px; color: #666; margin-top: 10px;">{{ now()->translatedFormat('l, d de F de Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="25%">Horario</th>
                <th width="50%">Asignatura</th>
                <th width="25%">Aula / Laboratorio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedule as $class)
                <tr>
                    <td><div class="time">{{ $class['time'] }}</div></td>
                    <td><div class="subject">{{ $class['subject'] }}</div></td>
                    <td><div class="room">{{ $class['room'] }}</div></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Este documento es un comprobante electrónico válido de su horario de clases para el período actual.<br>
        Generado automáticamente por UniPortal el {{ now()->format('d/m/Y H:i') }}.
    </div>
</body>
</html>
