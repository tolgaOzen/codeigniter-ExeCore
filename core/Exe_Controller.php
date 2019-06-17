<?php

/**
 * Created by PhpStorm.
 * User: tolgaozen
 * Date: 20.12.2018
 * Time: 02:43
 */

abstract class RenderTypes
{
    const HtmlType = 0;
    const JsonType = 1;
    const TxtType = 2;
    const ArrayType = 3;
    const ObjectType = 4;

}


class Exe_Controller extends CI_Controller
{

    private $_controllerName;
    private $_currentMethod;
    private $_currentParam;

    private $_fullPath;
    private $_modelName;
    private $_viewName;
    private $_title;
    private $_data;
    private $_renderType;

    public $userDetails = array();

    public $pagesWithoutSessionControl = array("SignIn", "SignUp");

    public function __construct()
    {
        parent::__construct();
    }


    private function fillRenderType($params)
    {
        $renderTypes = array(
            0 => RenderTypes::HtmlType,
            ":json" => RenderTypes::JsonType,
            ":html" => RenderTypes::HtmlType,
            ":txt" => RenderTypes::TxtType,
            ":array" => RenderTypes::ArrayType,
            ":object" => RenderTypes::ObjectType
        );

        $endArray = end($params);
        if (isset($renderTypes[$endArray])) {
            $this->_renderType = $renderTypes[$endArray];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | _remap
    |--------------------------------------------------------------------------
    | @param session control and remapping
    */
    public function _remap($method, $params = array())
    {

        $this->fillRenderType($params);

        $this->userDetails = $this->getSession(true);

        if ($this->userDetails == true || in_array($this->_controllerName, $this->pagesWithoutSessionControl)) {

            $this->_currentMethod = $method;
            $this->_currentParam = $params;

            $this->_fullPath = $this->_controllerName . '/' . $method;

            if ($params != null) {

                foreach ($params as $param) {
                    $this->_fullPath .= '/' . $param;
                }

            }

            if (method_exists($this, $this->_currentMethod)) {

                return call_user_func_array(array($this, $this->_currentMethod), $this->_currentParam);

            };

            redirect("/Dashboard");
        }

        redirect("/SignIn");
    }

    /*
    |--------------------------------------------------------------------------
    | readyToUpdate
    |--------------------------------------------------------------------------
    | @param
    */
    public function readyToUpdate($data)
    {

        $returnArray = array();

        $datetime = date_create()->format('Y-m-d H:i:s');

        foreach ($data as $modelName => $childKey) {

            foreach ($childKey as $specialKey => $d) {

                $hash = arrayMd5Hash($d);
                $d[singular($modelName) . "_update_date"] = $datetime;
                $d[singular($modelName) . "_hash"] = $hash;
                $returnArray[$modelName][$specialKey] = $d;
            }

        }

        return $returnArray;
    }


    /*
   |--------------------------------------------------------------------------
   | readyToInsert
   |--------------------------------------------------------------------------
   | @param inseet have not child key because not created data
   */
    public function readyToInsert($data)
    {

        $returnArray = array();

        $datetime = date_create()->format('Y-m-d H:i:s');

        foreach ($data as $modelName => $insetDatas) {

            foreach ($insetDatas as $specialKey => $insertData) {

                $hash = arrayMd5Hash($insertData);

                $insertData[singular($modelName) . "_created_date"] = $datetime;
                $insertData[singular($modelName) . "_update_date"] = $datetime;
                $insertData[singular($modelName) . "_hash"] = $hash;

                $returnArray[$modelName][] = $insertData;

            }

        }
        return $returnArray;
    }




    /*
    |--------------------------------------------------------------------------
    | loadHelpers
    |--------------------------------------------------------------------------
    | @param
    */
    public function loadHelpers($var = array())
    {
        $this->load->helper($var);
    }

    /*
    |--------------------------------------------------------------------------
    | loadLibraries
    |--------------------------------------------------------------------------
    | @param
    */
    public function loadLibraries($var = array())
    {
        $this->load->library($var);
    }


    /*
    |--------------------------------------------------------------------------
    | setViewName
    |--------------------------------------------------------------------------
    | @param set $this->_viewName variable
    */
    public function setViewName($viewName)
    {
        $this->_viewName = $viewName;
    }


    /*
    |--------------------------------------------------------------------------
    | setModelName
    |--------------------------------------------------------------------------
    | @param set $this->_modelName variable
    */
    public function setModelName($modelName)
    {
        $this->_modelName = $modelName;
    }

    /*
    |--------------------------------------------------------------------------
    | setControllerName
    |--------------------------------------------------------------------------
    | @param set $this->_controllerName variable
    */
    public function setControllerName($controllerName)
    {
        $this->_controllerName = $controllerName;
    }

    /*
    |--------------------------------------------------------------------------
    | setTitle
    |--------------------------------------------------------------------------
    | @param set $this->_title variable
    */
    public function setTitle($var)
    {
        $this->_title = $var;
    }

    /*
    |--------------------------------------------------------------------------
    | setData
    |--------------------------------------------------------------------------
    | @param set $this->_data['db'] variable
    */
    public function setData($data, $encapsulationName = 'db')
    {
        $this->_data[$encapsulationName] = $data;
    }


    /*
    |--------------------------------------------------------------------------
    | loadModels
    |--------------------------------------------------------------------------
    | @param load different models from this controller name
    */

    public function loadModels($OtherModel = array())
    {
        if (count($OtherModel) > 0) {
            foreach ($OtherModel as $item) {
                $item .= '_Model';
                $this->load->model($item);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | loadModel
    |--------------------------------------------------------------------------
    | @param load controller name of model with same name
    */
    public function loadModel()
    {
        if ($this->_modelName == null) {
            $this->_modelName = $this->_controllerName . '_Model';
            $this->load->model($this->_modelName);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | getHTMLItems
    |--------------------------------------------------------------------------
    | @param get css, script, meta data
    */
    private function getHTMLItems()
    {
        $this->load->model('HtmlItems_Model', "HtmlItems");
        $this->HtmlItems->setControllerMethodName($this->_controllerName . '/' . $this->_currentMethod);
        return $this->HtmlItems->render();
    }

    /*
    |--------------------------------------------------------------------------
    | getSession
    |--------------------------------------------------------------------------
    | @param get session data
    */
    private function getSession($flag = true)
    {
        $userData = $this->session->userdata();
        if ($flag) {
            unset($userData['__ci_last_regenerate']);
        }
        return $userData;
    }


    /*
    |--------------------------------------------------------------------------
    | renderSection
    |--------------------------------------------------------------------------
    | @param load view return string set $this->_data['page']['sections'] value
    */
    private function renderSection($sectionName)
    {
        $this->_data['page']['sections'][$sectionName] = array();
        $content = $this->load->view('sections/' . $sectionName, $this->_data, true);
        $this->_data['page']['sections'][$sectionName] = $content;
    }

    /*
    |--------------------------------------------------------------------------
    | renderPage
    |--------------------------------------------------------------------------
    | @param render all data in $this->_data array completed ,sections and body fill in this func
    */
    private function renderPage($viewName)
    {
        $output = '';

        if ($viewName != null) {
            $_view = $viewName;
        } else {
            $_view = strtolower($this->_controllerName);
        }


        $content = $this->load->view($_view, $this->_data, true);

        foreach ($this->_data['page']['sections'] as $o) {
            $output .= $o;
        }


        if (isset($this->_data['page']['modules'])) {
            foreach ($this->_data['page']['modules'] as $m) {
                $output .= $m;
            }
        }

        $this->_data['page']['htmlContent'] = $output . $content;
    }


    /*
    |--------------------------------------------------------------------------
    | getMenuHtmlRender
    |--------------------------------------------------------------------------
    | @param menu_model return menu HTML
    */
    private function getMenuHtmlRender()
    {
        $this->load->model('Menu_Model', "menuModel");
        $this->menuModel->setControllerName($this->_controllerName);
        return $this->menuModel->getMenuHTML();
    }

    /*
    |--------------------------------------------------------------------------
    | getMenuArrayRender
    |--------------------------------------------------------------------------
    | @param menu_model return menu array
    */
    private function getMenuArrayRender()
    {
        $this->load->model('Menu_Model', "menuModel");
        $this->menuModel->setControllerName($this->_controllerName);
        return $this->menuModel->getMenuArray();
    }

    /*
    |--------------------------------------------------------------------------
    | getMenuObjectRender
    |--------------------------------------------------------------------------
    | @param menu_model return menu object
    */
    private function getMenuObjectRender()
    {
        return (object)$this->getMenuArrayRender();
    }


    /*
    |--------------------------------------------------------------------------
    | render
    |--------------------------------------------------------------------------
    | @param exe_controller render, edit data and data fill blank.php
   */
    public function render($type = 0)
    {

        $output = null;

        $queryStrings = $this->input->get();

        $this->_data['pageDetails'] = array(
            'controller' => $this->_controllerName,
            'model' => $this->_modelName,
            'view' => $this->_viewName,
            'method' => $this->_currentMethod,
            'params' => $this->_currentParam,
            'queryStrings' => $queryStrings,
            'localStorage' => array('refresh' => 0),
        );

        $this->_data['userDetails'] = $this->getSession();


        if ($this->_renderType == 0) {
            $currentType = $type;
        } else {
            $currentType = $this->_renderType;
        }


        switch ($currentType) {
            case 0 :

                $this->_data['page']['title'] = $this->_title;
                $this->_data['menu'] = $this->getMenuArrayRender();

//                $this->renderSection('header');
                $this->renderSection('menu');

                $this->renderPage($this->_viewName);

                $data = $this->getHTMLItems();
                $data['output'] = $this->_data['page']['htmlContent'];
                $data['title'] = $this->_data['page']['title'];

                $this->load->view('themes/blank', $data);

                break;

            case 1 :

                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($this->_data);

                break;

            default:

                pre($this->_data);
                break;
        }


    }



    /*
    |--------------------------------------------------------------------------
    | not be used func list
    |--------------------------------------------------------------------------
    | @param
    */


    /*
 MODULES
   */

    // bir sey modulse icnde sectionlar bulunur oyuzden for lanir
    public function renderModule($moduleName)
    {

        $sectionHtml = '';
        foreach ($this->_data[$moduleName] as $sectionArray) {

            $content = $this->load->view('sections/' . key($sectionArray), $sectionArray, true);
            $sectionHtml .= $content;

        }

        $d['items'] = $sectionHtml;
        $this->_data['page']['modules'][$moduleName] = array();
        $content = $this->load->view('modules/' . $moduleName, $d, true);
        $this->_data['page']['modules'][$moduleName] = $content;

    }


    /*
   |--------------------------------------------------------------------------
   | renderFormModule
   |--------------------------------------------------------------------------
   | @param
   */
    public function renderFormModule($data)
    {
        $this->load->model('form_model');
        $this->_data['page']['modules']['form'] = array();
        $content = $this->form_model->formRenderHtml($data, $this->_fullPath);
        $this->_data['page']['modules']['form'] = $content;
    }


    /*
   |--------------------------------------------------------------------------
   | loadForm
   |--------------------------------------------------------------------------
   | @param load form validation
   */
    public function loadForm()
    {
        $this->load->library(array('form_validation'));
        $this->load->helper(array('form', "url"));
    }

    public function setSection($sectionName, $val)
    {
        $this->_data[$sectionName] = $val;
        $this->renderSection($sectionName);
    }

}


