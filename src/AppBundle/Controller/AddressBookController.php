<?php

/*
 * This file is part of the www.lillydoo.com test.
 *
 * @author Omar Makled <omar.makled@gmail.com.com>
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\AddressBookType;
use AppBundle\Entity\AddressBook;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AddressBookController.
 *
 * @Route("/address-book")
 */
class AddressBookController extends Controller
{
    /**
     * @Route(name="addressbook", methods={"GET"})
     */
    public function indexAction()
    {
        $addressBooks = $this->getDoctrine()->getRepository(AddressBook::class)->findAll();

        return $this->render('addressbook/index.html.twig', [
            'addressBooks' => $addressBooks,
        ]);
    }

    /**
     * @Route("/create", name="addressbook_create", methods={"GET", "POST"})
     */
    public function createAction(Request $request, FileUploader $uploader)
    {
        $addressBook = new AddressBook();
        $form = $this->createForm(AddressBookType::class, $addressBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->save($addressBook, $request, $uploader);
            $this->flash(['type' => 'success', 'text' => 'Added new record !', 'timer' => 1500]);

            return $this->redirectToRoute('addressbook');
        }

        return $this->render('addressbook/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="addressbook_edit", methods={"GET", "PUT"})
     */
    public function editAction(Request $request, AddressBook $addressBook, FileUploader $uploader)
    {
        $form = $this->createForm(AddressBookType::class, $addressBook, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->save($addressBook, $request, $uploader);
            $this->flash(['type' => 'success', 'text' => 'Edited existing record !', 'timer' => 1500]);

            return $this->redirectToRoute('addressbook');
        }

        return $this->render('addressbook/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="addressbook_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, AddressBook $addressBook)
    {
        if (!$this->isCsrfTokenValid($addressBook->getId(), $request->request->get('_token'))) {
            $this->flash(['type' => 'error', 'text' => 'CSRF token invalid']);

            return $this->redirectToRoute('addressbook');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($addressBook);
        $em->flush();

        $this->flash(['type' => 'success', 'text' => 'Success deleted !']);

        return $this->redirectToRoute('addressbook');
    }

    /**
     * @Route("/{id}/photo", name="addressbook_delete_photo", methods={"DELETE"})
     */
    public function deletePhotoAction(Request $request, AddressBook $addressBook)
    {
        if (!$this->isCsrfTokenValid($addressBook->getId(), $request->request->get('_token'))) {
            $this->flash(['type' => 'error', 'text' => 'CSRF token invalid']);

            return $this->redirectToRoute('addressbook');
        }
        $em = $this->getDoctrine()->getManager();
        $addressBook->setPhoto(null);
        $em->persist($addressBook);
        $em->flush();

        $this->flash(['type' => 'success', 'text' => 'Success deleted !']);

        return $this->redirectToRoute('addressbook');
    }

    /**
     * @param array $options
     */
    private function flash(array $options)
    {
        $this->addFlash('alert', $options);
    }

    /**
     * @param AddressBook    $addressBook
     * @param Request      $request
     * @param FileUploader $uploader
     */
    private function save(AddressBook $addressBook, Request $request, FileUploader $uploader)
    {
        $files = $request->files->get('address_book');
        if ($files && $files['photo'] instanceof UploadedFile) {
            $addressBook->setPhoto($uploader->upload($files['photo']));
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($addressBook);
        $em->flush();
    }
}
