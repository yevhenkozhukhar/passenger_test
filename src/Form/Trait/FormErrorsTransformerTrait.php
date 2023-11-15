<?php

declare(strict_types=1);

namespace App\Form\Trait;

use Symfony\Component\Form\FormInterface;

trait FormErrorsTransformerTrait
{
    public function getFormErrorsMessages(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()][] = $error->getMessage();
        }

        return $errors;
    }
}
