<?php

namespace Chambre\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Chambre\Model\Chambre;
use Chambre\Form\ChambreForm;

class ChambreController extends AbstractActionController {

    protected $chambreTable;

    public function indexAction() {
        return new ViewModel(array(
            'chambres' => $this->getChambreTable()->fetchAll(),
        ));
    }

    // Add content to this method:
    public function addAction() {
        $form = new ChambreForm();
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $chambre = new Chambre();
            $form->setInputFilter($chambre->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $chambre->exchangeArray($form->getData());
                $this->getChambreTable()->saveChambre($chambre);

                // Redirect to list of chambres
                return $this->redirect()->toRoute('chambre');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
     {
         $idChambre = (int) $this->params()->fromRoute('id', 0);
         if (!$idChambre) {
             return $this->redirect()->toRoute('chambre', array(
                 'action' => 'add'
             ));
         }

         // Get the Chambre with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $chambre = $this->getChambreTable()->getChambre($idChambre);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('chambre', array(
                 'action' => 'index'
             ));
         }

         $form = new ChambreForm();
         $form->bind($chambre);
         $form->get('submit')->setAttribute('value', 'Modifier');
        
         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($chambre->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getChambreTable()->saveChambre($chambre);

                 // Redirect to list of chambres
                 return $this->redirect()->toRoute('chambre');
             }
         }

         return array(
             'idChambre' => $idChambre,
             'form' => $form,
         );
     }

     public function deleteAction()
     {
         $idChambre = (int) $this->params()->fromRoute('id', 0);
         if (!$idChambre) {
             return $this->redirect()->toRoute('chambre');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'Non');

             if ($del == 'Oui') {
                 $idChambre = (int) $request->getPost('idChambre');
                 $this->getChambreTable()->deleteChambre($idChambre);
             }

             // Redirect to list of chambres
             return $this->redirect()->toRoute('chambre');
         }

         return array(
             'idChambre' => $idChambre,
             'chambre' => $this->getChambreTable()->getChambre($idChambre)
         );
     }

    public function getChambreTable() {
        if (!$this->chambreTable) {
            $sm = $this->getServiceLocator();
            $this->chambreTable = $sm->get('Chambre\Model\ChambreTable');
        }
        return $this->chambreTable;
    }

}
