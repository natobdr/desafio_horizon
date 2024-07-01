@extends('layouts.app')

@section('template_title', 'Cadastrar Voo')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="danger" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="container">
        <div class="card">
            <div class="card-header">Cadastrar Voo</div>
            <div class="card-body">
                <form method="POST" action="{{ route('voos.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="aeroporto_origem">Aeroporto de Origem</label>
                        <select class="form-control" id="aeroporto_origem" name="aeroporto_origem_id" required>
                            <option value="0" selected>Selecione o aeroporto de origem</option>
                            @foreach($aeroportos as $aeroporto)
                                <option value="{{ $aeroporto->id }}">{{ $aeroporto->nome }} - {{ $aeroporto->cidade->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aeroporto_destino">Aeroporto de Destino</label>
                        <select class="form-control" id="aeroporto_destino" name="aeroporto_destino_id" required>
                            <option value="0" selected>Selecione o aeroporto de destino</option>
                            @foreach($aeroportos as $aeroporto)
                                <option value="{{ $aeroporto->id }}">{{ $aeroporto->nome }} - {{ $aeroporto->cidade->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="data_hora_partida">Data e Hora de Partida</label>
                        <input type="datetime-local" class="form-control" id="data_hora_partida" name="data_hora_partida" required>
                    </div>
                    </br>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
@endsection