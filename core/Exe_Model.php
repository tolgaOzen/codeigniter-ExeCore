<?php
/**
 * Created by PhpStorm.
 * User: tolgaozen
 * Date: 20.12.2018
 * Time: 02:29
 */

class Exe_Model extends CI_Model
{
    public $tableName;
    public $specialKey;
    public $relationUpperTableSpecialKey;
    public $relationUpperTableName;

    public $_insertData;
    public $_updateData;
    public $_postData;

    public $_relationColumnKey;
    public $_relationValue;

    public function __construct()
    {
        parent::__construct();
    }


    /*
    |--------------------------------------------------------------------------
    | getSpecialOwnIdArray
    |--------------------------------------------------------------------------
    | @param array keys nodes ex: persons table ,  $specialKey = p_ , person_id = 32 == p_32
    */
    public function getSpecialOwnIdArray($array)
    {
        return $this->specialOwnIdParser($array);
    }

    private function specialOwnIdParser($array)
    {
        $schema = array();

        $v = $this->getSingularColumnName($this->tableName . '_id');

        foreach ($array as $value) {
            $schema[$this->specialKey . $value[$v]] = $value;
        }

        return $schema;
    }


    /*
    |--------------------------------------------------------------------------
    | getSpecialOwnIdAndParentIdArray
    |--------------------------------------------------------------------------
    | @param array keys nodes ex: persons table ,  $specialKey = p_ , customer = c_ , person_id = 32 , person_customer_id = 55 == c_55 , p_32
    */
    public function getSpecialOwnIdAndParentIdArray($array)
    {
        return $this->getSpecialOwnIdAndParentIdParser($array);
    }

    private function getSpecialOwnIdAndParentIdParser($array)
    {
        $schema = array();

        $v = $this->getSingularColumnName($this->tableName . '_' . $this->relationUpperTableName . '_id');

        foreach ($array as $key => $value) {
            $schema[$this->relationUpperTableSpecialKey . $value[$v]][$key] = $value;
        }

        return $schema;
    }


    /*
    |--------------------------------------------------------------------------
    | getList
    |--------------------------------------------------------------------------
    | @param get table all column and rows
    */
    public function getList($columnProperties = '*')
    {
        $resultArray = array();
        if ($this->tableName != null) {

            $this->db->select($columnProperties);
            $this->db->from($this->tableName);
            $query = $this->db->get();
            if ($query->num_rows() != 0) {
                $resultArray = $query->result_array();
            }
        }
        return $resultArray;
    }


    /*
    |--------------------------------------------------------------------------
    | search
    |--------------------------------------------------------------------------
    | @param get table all column and rows
    */
    public function search($where, $like, $columnProperties = '*')
    {
        $resultArray = array();
        if ($this->tableName != null) {

            $this->db->select($columnProperties);
            $this->db->from($this->tableName);
            $this->db->like($where, $like);


            $query = $this->db->get();
            if ($query->num_rows() != 0) {
                $resultArray = $query->result_array();
            }
        }
        return $resultArray;
    }


    /*
    |--------------------------------------------------------------------------
    | getCount
    |--------------------------------------------------------------------------
    | @param get table all column and rows
    */
    public function getCount($columnProperties = 'count(*) as count')
    {
        $resultArray = array();
        if ($this->tableName != null) {

            $this->db->select($columnProperties);
            $this->db->from($this->tableName);

            $query = $this->db->get();
            if ($query->num_rows() != 0) {
                $resultArray = $query->result_array();
            }
        }
        return $resultArray[0];
    }


    /*
    |--------------------------------------------------------------------------
    | getRowNumber
    |--------------------------------------------------------------------------
    | @param
    */
    public function getRowNumber()
    {
        $this->db->from($this->tableName);
        $query = $this->db->get();
        $rowcount[$this->tableName]['num_rows'] = $query->num_rows();
        return $rowcount;
    }

    /*
    |--------------------------------------------------------------------------
    | getSingularColumnName
    |--------------------------------------------------------------------------
    | @param
    */
    public function getSingularColumnName($RowName)
    {
        $returnString = null;
        $array = array();

        $splitColumnName = explode("_", $RowName);

        foreach ($splitColumnName as $ColumnName) {

            $singularRowName = singular($ColumnName);

            array_push($array, $singularRowName);
        }

        $returnString = implode("_", $array);


        return $returnString;
    }

    /*
    |--------------------------------------------------------------------------
    | update
    |--------------------------------------------------------------------------
    | @param
    */
    public function update($data, $columnName, $value)
    {
        $this->db->where($columnName, $value);

        $this->db->update($this->tableName, $data);

        $returnVal = $this->affectedRowControl();

        return $returnVal;
    }


    public function errorControl()
    {
        $error = $this->db->error();
        if ($error['message'] == null) {
            return true;
        }
        return $error['message'];
    }

    /*
   |--------------------------------------------------------------------------
   | insertDataGetAddedId
   |--------------------------------------------------------------------------
   | @param
   */
    public function insertDataGetAddedId($data)
    {

        $this->db->insert($this->tableName, $data);

        $returnVal = $this->affectedRowControl();
        if ($returnVal == false) {
            return $returnVal;
        }

        $id = $this->db->insert_id();

        return $id;

    }


    /*
    |--------------------------------------------------------------------------
    | insert not be used func you can use insertData func
    |--------------------------------------------------------------------------
    | @param
    */
    public function insert($data)
    {

        $this->db->insert($this->tableName, $data);

        $returnVal = $this->affectedRowControl();

        return $returnVal;

    }


    /*
    |--------------------------------------------------------------------------
    | affectedRowControl
    |--------------------------------------------------------------------------
    | @param get table all column and rows
    */
    public function affectedRowControl()
    {

        if ($this->db->affected_rows() > 0) {

            return true;

        } else {

            return false;

        }

    }


    public function addRelationValue($relationColumnKey, $relationValue)
    {

        $this->_relationColumnKey = $relationColumnKey;
        $this->_relationValue = $relationValue;

    }

    /*
   *  @param insert
   */
    public function insertData($data)
    {

        $this->_insertData = $data;

        $returnVal = $this->insert($this->_insertData);

        return $returnVal;
    }


    public function updateData($value, $data, $columnKey = "_id")
    {

        $this->_updateData = $data;

        $column = $this->tableName . $columnKey;

        $columnName = $this->getSingularColumnName($column);

        $returnVal = $this->update($value, $this->_updateData, $columnName);

        return $returnVal;
    }

}
