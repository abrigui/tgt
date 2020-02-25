<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom'
        )->add('prenom')
            ->add('adresse')
            ->add('telephone')
            ->add('genre', ChoiceType::class, [
                    'choices' => [
                        'Homme' => 'Homme',
                        'Femme' => 'Homme'
                    ],
                    'expanded' => false,  // => boutons
                    'label' => 'Genre',
        ])
            ->add('roles', ChoiceType::class, array(
                'choices'   => array(
                    'Utilisateur talent'        => 'ROLE_USERT',
                    'Utilisateur proffessionel'        => 'ROLE_USERP',
                ),

                'multiple'  => true,
            ))
        ->add('captchaCode',CaptchaType::class);
    }/**
     * {@inheritdoc}
     */


    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
