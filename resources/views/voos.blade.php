<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery (necessary for Select2) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <![endif]-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var cleave = new Cleave('#cpf', {
                delimiters: ['.', '.', '-'],
                blocks: [3, 3, 3, 2],
                numericOnly: true
            });
        });
    </script>

</head>
<body class="antialiased">
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div class="relative sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white container">
    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>
        </div>
    @endif
    <br/>
    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
        <a href="{{ route('searchticket') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Imprimir Passagem</a>
    </div>
    <section class="booking-area">
        <form id="searchFlightsForm" action="{!! route('registerCustomer') !!}" method="post">
            @csrf
            <div class="card">
                <div class="card-header">
                    Lista de Voos
                </div>
                <div id="clientList"></div>
                <div class="card-body" id="table">
                    <div class="table-responsive">
                        <span class="badge badge-primary pull-right">Voo de Ida</span>
                        <table class="table table-striped table-sm data-table" id="relatorio-ida">
                            <thead class="thead-dark">
                            <tr class="success">
                                <th></th>
                                <th>Nº Voo</th>
                                <th>Origem</th>
                                <th>Destino</th>
                                <th>Data</th>
                                <th>Classe</th>
                                <th>Valor</th>
                                <th>Quantidade de Assentos</th>
                                <th>Valor Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($voosIda as $vooIda)
                                @foreach($vooIda['classes'] as $classe)
                                    <tr>
                                        <td>
                                            <input class="checkbox-cliente-ida" type="checkbox" name="voosIda[]" value="{{ $vooIda['id'] }}_{{ $classe['id'] }}">
                                        </td>
                                        <td class="numero-voo">{!! $vooIda['numero'] !!}</td>
                                        <td class="origem">{!! $vooIda['aeroporto_origem']['codigo_iata'] . ' - ' . $vooIda['aeroporto_origem']['nome'] . ' - ' . $vooIda['aeroporto_origem']['cidade']['nome'] !!}</td>
                                        <td class="destino">{!! $vooIda['aeroporto_destino']['codigo_iata'] . ' - ' . $vooIda['aeroporto_destino']['nome'] . ' - ' . $vooIda['aeroporto_destino']['cidade']['nome'] !!}</td>
                                        <td class="data">{!! \Carbon\Carbon::parse($vooIda['data_hora_partida'])->format('d/m/y H:i') !!}</td>
                                        <td class="classe">{!! $classe['tipo_classe']['tipo'] !!}</td>
                                        <td class="valor-assento" data-valor="{{ $classe['valor_assento'] }}">R$ {!! $classe['valor_assento'] !!}</td>
                                        <td>
                                            <input type="number" class="form-control quantidade-assentos-ida" min="1" value="1" data-valor="{{ $classe['valor_assento'] }}">
                                        </td>
                                        <td class="valor-total-ida">R$ {!! $classe['valor_assento'] !!}</td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="9">Nenhum Voo Encontrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <span class="badge badge-primary pull-right">Voo de Volta</span>
                        <table class="table table-striped table-sm data-table" id="relatorio-retorno">
                            <thead class="thead-dark">
                            <tr class="success">
                                <th></th>
                                <th>Nº Voo</th>
                                <th>Origem</th>
                                <th>Destino</th>
                                <th>Data</th>
                                <th>Classe</th>
                                <th>Valor</th>
                                <th>Quantidade de Assentos</th>
                                <th>Valor Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($voosRetorno as $vooRetorno)
                                @foreach($vooRetorno['classes'] as $classe)
                                    <tr>
                                        <td>
                                            <input class="checkbox-cliente-volta" type="checkbox" name="voosRetorno[]" value="{{ $vooRetorno['id'] }}_{{ $classe['id'] }}">
                                        </td>
                                        <td class="numero-voo">{!! $vooRetorno['numero'] !!}</td>
                                        <td class="origem">{!! $vooRetorno['aeroporto_origem']['codigo_iata'] . ' - ' . $vooRetorno['aeroporto_origem']['nome'] . ' - ' . $vooRetorno['aeroporto_origem']['cidade']['nome'] !!}</td>
                                        <td class="destino">{!! $vooRetorno['aeroporto_destino']['codigo_iata'] . ' - ' . $vooRetorno['aeroporto_destino']['nome'] . ' - ' . $vooRetorno['aeroporto_destino']['cidade']['nome'] !!}</td>
                                        <td class="data">{!! \Carbon\Carbon::parse($vooRetorno['data_hora_partida'])->format('d/m/y H:i') !!}</td>
                                        <td class="classe">{!! $classe['tipo_classe']['tipo'] !!}</td>
                                        <td class="valor-assento" data-valor="{{ $classe['valor_assento'] }}">R$ {!! $classe['valor_assento'] !!}</td>
                                        <td>
                                            <input type="number" class="form-control quantidade-assentos-volta" min="1" value="1" data-valor="{{ $classe['valor_assento'] }}">
                                        </td>
                                        <td class="valor-total-volta">R$ {!! $classe['valor_assento'] !!}</td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="9">Nenhum Voo de Retorno Encontrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="customerForm">
                    <div class="SubTotal"></div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Reservar</button>
                </div>
            </div>
        </form>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateTotal() {
                let totalCompra = 0;
                $('.valor-total-ida, .valor-total-volta').each(function() {
                    totalCompra += parseFloat($(this).text().replace('R$', '').trim());
                });
                $('#total_compra').text('Total: R$ ' + totalCompra.toFixed(2));
            }

            function updateClientForms() {
                $('#customerForm').empty();
                $('.checkbox-cliente-ida:checked').each(function() {
                    let value = $(this).val();
                    let seats = parseInt($(this).closest('tr').find('.quantidade-assentos-ida, .quantidade-assentos-volta').val(), 10);

                    for (let i = 0; i < seats; i++) {
                        let formHtml = `
                        <div class="card">
                            <div class="card-header">Dados do Cliente </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nome_${value}_${i}">Nome</label>
                                    <input type="text" class="form-control" name="nome_${value}[]" id="nome_${value}_${i}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email_${value}_${i}">Email</label>
                                    <input type="email" class="form-control" name="email_${value}[]" id="email_${value}_${i}" required>
                                </div>
                                <div class="form-group">
                                    <label for="cpf_${value}_${i}">CPF</label>
                                    <input type="text" class="form-control cpf" name="cpf_${value}[]" id="cpf_${value}_${i}" required>
                                </div>
                                <div class="form-group">
                                    <label for="nasc_${value}_${i}">Data de Nascimento</label>
                                    <input type="date" class="form-control nasc" name="nasc_${value}[]" id="nasc_${value}_${i}" required>
                                </div>
                                <div class="form-group">
                                    <label for="bagagem_${value}_${i}">Incluir Bagagem?</label>
                                    <input type="checkbox" name="bagagem_${value}[]" class="incluir-bagagem">
                                </div>
                            </div>
                        </div>
                    `;
                        $('#customerForm').append(formHtml);
                    }
                });
            }

            $('.quantidade-assentos-ida, .quantidade-assentos-volta').change(function() {
                let valorAssento = parseFloat($(this).closest('tr').find('.valor-assento').data('valor'));
                let quantidadeAssentos = parseInt($(this).val(), 10);
                let valorTotal = valorAssento * quantidadeAssentos;

                if ($(this).closest('tr').find('.incluir-bagagem').is(':checked')) {
                    valorTotal += valorAssento * 0.10;
                }

                $(this).closest('tr').find('.valor-total').text('R$ ' + valorTotal.toFixed(2));
                updateClientForms();
                updateTotal();
            });

            $(document).on('change', '.incluir-bagagem', function() {
                let quantidadeAssentos = parseInt($('.quantidade-assentos-ida, .quantidade-assentos-volta').val(), 10);
                let valorAssento = parseFloat($(this).closest('tr').find('.valor-assento').data('valor'));
                let valorTotal = valorAssento * quantidadeAssentos;
                console.log(quantidadeAssentos);

                if ($(this).is(':checked')) {
                    valorTotal += valorAssento * 0.10;
                }

                $(this).closest('tr').find('.valor-total').text('R$ ' + valorTotal.toFixed(2));
                updateTotal();
            });

            $('.checkbox-cliente-ida, .checkbox-cliente-volta').change(function() {
                updateClientForms();
            });

            updateTotal();
        });
    </script>
</body>
</html>