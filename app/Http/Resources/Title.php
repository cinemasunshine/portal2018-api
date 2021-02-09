<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Doctrine\Entities\Title as TitleEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Title extends JsonResource
{
    public function __construct(TitleEntity $title)
    {
        parent::__construct($title);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array{
     *     id: int,
     *     name: string,
     *     image: string|null,
     *     credit: string|null,
     *     catchcopy: string|null,
     *     introduction: string|null,
     *     director: string|null,
     *     cast: string|null,
     *     official_site: string|null,
     *     rating: string|null,
     *     universal: string[],
     * }
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function toArray($request): array
    {
        /** @var TitleEntity $title */
        $title = $this->resource;
        $image = $title->getImage();

        return [
            'id' => $title->getId(),
            'name' => $title->getName(),
            'image' => $image ? $image->getUrl() : null,
            'credit' => $title->getCredit(),
            'catchcopy' => $title->getCatchcopy(),
            'introduction' => $title->getIntroduction(),
            'director' => $title->getDirector(),
            'cast' => $title->getCast(),
            'official_site' => $title->getOfficialSite(),
            'rating' => $title->getRatingText(),
            'universal' => $title->getUniversalTexts(),
        ];
    }
}
