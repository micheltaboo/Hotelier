<?php

namespace Utilisateur\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Utilisateur\Model\Utilisateur;
use Utilisateur\Form\UtilisateurForm;

class UtilisateurController extends AbstractActionController {

    protected $utilisateurTable;

    public function indexAction() {
        //   return new ViewModel();
    }

    public function addAction() {


        // Creating html form for utilisateur insert
        $form = new UtilisateurForm();

        // Getting a request object
        $request = $this->getRequest();

        // If it is a form submission,
        // then request will be post
        if ($request->isPost()) {

            // Creating a Utilisateur object
            $utilisateur = new Utilisateur();

            // Setting data on form object from request object
            $form->setData($request->getPost());

            if ($form->isValid()) {

                // Setting data on utilisateur object from form object
                $utilisateur->exchangeArray($form->getData());

                print_r($utilisateur);
                print_r($form->getData());
                // Inserting utilisateur data in the datbase table
                $this->getUtilisateurTable()->saveUtilisateur($utilisateur);

                // Redirecting to index action of utilisateur controller
                return $this->redirect()->toRoute("utilisateur");
            }
        }

        // If it is form request,
        // then return the form
        return array('form' => $form);
    }

    public function getUtilisateurTable() {
        if (!$this->utilisateurTable) {
            $sm = $this->getServiceLocator();
            $this->utilisateurTable = $sm->get('Utilisateur\Model\UtilisateurTable');
        }
        return $this->utilisateurTable;
    }

}