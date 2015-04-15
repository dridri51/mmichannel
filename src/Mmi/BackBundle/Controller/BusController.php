<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mmi\BackBundle\Entity\Bus;
use Mmi\BackBundle\Form\BusType;

/**
 * Bus controller.
 *
 */
class BusController extends Controller
{

    /**
     * Lists all Bus entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MmiBackBundle:Bus')->findBy(array(),array('busHeure' => 'ASC'));

        $tab=array();


        foreach ($entities as $entity)
        {
            if(in_array($entity->getBusNum(),$tab))
            {

            }else{
                array_push($tab,$entity->getBusNum());
            }
        }


        return $this->render('MmiBackBundle:Bus:index.html.twig', array(
            'entities' => $entities, 'tab' => $tab
        ));
    }
    /**
     * Creates a new Bus entity.
     *
     */
    public function createAction(Request $request)
    {

        $entity = new Bus();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $id= $this->getUser()->getId();
            $recup= $this->getDoctrine()->getManager()->getRepository('MmiBackBundle:User')->find($id);
            $entity->addUser($recup);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('client_bus_show', array('id' => $entity->getId())));
        }

        return $this->render('MmiBackBundle:Bus:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Bus entity.
     *
     * @param Bus $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Bus $entity)
    {
        $form = $this->createForm(new BusType(), $entity, array(
            'action' => $this->generateUrl('client_bus_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Bus entity.
     *
     */
    public function newAction()
    {
        $entity = new Bus();
        $form   = $this->createCreateForm($entity);

        return $this->render('MmiBackBundle:Bus:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Bus entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MmiBackBundle:Bus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bus entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MmiBackBundle:Bus:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Bus entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MmiBackBundle:Bus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bus entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MmiBackBundle:Bus:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Bus entity.
    *
    * @param Bus $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Bus $entity)
    {
        $form = $this->createForm(new BusType(), $entity, array(
            'action' => $this->generateUrl('client_bus_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Bus entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MmiBackBundle:Bus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bus entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('client_bus_edit', array('id' => $id)));
        }

        return $this->render('MmiBackBundle:Bus:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Bus entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MmiBackBundle:Bus')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Bus entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('client_bus'));
    }

    /**
     * Creates a form to delete a Bus entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_bus_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
