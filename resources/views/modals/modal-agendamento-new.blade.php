<div class="modal fade row-cols-6" id="novoAgendamentoModal" tabindex="3" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-10 offset-lg-1">
                        <h6><b>AGENDAMENTO DE MENSAGENS</b></h6>
                        <form enctype="multipart/form-data" class="enviar-form" id="form-data" name="form-data">
                            @csrf
                            <div class="switch-holder">
                                <!-- Seção de seleção de grupos -->
                                LEADS => Selecione os grupos ou contatos<br><br>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="switch__container">
                                            <input id="switch-shadow" class="switch switch--shadow" type="checkbox" onclick="checkAll( this.id, this.checked );"/>
                                            <label for="switch-shadow"></label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="switch__container" style="text-align: left;">
                                            Marcar Todos:
                                        </div>
                                    </div>
                                </div>
                                <br>
                                @include('panels.agendamento-panel')
                            </div>

                            <!-- Seção de entrada de mensagens -->
                            <div class="row-cols-1">
                                <br><br>
                                <div class="form-group">
                                    <label for="saudacao">SAUDAÇÃO => Insira variáveis para saudação separadas por ;</label><br><br>
                                    <input placeholder="Oi, tudo bem?;Olá como vai você?;Falaaa, tudo certo?" style="width: 500px" type="text" id="saudacao" name="saudacao"/><br/><br/>
                                </div>
                                <div class="form-group">
                                    <label for="mensagem">MENSAGEM => Insira a sua mensagem</label><br><br>
                                    <textarea name="txt" id="mensagem" cols="40" rows="5" style="width: 500px"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="despedida">DESPEDIDA => Insira variáveis para despedida separadas por ;</label><br><br>
                                    <input placeholder="Até mais!;Nos vemos por aí!;Abraços!" style="width:500px" type="text" id="despedida" name="despedida"/><br/><br/>
                                </div>
                                <div class="form-group">
                                    <input name="arquivo" type="checkbox" value="img" id="img" class="img"> Marque o checkbox para enviar arquivos jpg ou png (tamanho max 64MB) <br/>
                                </div>
                            </div>
                            <!-- Seção de inserção de arquivos -->
                            <div id="imagem" class="form-group">
                                <br>
                                <label for="file">INSERIR ARQUIVO:</label><br>
                                <input name="urlPath" id="file" type="file" class="form-control-file" placeholder="Selecione a imagem" style="text-decoration-color: aliceblue"/>
                            </div>
                            <br>
                            <input class="btn-primary submit-form" style="width:10%" type="submit" id="enviar-checkboxes" value="ENVIAR!"/>
                        </form>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn btn-secondary', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                        {!! Form::button('<i class="fa fa-fw fa-check" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_submit'), array('class' => 'btn btn-primary', 'type' => 'button', 'id' => 'confirm' )) !!}
                    </div>
                </div>
           </div>
            </div>
        </div>
    </div>
</div>
@vite(['resources/assets/css/agendamento.css'])