<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Aeroportos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="card">
                        <div class="card-header">
                            Lista de Aeroportos
                            <span class="badge badge-primary pull-right">{{ count($aeroportos) }} Aeroportos</span>

                        </div>
                        <div id="clientList"></div>
                        <div class="card-body" id="table">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm data-table" id="relatorio">
                                    <thead class="thead-dark">
                                    <tr class="success">
                                        <th>Código IATA</th>
                                        <th>Aeroporto</th>
                                        <th>Cidade</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($aeroportos as $aeroporto)
                                        <tr>
                                            <td>{{ $aeroporto['codigo_iata'] }}</td>
                                            <td>{{ $aeroporto['nome'] }}</td>
                                            <td>{{ $aeroporto['cidade']['nome'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">Nenhum Aeroporto Encontrado.</td>
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
