<!DOCTYPE html>
<html>
<head>
    <title>Boleta de Pago</title>
    <style>
        body {
            width: 302px;
            height: 100%;
            margin: auto;
            font-family: Arial, sans-serif;
        }

        @media print {
            @page { margin: 0; }
            body { margin: 1.6cm; }
        }

        img.header-image {
            display: block;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-top: 10px; /* Ajusta este valor para el espacio entre la imagen y el título */
            font-size: 24px;
        }

        .label {
            font-weight: bold;
        }

        .details {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .details p {
            margin: 5px 0;
        }

        h2, h4 {
            text-align: center;
            font-size: 18px;
        }

        h4 {
            margin-top: -15px;
        }

        h3 {
            margin-top: 10px;
            font-size: 16px;
        }

        hr {
            border: none;
            border-top: 1px solid #000;
        }

        .total {
            display: flex;
            justify-content: space-between;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .total span {
            text-align: center;
        }
    </style>
</head>
<body onload="window.print()">
    <img class="header-image" src="https://www.iestpacora.edu.pe/wp-content/uploads/2020/09/logo-iestp-acora.jpg" alt="Logo" width="150">
    <h1>Boleta de Pago</h1>
    <hr/>
    @foreach ($pagos as $pago)
        <h2>Código de Operación: {{ $pago->operation_id }}</h2>
        <h4>Código de Pago: {{ $pago->id }}</h4>
        <div class="details">
            <div>
                <p><span class="label">DNI:</span> *****{{ substr($pago->dni, -3) }}</p>
                <p><span class="label">Usuario:</span> {{ $pago->user_name }}</p>
                <p><span class="label">Hora de Pago:</span> {{ $pago->payment_time }}</p>
                <p><span class="label">Fecha de Pago:</span> {{ $pago->payment_date }}</p>
            </div>
            <div>
                <h3>Detalles de la Operación:</h3>
                @foreach ($pago->paymentDetails as $detalle)
                    <p><span class="label">Concepto:</span> {{ $detalle->concept->concept_name }}</p>
                    <p><span class="label">Importe(S/.):</span> {{ number_format($pago->importe, 2) }}</p>
                @endforeach
            </div>
        </div>
        <hr/>
    @endforeach
    <div class="total">
        <span>Importe total:</span>
        <span>S/. {{ number_format($importeTotal , 2) }}</span>
    </div>
</body>
</html>
