<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cidades = [
            'Aracaju, SE',
            'Araguaína, TO',
            'Itauba, MT',
            'Barcelos, AM',
            'Belém, PA',
            'Belo Horizonte, MG',
            'Porto Seguro, BA',
            'Brasília, DF',
            'Vilhena, RO',
            'Bonito, MS',
            'Cascavel, PR',
            'Cuiabá, MT',
            'São Paulo, SP',
            'Campo Grande, MS',
            'Parauapebas, PA',
            'Carolina, MA',
            'Corumbá, MS',
            'Belo Horizonte, MG',
            'Campina Grande, PB',
            'Caiapônia, GO',
            'Belo Horizonte, MG',
            'Campinas, SP',
            'Mossoró, RN',
            'Curitiba, PR',
            'Caxias do Sul, RS',
            'Cruzeiro do Sul, AC',
            'Divinópolis, MG',
            'Espírito Santo do Dourado, SP',
            'Rio de Janeiro, RJ',
            'Fernando de Noronha, PE',
            'Florianópolis, SC',
            'Fortaleza, CE',
            'Santo Ângelo, RS',
            ];
        foreach ($cidades as $cidade) {
            DB::table('cidades')->insert([
                'nome' => $cidade,
            ]);
        }
    }
}
