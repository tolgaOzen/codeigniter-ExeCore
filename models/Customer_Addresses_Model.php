<?php
/**
 * Created by PhpStorm.
 * User: tolgaozen
 * Date: 11.01.2019
 * Time: 21:07
 */

class Customer_Addresses_Model extends Exe_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "customer_addresses";
        $this->specialKey = "ca_";
        $this->relationUpperTableSpecialKey = 'c_';
        $this->relationUpperTableName = 'customers';
    }

    /*
    |--------------------------------------------------------------------------
    | getList
    |--------------------------------------------------------------------------
    | @param Exe_model getList function
    */


    /*
   |--------------------------------------------------------------------------
   | getCustomerAddressListCASpecialKeys
   |--------------------------------------------------------------------------
   | @param array keys = customerAddressId , ca_ = customerAddress
   */
    public function getCustomerAddressListCaSpecialKeys()
    {
        $customerAddressList = $this->getList();
        return $this->getSpecialOwnIdArray($customerAddressList);
    }

    /*
    |--------------------------------------------------------------------------
    | getPersonsKeyCustomerIdList
    |--------------------------------------------------------------------------
    | @param array keys = customerId , child keys customerAddressId , c_ = customer , ca_ = customerAddress
    */
    public function getCustomerAddressListCustomerIdSpecialKeys()
    {
        $customerAddressList = $this->getCustomerAddressListCaSpecialKeys();
        return $this->getSpecialOwnIdAndParentIdArray($customerAddressList);
    }

    /*
    |--------------------------------------------------------------------------
    | updateCustomerAddressData
    |--------------------------------------------------------------------------
    | @param array keys = customerId , child keys customerAddressId , c_ = customer , ca_ = customerAddress
    */
    public function updateCustomerAddressData($data)
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
    | insertCustomerAddressData
    |--------------------------------------------------------------------------
    | @param
     */
    public function insertCustomerAddressData($data, $id)
    {
        $returnVal = "";
        foreach ($data as $d) {

            $d[singular($this->tableName) . '_' . singular($this->relationUpperTableName) . '_id'] = $id;

            $returnVal = $this->insert($d);

            if (!$returnVal) {
                break;
            }
        }

        return $returnVal;
    }

    /*
    |--------------------------------------------------------------------------
    | deleteCustomerAddress
    |--------------------------------------------------------------------------
    | @param
     */
    public function deleteCustomerAddress($id)
    {
        $this->db->delete($this->tableName, array('customer_address_id' => $id));
        return $this->errorControl();
    }


}