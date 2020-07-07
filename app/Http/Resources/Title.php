<?php

namespace App\Http\Resources;

use App\Doctrine\Entities\Title as TitleEntity;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Title extends JsonResource
{
    /**
     * @param TitleEntity $title
     */
    public function __construct(TitleEntity $title)
    {
        parent::__construct($title);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
     */
    public function toArray($request)
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
