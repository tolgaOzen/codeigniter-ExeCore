<?php
/**
 * Created by PhpStorm.
 * User: tolgaozen
 * Date: 5.01.2019
 * Time: 02:29
 */

class Customers_Model extends Exe_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "customers";
        $this->specialKey = "c_";
    }

    /*
    |--------------------------------------------------------------------------
    | getList
    |--------------------------------------------------------------------------
    | @param Exe_model getList function
    */

    /*
    |--------------------------------------------------------------------------
    | getCustomersListCustomerIdSpecialKeys
    |--------------------------------------------------------------------------
    | @param array keys = customerId , c_ = customer
    */
    public function getCustomersListCustomerIdSpecialKeys()
    {
        $customerList = $this->getList();
        return $this->getSpecialOwnIdArray($customerList);
    }


    /*
    |--------------------------------------------------------------------------
    | updateCustomerData
    |--------------------------------------------------------------------------
    | @param
    */
    public function updateCustomerData($data)
    {
        $returnVal = "";

        $columnName = singular($this->tableName) . '_id';
        foreach ($data as $key => $d) {
            $endVal = getEndValue($key);
            $returnVal = $this->update($d, $columnName, $endVal);
            if (!$returnVal) {
                break;
            }
        }

        return $returnVal;
    }

    /*
    |--------------------------------------------------------------------------
    | insertCustomerData
    |--------------------------------------------------------------------------
    | @param
    */
    public function insertCustomerData($data)
    {
        $returnVal = "";
        foreach ($data as $d) {

            $returnVal = $this->insertDataGetAddedId($d);

            if (!$returnVal) {
                break;
            }
        }
        return $returnVal;
    }


}

