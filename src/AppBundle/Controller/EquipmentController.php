<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Equipment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Equipment controller.
 *
 * @Route("admin/equipment")
 */
class EquipmentController extends Controller
{
    /**
     * Lists all Equipment entities.
     *
     * @Route("/",    name="equipment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $equipments = $em->getRepository('AppBundle:Equipment')->findAll();
        return $this->render(
            'admin/equipment/index.html.twig',
            array(
                'equipments' => $equipments,
            )
        );
    }
    /**
     * Creates a new equipment entity.
     *
     * @Route("/new",  name="equipment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $equipment = new Equipment();
        $form = $this->createForm('AppBundle\Form\EquipmentType', $equipment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipment);
            $em->flush();
            return $this->redirectToRoute('equipment_show', array('id' => $equipment->getId()));
        }
        return $this->render(
            'admin/equipment/new.html.twig',
            array(
                'equipment' => $equipment,
                'form' => $form->createView(),
            )
        );
    }
    /**
     * Finds and displays a equipment entity.
     *
     * @Route("/{id}", name="equipment_show")
     * @Method("GET")
     */
    public function showAction(Equipment $stuff)
    {
        $deleteForm = $this->createDeleteForm($stuff);
        return $this->render(
            'equipment/show.html.twig',
            array(
                'equipment' => $stuff,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }
    /**
     * Displays a form to edit an existing equipment entity.
     *
     * @Route("/{id}/edit", name="equipment_edit")
     * @Method({"GET",      "POST"})
     */
    public function editAction(Request $request, Equipment $stuff)
    {
        $deleteForm = $this->createDeleteForm($stuff);
        $editForm = $this->createForm('AppBundle\Form\EquipmentType', $stuff);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('equipment_edit', array('id' => $stuff->getId()));
        }
        return $this->render(
            'equipment/edit.html.twig',
            array(
                'equipment' => $stuff,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }
    /**
     * Deletes a equipment entity.
     *
     * @Route("/{id}",   name="equipment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Equipment $stuff)
    {
        $form = $this->createDeleteForm($stuff);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($stuff);
            $em->flush();
        }
        return $this->redirectToRoute('equipment_index');
    }
    /**
     * Creates a form to delete a equipment entity.
     *
     * @param Equipment $stuff The Equipment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Equipment $stuff)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('equipment_delete', array('id' => $stuff->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}