<?php

namespace Mmi\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('msgType','choice', array(
                'choices' => array('tweet' => 'Tweet', 'mail' => 'Mail', 'annonce' => 'Annonce'),
                'label' => 'Type',
                'required' => true,
            ))
            ->add('msgTitre','text',array(
                'label' => 'Titre',
            ))
            ->add('msgDate','date',array(
                'label' => 'Date',
                'days' => range(1,31),
                'months' => range(1,12),



            ))
            ->add('msgText','textarea',array(
                'label' => 'Contenu',
            ))


            ->add('Enregistrer', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mmi\BackBundle\Entity\Message'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mmi_backbundle_message';
    }
}
