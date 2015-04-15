<?php

namespace Mmi\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mmi\BackBundle\Entity\Creneau;
use Mmi\BackBundle\Form\CreneauType;

/**
 * Creneau controller.
 *
 */
class CreneauController extends Controller
{

    /**
     * Lists all Creneau entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MmiBackBundle:Creneau')->findAll();

        return $this->render('MmiBackBundle:Creneau:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MmiBackBundle:Creneau')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creneau entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('MmiBackBundle:Creneau:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Creneau entity.
    *
    * @param Creneau $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Creneau $entity)
    {
        $form = $this->createForm(new CreneauType(), $entity, array(
            'action' => $this->generateUrl('creneau_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Creneau entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MmiBackBundle:Creneau')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creneau entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('creneau_edit', array('id' => $id)));
        }

        return $this->render('MmiBackBundle:Creneau:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

}
