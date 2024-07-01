<!-- Modal -->
<div class="modal fade" id="edit_{{ $voo['id'] }}" tabindex="-1" role="dialog" aria-labelledby="editTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('voos.update', $voo['id']) }}" enctype="multipart/form-data" class="enviar-form">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editTitle">Editar Voo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="numero">Número do Voo</label>
                        <input type="text" class="form-control" id="numero" name="numero" value="{{ $voo['numero'] }}" required>
                    </div>
                    <div class="form-group">
                        <label for="aeroporto_origem">Aeroporto de Origem</label>
                        <select class="form-control" id="aeroporto_origem" name="aeroporto_origem_id" required>
                            @foreach($aeroportos as $aeroporto)
                                <option value="{{ $aeroporto['id'] }}" {{ $voo['aeroporto_origem']['id'] == $aeroporto['id'] ? 'selected' : '' }}>
                                    {{ $aeroporto['nome'] }} - {{ $aeroporto['nome'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aeroporto_destino">Aeroporto de Destino</label>
                        <select class="form-control" id="aeroporto_destino" name="aeroporto_destino_id" required>
                            @foreach($aeroportos as $aeroporto)
                                <option value="{{ $aeroporto['id'] }}" {{ $voo['aeroporto_destino']['id'] == $aeroporto['id'] ? 'selected' : '' }}>
                                    {{ $aeroporto['nome'] }} - {{ $aeroporto['nome'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="data_hora_partida">Data e Hora de Partida</label>
                                <input type="datetime-local" class="form-control" id="data_hora_partida" name="data_hora_partida" value="{{ \Carbon\Carbon::parse($voo['data_hora_partida'])->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>