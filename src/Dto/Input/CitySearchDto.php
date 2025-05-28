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
    #[Assert\NotBlank(message: 'Будь ласка, введіть назву міста.')]
    #[Assert\Type(type: 'string', message: 'Назва міста має бути текстом.')]
    #[Assert\Regex(
        pattern: '/^(?!\d+$).+$/',
        message: 'Назва міста не може складатися лише з цифр.'
    )]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Назва міста має містити щонайменше {{ limit }} символи.',
        maxMessage: 'Назва міста не може перевищувати {{ limit }} символів.'
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
