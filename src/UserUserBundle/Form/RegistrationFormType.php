<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserUserBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle','attr' => array('class' => 'form-control')))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle','attr' => array('class' => 'form-control')))

            ->remove('Enregistrer')
            ->add('nom','text',array('attr' => array('class' => 'form-control')))
            ->add('prenom','text',array('attr' => array('class' => 'form-control')))
            ->add('roles', 'choice', array(
                'attr' => array('class' => 'form-control'),
                'choices' => array('ROLE_CLIENT'=>'Utilisateur','ROLE_ADMIN'=>'Administrateur'),
                'label' => 'Roles',
                'expanded' => false,
                'multiple' => true,
            ))
            /*
            ->add('expiresAt','date',array(
                'required' => false,
                'label' => 'Date: Uniquement pour les clients',
            ))*/
            ->add('jour','integer',array(
                "mapped" => false,
                'required' => false,
                'rounding_mode' => 4,
                'label' => 'Jour autorisé (uniquement pour le role client)',
                'attr' => array('class' => 'form-control')))



        ;

    }

    public function getParent()
    {
        return 'fos_user_registration';
    }


    public function getName()
    {
        return 'useruser_registration';
    }
}
