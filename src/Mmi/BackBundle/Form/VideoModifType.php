<?php

namespace Mmi\BackBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VideoModifType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('vidLink')
            ->remove('Enregistrer')
            ->remove('playlist')

            ->add('vidLink','text', array(
                'required' =>true,
                'disabled'=> true
            ))
            ->add('vidTitre','text', array(
                'required' =>true,
            ))
            ->add('vidDesc','text', array(
                'required' =>true,
            ))
            ->add('playlist', 'entity', array(
                'class' => "MmiBackBundle:Playlist",
                'property' => "plNom"
            ))

            ->add('Enregistrer','submit')
        ;

    }

    public function getParent()
    {
        return new VideoType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mmi_backbundle_video_edit';
    }
}
