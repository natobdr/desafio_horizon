<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voos') }}
        </h2>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="danger" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="card">
            <div class="card-header">Editar Voo</div>
            <div class="card-body">
                <form method="POST" action="{{ route('voos.update', $voo['id']) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="aeroporto_origem">Aeroporto de Origem</label>
                        <select class="form-control" id="aeroporto_origem" name="aeroporto_origem_id" required>
                            <option value="0" selected>Selecione o aeroporto de origem</option>
                            @foreach($aeroportos as $aeroporto)
                                <option value="{{ $aeroporto['id'] }}" {{ $voo['aeroporto_origem_id'] == $aeroporto['id'] ? 'selected' : '' }}>
                                    {{ $aeroporto['nome'] }} - {{ $aeroporto['cidade']['nome'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aeroporto_destino">Aeroporto de Destino</label>
                        <select class="form-control" id="aeroporto_destino" name="aeroporto_destino_id" required>
                            <option value="0" selected>Selecione o aeroporto de destino</option>
                            @foreach($aeroportos as $aeroporto)
                                <option value="{{ $aeroporto['id'] }}" {{ $voo['aeroporto_destino_id'] == $aeroporto['id'] ? 'selected' : '' }}>
                                    {{ $aeroporto['nome'] }} - {{ $aeroporto['cidade']['nome'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="data_hora_partida">Data e Hora de Partida</label>
                        <input type="datetime-local" class="form-control" id="data_hora_partida" name="data_hora_partida" value="{{ \Carbon\Carbon::parse($voo['data_hora_partida'])->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div>
                        <label for="classe_id[]">Selecione as classes do voo</label>
                    </div>
                    <div class="form-group">
                        <select name="classe_id[]" multiple>
                            @foreach($classes as $classe)
                                <option value="{{ $classe['id'] }}"
                                        {{ in_array($classe['id'], array_column($voo['classes'], 'id')) ? 'selected' : '' }}>
                                    {{ $classe['tipo_classe']['tipo'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    </br>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
