<?php

namespace ArticleBundle\Form;

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')->add('description',null , array('label' => 'Contenu : '))->add('theme', EntityType::class, [
            // looks for choices from this entity
            'class' => 'AppBundle\Entity\Theme',

            // uses the User.username property as the visible option string
            'choice_label' => 'libelle',

            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ]) ->add('imageFile', VichFileType::class, [
            'required' => false,
            'allow_delete' => true,
            'download_link' => true

        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'articlebundle_article';
    }


}
