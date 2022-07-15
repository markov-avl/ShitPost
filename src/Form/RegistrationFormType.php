<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Username'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an username',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Your username should be at least {{ limit }} characters',
                        'max' => 180,
                        'maxMessage' => 'Your username should be {{ limit }} characters or less',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                        'maxMessage' => 'Your password should be {{ limit }} characters or less',
                    ]),
                ],
                'required' => true,
                'first_options' => [
                    'label' => 'Password',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Password'
                    ]
                ],
                'second_options' => [
                    'label' => 'Re-enter password',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Re-entered password'
                    ]]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
