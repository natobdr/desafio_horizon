<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voos') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="card">
                        <div class="card-header">
                            Lista de Voos
                            <span class="badge badge-primary float-right">{{ count($voos) }} Voos</span>
                            <br/>
                            <div style="display: flex; justify-content: flex-end;">
                                <a href="{!! route('voos.create') !!}" style="background-color: #28a745; color: #fff; padding: 8px 16px; text-decoration: none;">Novo Voo</a>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm data-table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Número</th>
                                        <th>Aeroporto Origem</th>
                                        <th>Aeroporto Destino</th>
                                        <th>Data e Hora de Partida</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($voos as $voo)
                                        <tr>
                                            <td>{{ $voo['numero'] }}</td>
                                            <td>{{ $voo['aeroporto_origem']['nome'] }} - {{ $voo['aeroporto_origem']['cidade']['nome'] }}</td>
                                            <td>{{ $voo['aeroporto_destino']['nome'] }} - {{ $voo['aeroporto_destino']['cidade']['nome'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($voo['data_hora_partida'])->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <a href="{!! route('voos.edit',$voo['id']) !!}" class="text-muted">
                                                    <i class="fa fa-edit" style="color: blue;"></i>
                                                </a>
                                                <a href="#"  class="text-muted">
                                                    <i class="fa fa-trash" style="color: darkred;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">Nenhum Voo Encontrado.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>