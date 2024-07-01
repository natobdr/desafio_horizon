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
        <form action="{!! route('searchFlights') !!}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="trecho">TRECHO</label>
                    <select class="form-control" id="trecho" name="trecho">
                        <option value="0" selected>Ida e volta</option>
                        <option value="1">Só ida</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="origin">SAINDO DE</label>
                    <select class="form-control" id="origin" name="origin" required>
                        <option value="">De onde você deseja partir?  </option>
                        @foreach($aeroportos as $aeroporto)
                            <option value="{!! $aeroporto['id'] !!}">{{ $aeroporto['codigo_iata'] }} - {{ $aeroporto['nome'] }} - {{ $aeroporto['cidade']['nome'] }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="destination">INDO PARA</label>
                    <select class="form-control" id="destination" name="destination" required>
                        <option value="">Para onde você quer ir?  </option>
                        @foreach($aeroportos as $aeroporto)
                            <option value="{!! $aeroporto['id'] !!}">{{ $aeroporto['codigo_iata'] }} - {{ $aeroporto['nome'] }} - {{ $aeroporto['cidade']['nome'] }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row" id="oferta">
                <div class="form-group col-md-6">
                    <label for="departure_date">DATA DA VIAGEM DE IDA</label>
                    <input type="date" class="form-control" id="departure_date" name="departure_date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group col-md-6" id="data_volta">
                    <label for="return_date">DATA DA VIAGEM DE VOLTA</label>
                    <input type="date" class="form-control" id="return_date" name="return_date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            <div class="form-row" >
                <div class="form-group col-md-6">
                    <button type="submit" class="btn btn-primary ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">BUSCAR VOOS</button>
                </div>
            </div>
        </form>
    </section>
    <script>
        $('#trecho').change(function() {
            var trechoValue = $(this).val();
            if(trechoValue == "1"){
                $('#data_volta').hide();
                $('#return_date').removeAttr('required');
            }else {
                $('#data_volta').show();
            }

        });

        $('#going-to').change(function() {
            var destino = $(this).val();
            var origem  = $('#origin').val()
            if(destino == origem){
                alert("Origem e Destino não podem ser o mesmo aeroporto!")
                $(this).val("");
            }
        });
    </script>
</body>
</html>