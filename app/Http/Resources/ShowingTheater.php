<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Doctrine\Entities\ShowingTheater as ShowingTheaterEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowingTheater extends JsonResource
{
    public function __construct(ShowingTheaterEntity $showingTheater)
    {
        parent::__construct($showingTheater);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array{
     *     id: int,
     *     name: string,
     *     name_ja: string,
     *     coa_code: string|null,
     * }
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function toArray($request): array
    {
        /** @var ShowingTheaterEntity $showingTheater */
        $showingTheater = $this->resource;
        $theater        = $showingTheater->getTheater();

        return [
            'id' => $theater->getId(),
            'name' => $theater->getName(),
            'name_ja' => $theater->getNameJa(),
            'coa_code' => $theater->getMasterCode(),
        ];
    }
}
