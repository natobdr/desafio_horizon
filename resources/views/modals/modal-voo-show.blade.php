<!-- Modal -->
<div class="modal fade" id="show_{{ $voo['id'] }}" tabindex="-1" role="dialog" aria-labelledby="viewTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="enviar-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTitle">Visualizar Voo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Numero:</strong> {!! $voo['numero'] !!}</p>
                    <p><strong>Aeroporto de Origem:</strong> {!! $voo['aeroportoOrigem']['nome'] . ' - ' . $voo['aeroportoOrigem']['cidade']['nome'] !!}</p>
                    <p><strong>Aeroporto de Destino:</strong> {!! $voo['aeroportoDestino']['nome'] . ' - ' . $voo['aeroportoDestino']['cidade']['nome'] !!}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>
