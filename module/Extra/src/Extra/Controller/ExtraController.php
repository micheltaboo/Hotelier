<?php

namespace Extra\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Extra\Form\DoucheForm;
use Extra\Form\TelevisionForm;
use Extra\Form\FrigoForm;

class ExtraController extends AbstractActionController {

    protected $doucheTable;
    protected $televisionTable;
    protected $frigoTable;

    public function indexAction() {


        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);

        return new ViewModel(array(
            'idChambre' => $idChambre,
            'douche' => $this->getDoucheTable()->getDouche($idChambre),
            'television' => $this->getTelevisionTable()->getTelevision($idChambre),
            'frigo' => $this->getFrigoTable()->getFrigo($idChambre),
        ));
    }

    /*     * ****************************************
     * *******Actions Douche**********
     * **************************************** */

    public function adddoucheAction() {

        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);

        $form = new DoucheForm();
        $form->get('idChambre')->setValue($idChambre);
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $douche = new \Extra\Model\Douche();
            $form->setInputFilter($douche->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $douche->exchangeArray($form->getData());
                $this->getDoucheTable()->saveDouche($douche);

                // Redirect to list of extra
                return $this->redirect()->toRoute('extra', array(
                            'action' => 'index',
                            'idChambre' => $idChambre,
                ));
            }
        }
        return array('form' => $form, 'idChambre' => $idChambre);
    }

    public function editdoucheAction() {
        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);
        if (!$idChambre) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index',
            ));
        }

        $idDouche = (int) $this->params()->fromRoute('idDouche', 0);


        if (!$idDouche) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'add'
            ));
        }


        try {
            $douche = $this->getDoucheTable()->getDouche($idChambre);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index'
            ));
        }



        $form = new DoucheForm();
        $form->bind($douche);
        $form->get('submit')->setAttribute('value', 'Modifier');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($douche->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getDoucheTable()->saveDouche($douche);

                // Redirect to list of chambres
                return $this->redirect()->toRoute('extra', array(
                            'action' => 'index',
                            'idChambre' => $idChambre,
                ));
            }
        }

        return array(
            'idDouche' => $idDouche,
            'douche' => $this->getDoucheTable()->getDouche($idDouche),
            'idChambre' => $idChambre,
            'form' => $form,
        );
    }

    public function douchedeleteAction() {
        $idDouche = (int) $this->params()->fromRoute('idDouche', 0);

        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);
        if (!$idChambre) {
            return $this->redirect()->toRoute('chambre');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $idDouche = (int) $this->params()->fromRoute('idDouche', 1);


                $this->getDoucheTable()->deleteDouche($idDouche);
            }

            // Redirect to list of extra
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index',
                        'idChambre' => $idChambre,
            ));
        }

        return array(
            'idDouche' => $idDouche,
            'douche' => $this->getDoucheTable()->getDouche($idDouche),
            'idChambre' => $idChambre,
        );
    }

    /*     * ****************************************
     * *******Actions Television**********
     * **************************************** */

    public function addtelevisionAction() {

        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);

        $form = new TelevisionForm();
        $form->get('idChambre')->setValue($idChambre);
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $television = new \Extra\Model\Television();
            $form->setInputFilter($television->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $television->exchangeArray($form->getData());
                $this->getTelevisionTable()->saveTelevision($television);

                // Redirect to list of extra
                return $this->redirect()->toRoute('extra', array(
                            'action' => 'index',
                            'idChambre' => $idChambre,
                ));
            }
        }
        return array('form' => $form, 'idChambre' => $idChambre);
    }

    public function edittelevisionAction() {
        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);
        if (!$idChambre) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index',
            ));
        }

        $idTelevision = (int) $this->params()->fromRoute('idDouche', 0);

        print_r($idTelevision);
        print_r($idChambre);
        if (!$idTelevision) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'add'
            ));
        }


        try {
            $television = $this->getTelevisionTable()->getTelevisionV2($idTelevision);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index',
                        'idChambre' => $idChambre,
            ));
        }

        $form = new TelevisionForm();
        $form->bind($television);
        $form->get('submit')->setAttribute('value', 'Modifier');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($television->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTelevisionTable()->saveTelevision($television);

                // Redirect to list of chambres
                return $this->redirect()->toRoute('extra', array(
                            'action' => 'index',
                            'idChambre' => $idChambre,
                ));
            }
        }

        return array(
            'idTelevision' => $idTelevision,
            'television' => $this->getTelevisionTable()->getTelevision($idTelevision),
            'idChambre' => $idChambre,
            'form' => $form,
        );
    }

    public function televisiondeleteAction() {

        $idTelevision = (int) $this->params()->fromRoute('idDouche', 99);

        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);
        if (!$idChambre) {
            return $this->redirect()->toRoute('chambre');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $idTelevision = (int) $this->params()->fromRoute('idDouche', 99);


                $this->getTelevisionTable()->deleteTelevision($idTelevision);
            }

            // Redirect to list of extra
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index',
                        'idChambre' => $idChambre,
            ));
        }

        return array(
            'idTelevision' => $idTelevision,
            'television' => $this->getTelevisionTable()->getTelevision($idTelevision),
            'idChambre' => $idChambre,
        );
    }

    /*     * ****************************************
     * *******Actions Frigo**********
     * **************************************** */

    public function addfrigoAction() {

        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);

        $form = new FrigoForm();
        $form->get('idChambre')->setValue($idChambre);
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $frigo = new \Extra\Model\Frigo();
            $form->setInputFilter($frigo->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $frigo->exchangeArray($form->getData());
                $this->getFrigoTable()->saveFrigo($frigo);

                // Redirect to list of extra
                return $this->redirect()->toRoute('extra', array(
                            'action' => 'index',
                            'idChambre' => $idChambre,
                ));
            }
        }
        return array('form' => $form, 'idChambre' => $idChambre);
    }

    public function editfrigoAction() {
        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);
        if (!$idChambre) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index',
            ));
        }

        $idFrigo = (int) $this->params()->fromRoute('idDouche', 0);


        if (!$idFrigo) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'add'
            ));
        }


        try {
            $frigo = $this->getFrigoTable()->getFrigov2($idFrigo);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index',
                        'idChambre' => $idChambre,
            ));
        }

        $form = new FrigoForm();
        $form->bind($frigo);
        $form->get('submit')->setAttribute('value', 'Modifier');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($frigo->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getFrigoTable()->saveFrigo($frigo);

                // Redirect to list of chambres
                return $this->redirect()->toRoute('extra', array(
                            'action' => 'index',
                            'idChambre' => $idChambre,
                ));
            }
        }

        return array(
            'idFrigo' => $idFrigo,
            'frigo' => $this->getFrigoTable()->getFrigo($idFrigo),
            'idChambre' => $idChambre,
            'form' => $form,
        );
    }

    public function frigodeleteAction() {

        $idFrigo = (int) $this->params()->fromRoute('idDouche', 99);

        $idChambre = (int) $this->params()->fromRoute('idChambre', 0);
        if (!$idChambre) {
            return $this->redirect()->toRoute('chambre');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $idFrigo = (int) $this->params()->fromRoute('idDouche', 99);


                $this->getFrigoTable()->deleteFrigo($idFrigo);
            }

            // Redirect to list of extra
            return $this->redirect()->toRoute('extra', array(
                        'action' => 'index',
                        'idChambre' => $idChambre,
            ));
        }
        return array(
            'idFrigo' => $idFrigo,
            'frigo' => $this->getFrigoTable()->getFrigo($idFrigo),
            'idChambre' => $idChambre,
        );
    }
    
     /*     * ****************************************
     * *******Getter Tables**********
     * **************************************** */

    public function getDoucheTable() {
        if (!$this->doucheTable) {
            $sm = $this->getServiceLocator();
            $this->doucheTable = $sm->get('Extra\Model\DoucheTable');
        }
        return $this->doucheTable;
    }

    public function getTelevisionTable() {
        if (!$this->televisionTable) {
            $sm = $this->getServiceLocator();
            $this->televisionTable = $sm->get('Extra\Model\TelevisionTable');
        }
        return $this->televisionTable;
    }

    public function getFrigoTable() {
        if (!$this->frigoTable) {
            $sm = $this->getServiceLocator();
            $this->frigoTable = $sm->get('Extra\Model\FrigoTable');
        }
        return $this->frigoTable;
    }

}
