<?php
/**
 * Created by PhpStorm.
 * User: tolgaozen
 * Date: 12.01.2019
 * Time: 20:34
 */

class Menu_Model extends CI_Model
{
    private $_controllerName;

    /*
    |--------------------------------------------------------------------------
    | getMenuskus
    |--------------------------------------------------------------------------
    | @param if you want menu item add menu information array
    */
    public function getMenuskus()
    {

        $menuArray = array(

            array(
                'displayName' => "Customers",
                'link' => "Customers",
                'controllerName' => 'Customers',
                'icon' => 'customer-icon',
                'color' => 'white'
            ),


        );

        return $menuArray;
    }

    /*
    |--------------------------------------------------------------------------
    | setControllerName
    |--------------------------------------------------------------------------
    | @param controller name set func understand which controller i am
    */
    public function setControllerName($controllerName)
    {
        $this->_controllerName = $controllerName;
    }

    /*
    |--------------------------------------------------------------------------
    | getMenuArray
    |--------------------------------------------------------------------------
    | @param set active passive info in array
    */
    public function getMenuArray()
    {
        $item = array();
        foreach ($this->getMenuskus() as $menu) {
            if ($menu['controllerName'] == $this->_controllerName) {

                $menu['active'] = 'active';
            } else {
                $menu['active'] = '';
            }
            array_push($item, $menu);
        }
        return $item;
    }

    /*
    |--------------------------------------------------------------------------
    | getMenuHTML
    |--------------------------------------------------------------------------
    | @param set active passive info in and render html items
    */
    public function getMenuHTML()
    {
        $item = null;
        foreach ($this->getMenuskus() as $menu) {
            $isActive = '';
            if ($menu['controllerName'] == $this->_controllerName) {
                $isActive = 'active';
            }
            $item .= "<li class='{$isActive}'><a href='/{$menu['link']}'><i class='icon-chevron-right'></i> {$menu['displayName']}</a></li>";
        }
        return $item;
    }

}