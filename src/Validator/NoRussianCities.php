<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class NoRussianCities extends Constraint
{
    public string $message = 'На жаль, місто "{{ city }}" недоступне.';
}
