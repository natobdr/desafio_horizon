<!DOCTYPE html>
<html lang="en">
<head>
    <title>Voucher e Etiqueta de Bagagem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .details {
            flex: 1;
        }
        .qr-code {
            margin-left: 20px;
        }
        .qr-code img {
            width: 100px; /* Ajuste o tamanho da imagem conforme necessário */
            height: 100px; /* Ajuste o tamanho da imagem conforme necessário */
        }
    </style>
</head>
<body>
<div class="etiqueta">
    <h2>Etiqueta de Bagagem</h2>
    <div class="info"><strong>Nome do Passageiro:</strong> {{ $dados[1]['nome'] }}</div>
    <div class="info"><strong>Número da Bagagem:</strong> {{ $dados[0]['numero_identificacao'] }}</div>
    <div class="qr-code">
        <h4>QR Code:</h4>
        <img src="{{ $filePath }}" alt="QR Code">
    </div>
</div>
</body>
</html>