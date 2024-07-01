<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClasseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo_classe' => new TipoClasseResource($this->whenLoaded('tipoClasse')),
            'valor_assento' => $this->valor_assesnto,
            'quantidade_assentos' => $this->quantidade_assentos,
        ];
    }
}
