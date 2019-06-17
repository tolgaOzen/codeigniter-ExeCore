<?php
/**
 * Created by PhpStorm.
 * User: tolgaozen
 * Date: 26.01.2019
 * Time: 02:28
 */

class HtmlItems_Model extends CI_Model
{
    private $_controllerMethodName;

    public function getHtmlItems()
    {

        $htmlItemsArray = array(

            'Customers/index' => array(

                'css' => array(
                    '/css/customers.css',
                ),

                'script' => array(
                    'src="/js/indexLocated.js"' => '',
                    1 => array('type ="text/javascript"' => "document.addEventListener('DOMContentLoaded', function() { console.log('welcome exe core')})"),
                ),

                'meta' => array(
                    "description" => "",
                ),
            ),

            'default' => array(

                'css' => array(
                    '/css/example.css',
                    '/css/example2.css',
                    '/css/example3.css',
                ),

                'script' => array(
                    'src="/js/example.js"' => '',
                    0 => array('type ="text/javascript"' => "document.addEventListener('DOMContentLoaded', function() { console.log('welcome exe core')})"),
                ),

                'meta' => array('description' => ''),
            )


        );

        return $htmlItemsArray;
    }

    /**
     * @param mixed $controllerMethodName
     */
    public function setControllerMethodName($controllerName)
    {
        $this->_controllerMethodName = $controllerName;
    }

    public function render()
    {
        $returnArray = null;
        $htmlItemsArray = $this->getHtmlItems();
        $defaultArray = $htmlItemsArray['default'];

        if (!isset($htmlItemsArray[$this->_controllerMethodName])) {
            $returnArray = $defaultArray;
        } else {
            unset($defaultArray['meta']);

            $returnArray = $htmlItemsArray[$this->_controllerMethodName];
            $returnArray = array_merge_recursive($defaultArray, $returnArray);
        }
        return $returnArray;
    }
}