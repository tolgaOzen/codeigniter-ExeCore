<?php
/**
 * Created by PhpStorm.
 * User: tolgaozen
 * Date: 5.01.2019
 * Time: 02:28
 */


class Customers extends Exe_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->setControllerName("Customers");

        $this->setTitle("Customers");

        $this->loadModel();

        $this->loadModels(array("Customer_Addresses"));

        $this->loadHelpers(array('form', "url"));

        $this->loadLibraries(array('form_validation'));

    }

    public function index()
    {

        $customers = $this->Customers_Model->getList();

        $this->setData($customers);
        $this->render();
    }



}





