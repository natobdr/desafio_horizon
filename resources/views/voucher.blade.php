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
<div class="voucher">
    <h1>Voucher de Passagem</h1>
    <div class="info"><strong>Nome do Passageiro:</strong> {{ $dados[1]['nome'] }}</div>
    <div class="info"><strong>Origem:</strong> {{ $dados[0]['voo']['aeroportoOrigem']['nome'] }}</div>
    <div class="info"><strong>Destino:</strong> {{ $dados[0]['voo']['aeroportoDestino']['nome'] }}</div>
    <div class="info"><strong>Classe:</strong> {{ $dados[2]['tipoClasse']['tipo'] }}</div>
    <div class="info"><strong>Data do Voo:</strong> {{ \Carbon\Carbon::parse($dados[0]['voo']['data_hora_partida'])->format('d-m-y H:i') }}</div>
    <div class="info"><strong>Valor da Passagem:</strong> {{ $dados[2]['valor_assento'] }}</div>
    <div class="qr-code">
        <h4>QR Code:</h4>
        <img src="{{ $filePath }}" alt="QR Code">
    </div>
</div>
</body>
</html>