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
            <div class="card-header">Pesquisar Voo</div>
            <div class="card-body">
                <form method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="aeroporto_origem">Voos disponivéis</label>
                        <select class="form-control" id="aeroporto_origem" name="aeroporto_origem_id" required>
                            <option value="0" selected>Selecione o aeroporto de origem e destino</option>
                            @foreach($voos as $voo)
                                <option value="{{ $voo['id'] }}">{{ $voo['numero'] }} - {{ $voo['aeroporto_origem']['codigo_iata'] }} - {{ $voo['aeroporto_origem']['nome'] }} => {{ $voo['aeroporto_destino']['codigo_iata'] }} - {{ $voo['aeroporto_destino']['nome'] }} - {{ \Carbon\Carbon::parse($voo['data_hora_partida'])->format('d-m-y H:i') }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <div id="customerForm"></div>
            </div>
        </div>
    </div>
    <script>
        $("#aeroporto_origem").change(function() {
            var query = $(this).val();
            console.log(query);
            $.ajax({
                url: "{!! route('voos.search') !!}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { query: query },
                success: function(response) {
                    $('#customerForm').empty(); // Limpa o conteúdo anterior

                    response.html.voo.ticket.forEach(function(ticket, index) {
                        console.log(index);
                        let formHtml = `
                            <div class="card mt-3">
                                <div class="card-header">Dados do Cliente ${index + 1}</div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nome_${ticket.passageiro.nome}">Nome</label>
                                        <input type="text" class="form-control" name="passageiros[${index}][nome]" id="nome_${index}" value="${ticket.passageiro.nome}">
                                    </div>
                                    <div class="form-group">
                                        <label for="idade_${index}">Idade</label>
                                        <input type="number" class="form-control" name="passageiros[${index}][idade]" id="idade_${index}" value="${ticket.passageiro.cpf}">
                                    </div>
                                    <div class="form-group">
                                        <label for="documento_${index}">Documento</label>
                                        <input type="text" class="form-control" name="passageiros[${index}][documento]" id="documento_${index}" value="${ticket.passageiro.email}">
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#customerForm').append(formHtml);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error in request:', status, error);
                    console.error('Server response:', xhr.responseText);
                }
            });
        });
    </script>
</x-app-layout>