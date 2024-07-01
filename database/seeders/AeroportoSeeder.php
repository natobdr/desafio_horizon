<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AeroportoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aeroportos = [
            ['AJU', 'Aeroporto Internacional de Aracaju', 'Aracaju, SE'],
            ['ARJ', 'Aeroporto Internacional de Araguaína', 'Araguaína, TO'],
            ['AUB', 'Aeroporto de Itauba', 'Itauba, MT'],
            ['AUX',	'Aeroporto de Araguaína', 'Araguaína, TO'],
            ['BAL', 'Aeroporto de Barcelos', 'Barcelos, AM'],
            ['BEL', 'Aeroporto Internacional de Belém', 'Aracaju, SE'],
            ['BHZ', 'Aeroporto Internacional de Belo Horizonte/Pampulha', 'Belo Horizonte, MG'],
            ['BPS', 'Aeroporto de Porto Seguro', 'Porto Seguro, BA'],
            ['BSB', 'Aeroporto Internacional de Brasília', 'Brasília, DF'],
            ['BVH', 'Aeroporto de Vilhena', 'Vilhena, RO'],
            ['BYO', 'Aeroporto de Bonito', 'Bonito, MS'],
            ['CAC', 'Aeroporto de Cascavel', 'Cascavel, PR'],
            ['CGB', 'Aeroporto Internacional de Cuiabá', 'Cuiabá, MT'],
            ['CGH', 'Aeroporto de Congonhas', 'São Paulo, SP'],
            ['CGR', 'Aeroporto Internacional de Campo Grande', 'Campo Grande, MS'],
            ['CKS', 'Aeroporto de Carajás', 'Parauapebas, PA'],
            ['CLN', 'Aeroporto de Carolina', 'Carolina, MA'],
            ['CMG', 'Aeroporto de Corumbá', 'Corumbá, MS'],
            ['CNF', 'Aeroporto Internacional de Belo Horizonte', 'Belo Horizonte, MG'],
            ['CPV', 'Aeroporto Internacional de Campina Grande', 'Campina Grande, PB'],
            ['CPH', 'Aeroporto de Caiapônia', 'Caiapônia, GO'],
            ['CPH', 'Aeroporto de Pampulha – Carlos Drummond de Andrade', 'Belo Horizonte, MG'],
            ['CPQ', 'Aeroporto Internacional de Viracopos', 'Campinas, SP'],
            ['CRY', 'Aeroporto de Mossoró', 'Mossoró, RN'],
            ['CWB', 'Aeroporto Internacional Afonso Pena', 'Curitiba, PR'],
            ['CXJ', 'Aeroporto de Caxias do Sul', 'Caxias do Sul, RS'],
            ['CZS', 'Aeroporto de Cruzeiro do Sul', 'Cruzeiro do Sul, AC'],
            ['DIQ', 'Aeroporto de Divinópolis', 'Divinópolis, MG'],
            ['EFL', 'Aeroporto de Espírito Santo do Dourado', 'Espírito Santo do Dourado, SP'],
            ['EJA', 'Aeroporto de Jacarepaguá', 'Rio de Janeiro, RJ'],
            ['FEN', 'Aeroporto de Fernando de Noronha', 'Fernando de Noronha'],
            ['FLN', 'Aeroporto Internacional de Florianópolis', 'Florianópolis, SC'],
            ['FOR', 'Aeroporto Internacional de Fortaleza', 'Fortaleza, CE'],
            ['GEL', 'Aeroporto de Santo Ângelo', 'Santo Ângelo, RS'],
        ];

        foreach ($aeroportos as $aeroporto) {
            $cidade = DB::table('cidade')->where('nome', $aeroporto[2])->first();

            if ($cidade) {
                DB::table('aeroporto')->insert([
                    'codigo_iata' => $aeroporto[0],
                    'nome' => $aeroporto[1],
                    'cidade_id' => $cidade->cidade_id,
                ]);
            }
        }
    }
}
