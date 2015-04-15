<?php

namespace Mmi\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BusType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('busDesti')
            ->add('busHeure','time',array(
                'widget' => 'choice'
            ))
            ->add('busNum','text')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mmi\BackBundle\Entity\Bus'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mmi_backbundle_bus';
    }
}
