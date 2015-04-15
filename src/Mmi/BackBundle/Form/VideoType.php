<?php

namespace Mmi\BackBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('vidLink','text', array(
                'required' =>false,
                'label' => "Lien (Youtube/Dailymotion/Viméo)"

    ))
    // Le champ date de la vidéo est dans le controller (correspond a la date d'aujourd'hui)

            ->add('playlist', 'entity', array(
                'class' => "MmiBackBundle:Playlist",
                'property' => "plNom",
                'label' => 'Choisissez la playlist'
            ))
            ->add('Enregistrer','submit')
        ;

    }


    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mmi\BackBundle\Entity\Video',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mmi_backbundle_video';
    }
}
