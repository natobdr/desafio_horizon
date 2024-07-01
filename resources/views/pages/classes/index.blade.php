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
            <div class="card">
                <div class="card-header">
                    Lista de Classes
                    <span class="badge badge-primary pull-right">{{ count($classes) }} Voos</span>
                    <div class="d-flex">
                        <div style="display: flex; justify-content: flex-end;">
                            <a href="{{ route('voos.create') }}" style="background-color: #28a745; color: #fff; padding: 8px 16px; text-decoration: none;">Novo Voo</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm data-table">
                            <thead class="thead-dark">
                            <tr>
                                <th>Tipo da Classe</th>
                                <th>Quantidade de Assentos</th>
                                <th>Valor do Assento</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($classes as $classe)
                                <tr>
                                    <td>{{ $classe['tipo_classe']['tipo'] }}</td>
                                    <td>{{ $classe['quantidade_assentos'] }} </td>
                                    <td>{{ $classe['valor_assento'] }}</td>
                                    <td>
                                        <button class="btn btn-primary">Editar</button>
                                        <button class="btn btn-danger">Excluir</button>
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
            <div/>
        </div>
    </div>
</x-app-layout>