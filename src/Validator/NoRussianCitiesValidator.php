<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoRussianCitiesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value || !is_string($value)) {
            return;
        }

        $forbidden = ['москва', 'moscow'];

        if (in_array(mb_strtolower($value), $forbidden, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ city }}', $value)
                ->addViolation();
        }
    }
}
