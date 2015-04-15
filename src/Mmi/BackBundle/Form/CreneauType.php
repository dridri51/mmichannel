<?php

namespace Mmi\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreneauType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cr_nom','text',array(
                'label' => 'Nom'
            ))
            ->add('cr_desc','text',array(
                'label' => 'Description'
            ))
            ->add('categorie','text',array(
                'label' => 'Catégorie'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mmi\BackBundle\Entity\Creneau'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mmi_backbundle_creneau';
    }
}
