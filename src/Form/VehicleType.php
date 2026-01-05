<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'constraints' => [
                    new Length(min: 3, max: 255, minMessage: 'vehicle.form.label.min_length'),
                ],
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'label',
            ])
            ->add(
                'registrationNumber',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Length(min: 3, max: 20),
                    ],
                ]
            )
            ->add('image', FileType::class,
                [
                    'mapped' => false,
                    'required' => false,
                    'label' => 'vehicle.form.label.image',
                    'constraints' => [
                        new Image(mimeTypes: ['image/jpeg', 'image/png']),
                    ],
                ]
            )
            ->add('submit', SubmitType::class, ['label' => 'Ajouter un véhicule'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
