<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AeroportoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'codigo_iata' => $this->codigo_iata,
            'nome' => $this->nome,
            'cidade' => new CidadeResource($this->whenLoaded('cidade')),
        ];
    }
}
