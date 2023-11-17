<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\Request\Postcode\CodeRequestDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PostcodeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(min: 2),
                ],
            ])
            ->add('page', IntegerType::class, [
                'constraints' => [
                    new Assert\Range(min: 1),
                ],
            ])
            ->add('perPage', IntegerType::class, [
                'constraints' => [
                    new Assert\Range(min: 1),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->resolve([
            'data_class' => CodeRequestDTO::class,
            'method' => 'GET',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
