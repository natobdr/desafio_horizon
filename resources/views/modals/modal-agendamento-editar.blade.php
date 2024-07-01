<div class="modal fade" id="edit_{!!$agendamento->id!!}" tabindex="-1" role="dialog" aria-labelledby="editTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{!!route('cliente-edit')!!}" enctype="multipart/form-data" class="enviar-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editTitle">Editar Agendamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" class="form-control" value="{!!$agendamento->nome!!}">
                    </div>

                    <div class="form-group">
                        <label for="texto">Texto:</label>
                        <input type="text" id="texto" name="texto" class="form-control"
                               value="{!!$agendamento->texto!!}">
                    </div>

                    <div class="form-group">
                        <label for="saudacao">Saudação:</label>
                        <input type="text" id="saudacao" name="saudacao" class="form-control"
                               value="{!!$agendamento->saudacao!!}">
                    </div>

                    <div class="form-group">
                        <label for="despedida">Despedida:</label>
                        <input type="text" id="despedida" name="despedida" class="form-control"
                               value="{!!$agendamento->despedida!!}">
                    </div>

                    <div class="form-group">
                        <label for="contatos">Contatos:</label>
                        <div class="contatos-lista" class="contatos-lista" style="max-height: 200px; overflow-y: auto;">
                            <?php
                            $stringContatos = $agendamento->contatos;
                            $arrayContatos = explode(',', $stringContatos);
                            $contatos = [];
                            ?>

                            @for($i = 0; $i < count($arrayContatos); $i += 2)
                                    <?php
                                    $numero = $arrayContatos[$i];
                                    $nome = $arrayContatos[$i + 1];
                                    $contatos[] = ['numero' => $numero, 'nome' => $nome];
                                    ?>
                            @endfor

                            @foreach($contatos as $contato)
                                <div class="contato-item">
                                    <label class="switch">
                                        <input type="checkbox" name="contatos[]" value="{{ $contato['numero'] }}" class="switch" data-on-text="Ativo" data-off-text="Inativo" data-on-color="success" data-off-color="danger">
                                        <span class="slider round"></span>
                                    </label>
                                    <span>{{ str_replace(['[', ']'], '', $contato['nome'])." - ".str_replace(['[', ']'], '', $contato['numero']) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <div class="form-group" id="drop_zone" ondrop="dropHandler(event);"
                         ondragover="dragOverHandler(event);">
                        <p>Arraste um ou mais arquivos e solte aqui <i>drop zone</i>.</p>
                    </div>

                    <div class="form-group">
                        <label for="data_hora">Horário:</label>
                        <input type="datetime-local" id="datetimepicker" class="form-control data-mdb-inline"
                               name="data_hora" value="{!! $agendamento->created_at !!}">
                    </div>

                    <input type="hidden" name="id" value="{!!$agendamento->id!!}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
