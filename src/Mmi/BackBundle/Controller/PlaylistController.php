<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mmi\BackBundle\Entity\Playlist;
use Mmi\BackBundle\Form\PlaylistType;

/**
 * Playlist controller.
 *
 */
class PlaylistController extends Controller
{

    /**
     * Lists all Playlist entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $id = $this->getUser()->getId();
        $recup = $em->getRepository('MmiBackBundle:User')->find($id);
        $entities = $em ->createQuery(
            'SELECT p FROM MmiBackBundle:Playlist p WHERE p.user = :id')
            ->setParameter('id',$recup)
            ->getResult();

        $video = $em ->getRepository('MmiBackBundle:Video')->findAll();
        return $this->render('MmiBackBundle:Playlist:index.html.twig', array(
            'entities' => $entities, 'videos' => $video
        ));
    }
    /**
     * Creates a new Playlist entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Playlist();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $id=$this->getUser()->getId();
            $recup = $em->getRepository('MmiBackBundle:User')->find($id);
            $form->getData()->setUser($recup);
            $form->getData()->setDate($recup->getSemaine());
            $form->getData()->setPlDuree(0);

            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('playlist_show', array('idp' => $entity->getId())));
        }

        return $this->render('MmiBackBundle:Playlist:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Playlist entity.
     *
     * @param Playlist $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Playlist $entity)
    {
        $form = $this->createForm(new PlaylistType(), $entity, array(
            'action' => $this->generateUrl('playlist_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Ajouter',
            'attr' => array('class' => "btn pull-right")
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Playlist entity.
     *
     */
    public function newAction()
    {
        $entity = new Playlist();
        $form   = $this->createCreateForm($entity);

        return $this->render('MmiBackBundle:Playlist:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Playlist entity.
     *
     */
    public function showAction($idp)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $this->getUser()->getId();
        $recup = $em->getRepository('MmiBackBundle:User')->find($id);
        $entity2 = $em ->createQuery(
            'SELECT p FROM MmiBackBundle:Playlist p WHERE p.user = :id AND p.id = :id2')
            ->setParameters(array('id' => $recup, 'id2' => $idp))

            ->getResult();
        $entity = $em->getRepository('MmiBackBundle:Playlist')->find($entity2[0]->getId());

        if (!$entity) {
            throw $this->createNotFoundException('Impossible de trouver la Playlist, veuillez vérifier si vous êtes autorisé à la modifier');
        }

        $deleteForm = $this->createDeleteForm($idp);

        return $this->render('MmiBackBundle:Playlist:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Playlist entity.
     *
     */
    public function editAction($idp)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $this->getUser()->getId();
        $recup = $em->getRepository('MmiBackBundle:User')->find($id);
        $entity2 = $em ->createQuery(
            'SELECT p FROM MmiBackBundle:Playlist p WHERE p.user = :id AND p.id = :id2')
            ->setParameters(array('id' => $recup, 'id2' => $idp))

            ->getResult();

        $entity = $em->getRepository('MmiBackBundle:Playlist')->find($entity2[0]->getId());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Playlist entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($idp);

        return $this->render('MmiBackBundle:Playlist:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Playlist entity.
    *
    * @param Playlist $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Playlist $entity)
    {
        $form = $this->createForm(new PlaylistType(), $entity, array(
            'action' => $this->generateUrl('playlist_update', array('idp' => $entity->getId())),
            'method' => 'PUT',
        ));
            $id2=$entity->getId();
        $form
            ->add('creneau','entity', array(
                'class' => 'MmiBackBundle:Creneau',
                'label' => 'Créneau',
                'property' => 'crNom',
                'empty_value' => 'Choisissez un Créneau',
                'required' => false,
                'query_builder' => function(\Doctrine\ORM\EntityRepository $ar) use ($id2) {
                    $id= $this->getUser()->getId();
                    $user = $this->getDoctrine()->getManager()->getRepository('MmiBackBundle:User')->find($id);
                     $recup= $this->getDoctrine()
                    ->getManager()->createQuery('SELECT p FROM MmiBackBundle:Playlist p WHERE p.creneau IS NOT NULL AND p.date = :date ')->setParameter('date', $user->getSemaine())->getResult();
                    $recup2 =$this->getDoctrine()->getManager()->getRepository('MmiBackBundle:Playlist')->find($id2);
                    $tab=array();

                    foreach ($recup as $recups) {
                            if($recups->getId() !== $recup2->getId()){
                                array_push($tab,$recups->getCreneau()->getId());

                            }else{
                            }

                    }

                    if($tab==array()){
                        return $ar = $this->getDoctrine()->getManager()->createQueryBuilder('c')
                            ->select('c')
                            ->from('MmiBackBundle:Creneau','c')
                            ;
                    }else{
                        return $ar = $this->getDoctrine()->getManager()->createQueryBuilder('c')
                            ->select('c')
                            ->from('MmiBackBundle:Creneau','c')
                            ->where('c.id NOT IN (:data)')
                            ->setParameter('data',$tab)
                            ;
                    }





    }))
            ->add('plDuree','integer',array(
                'label' => 'Durée de la playlist (en secondes)'
            ))

            ->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Playlist entity.
     *
     */
    public function updateAction(Request $request, $idp)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $this->getUser()->getId();
        $recup = $em->getRepository('MmiBackBundle:User')->find($id);
        $entity2 = $em ->createQuery(
            'SELECT p FROM MmiBackBundle:Playlist p WHERE p.user = :id AND p.id = :id2')
            ->setParameters(array('id' => $recup, 'id2' => $idp))

            ->getResult();

        $entity = $em->getRepository('MmiBackBundle:Playlist')->find($entity2[0]->getId());
        $temps=$entity->getPlDuree();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Playlist entity.');
        }

        $deleteForm = $this->createDeleteForm($idp);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $editForm->getData()->setplDuree($temps);
            $em->flush();

            return $this->redirect($this->generateUrl('playlist_edit', array('idp' => $idp)));
        }

        return $this->render('MmiBackBundle:Playlist:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Playlist entity.
     *
     */
    public function deleteAction(Request $request, $idp)
    {
        $form = $this->createDeleteForm($idp);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $id = $this->getUser()->getId();
            $recup = $em->getRepository('MmiBackBundle:User')->find($id);
            $entity2 = $em ->createQuery(
                'SELECT p FROM MmiBackBundle:Playlist p WHERE p.user = :id AND p.id = :id2')
                ->setParameters(array('id' => $recup, 'id2' => $idp))

                ->getResult();
            $entity = $em->getRepository('MmiBackBundle:Playlist')->find($entity2[0]->getId());

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Playlist entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('playlist'));
    }

    /**
     * Creates a form to delete a Playlist entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($idp)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('playlist_delete', array('idp' => $idp)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
