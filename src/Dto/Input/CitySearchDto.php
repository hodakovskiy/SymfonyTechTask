<?php

namespace App\Dto\Input;

use App\Validator\NoRussianCities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO for weather search form input.
 */
class CitySearchDto
{
    #[Assert\NotBlank(message: 'validation.city.not_blank')]
    #[Assert\Type(type: 'string', message: 'validation.city.type')]
    #[Assert\Regex(
        pattern: '/^(?!\d+$).+$/',
        message: 'validation.city.digits_only'
    )]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'validation.city.too_short',
        maxMessage: 'validation.city.too_long'
    )]
    #[NoRussianCities]
    public string $city = '';

    public static function fromRequest(Request $request): self
    {
        $dto = new self();
        $dto->city = trim($request->request->get('city', ''));

        return $dto;
    }
}
