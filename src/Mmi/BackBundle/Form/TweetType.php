<?php

namespace Mmi\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TweetType extends MessageType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('msgTitre','text')
            ->remove('msgText','textarea')
            ->remove('msgType','choice', array(
                'choices' => array('tweet' => 'Tweet', 'mail' => 'Mail', 'annonce' => 'Annonce'),
                'required' => true,
            ))
            ->remove('Enregistrer', 'submit')
            ->add('voir','checkbox');
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
        return 'mmi_backbundle_tweet';
    }
}
