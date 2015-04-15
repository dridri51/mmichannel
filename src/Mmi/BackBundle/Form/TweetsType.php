<?php

namespace Mmi\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Mmi\BackBundle\Form\TweetType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TweetsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('messages', 'collection', array('type' => new TweetType()));
    }

    public function getName()
    {
        return 'mmi_backbundle_tweets';
    }
}