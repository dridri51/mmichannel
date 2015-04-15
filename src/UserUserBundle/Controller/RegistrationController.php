<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserUserBundle\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\User;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends BaseController
{

    public function registerAction(Request $request)
    {
        function get_lundi_vendredi_from_week($week,$year,$format="Y-m-d") {

            $firstDayInYear=date("N",mktime(0,0,0,1,1,$year));
            if ($firstDayInYear<5)
                $shift=-($firstDayInYear-1)*86400;
            else
                $shift=(8-$firstDayInYear)*86400;
            if ($week>1) $weekInSeconds=($week-1)*604800; else $weekInSeconds=0;
            $timestamp=mktime(0,0,0,1,1,$year)+$weekInSeconds+$shift;
            $timestamp_vendredi=mktime(0,0,0,1,5,$year)+$weekInSeconds+$shift;

            return array(date($format,$timestamp),date($format,$timestamp_vendredi));

        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }


        $form = $formFactory->createForm();
        $form->setData($user);
        $form
            ->add('expiresAt','text',array(
                'attr' => array('class' => 'form-control'),
                'data' => date('Y-m-d 23:59:59'),
                'disabled' => 'disabled',
                'label' => 'Expire le'
            ))
            ->add('semaine','text', array(
                'attr' => array('class' => 'form-control',"data-provide" => "datepicker"),
                'label' => "Semaine à s'occuper",
                'required' => true
            ))
            ->add('Enregistrer','submit');

        $form->handleRequest($request);

        if ($form->isValid()) {

//On met la date à jour avec la valeur du formulaire
            for($i=0;$i<1;$i++) {




              if($form->getData()->getRoles()[0]=='ROLE_CLIENT'){

                  if($form->offsetGet('jour')->getNormData()<0 || $form->offsetGet('jour')->getNormData()==null){
                      $date = new \DateTime();
                      $date->add(new \DateInterval('P'.$form->offsetGet('jour')->getNormData(0).'D'));
                      $form->getData()->setExpiresAt($date);

                  }else{
                      $date = new \DateTime();
                      $date->add(new \DateInterval('P'.$form->offsetGet('jour')->getNormData().'D'));
                      $form->getData()->setExpiresAt($date);

                  }
              }else{
                  $form->getData()->setExpiresAt(null);
                  $form->getData()->setSemaine(new \DateTime('0000-00-00'));
              }
            }


            if($form->getData()->getRoles()[0]=='ROLE_CLIENT'){

                $j = $form->getData()->getSemaine();
                $sem = date('W',strtotime($j));
                $explode = explode("/",$j);

                // Permet de récupérer le lundi et le vendredi d'une date


                $debut_fin_semaine = get_lundi_vendredi_from_week($sem, $explode[2]);
                $em= $this->getDoctrine()->getManager();

                $recupdate = $em->getRepository('MmiBackBundle:User')->findBySemaine(new \DateTime($debut_fin_semaine[0]));

                if($recupdate == null) {
                $form->getData()->setSemaine(new \DateTime($debut_fin_semaine[0]));
                }else{
                    throw new NotFoundHttpException(sprintf('Attention, la date marqué est déjà relié à un élève'));
                }
            }







            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            //$dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }



        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        $email = $this->get('session')->get('fos_user_send_confirmation_email/email');
        $this->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('FOSUserBundle:Registration:checkEmail.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('FOSUserBundle:Registration:confirmed.html.twig', array(
            'user' => $user,
        ));
    }
}
