
#codeigniter-ExeCore


#### Use of controller and model core to simplify Codeigniter usage

---


![language](https://img.shields.io/badge/language-php-%238892BF.svg)
![GitHub](https://img.shields.io/github/license/tolgaOzen/codeigniter-ExeCore.svg)
![GitHub top language](https://img.shields.io/github/languages/top/tolgaOzen/codeigniter-ExeCore.svg)
![GitHub last commit](https://img.shields.io/github/last-commit/tolgaOzen/codeigniter-ExeCore.svg)


## setup

- Download and open codeigniter https://www.codeigniter.com

- Open application/core folder and Exe_Controller , Exe_Model paste it (our core file).

- Open application/models folder and HtmlItems_Model , Menu_Model paste it (our models file) - Customers_Model And Customer_Addresses_Model these are sample files.

- Open application/helpers folder and exe_helper paste it (our helpers file).

- Open application/views; 
    - Paste the customers.php sample file.
    - Create folders (themes , sections)  
    - Paste blank.php into your theme folder.
    - Paste menu.php, header.php, footer.php into the section folder.
    
Open application/config/ file

```php
$autoload['helper'] = array('exe_helper' , 'url' , 'text' , 'inflector');
```

and do it like this.

installation is over




## usage
```php
       /*
       |--------------------------------------------------------------------------
       | _remap
       |--------------------------------------------------------------------------
       | @param session control and remapping
       */
       
       public function _remap($method, $params = array())
       {
   
          .
          .
          .
   
               redirect("/Dashboard");
           }
   
           redirect("/SignIn");
       }
  
```
   >If session does not exist, type which page to open - change redirect("/SignIn");
   If there is session, type which page to open - change redirect("/Dashboard");
```
   public $pagesWithoutSessionControl = array("SignIn", "SignUp");
```
  >If you do not want session control on the page you can add $pagesWithoutSessionControl



### Controller Example
```php
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
  
```

##### index example Customers controller
```php

    public function index()
    {
        $customers = $this->Customers_Model->getList();
        
        $this->setData($customers);
        
        $this->render();
    }

```
```php
$this->loadModel() 
```
>loads the model with the same name whatever the controller name
 
```php
$this->setTitle("customers")
```
>html title
```php
$this->loadModels(array("Customer_Addresses"))
```
>extra model loads
  
```php
$this->loadHelpers(array('form', "url"))
```
>load helpers
  
```php
$this->loadLibraries(array('form_validation'))
```
>load libraries




### readyToUpdate and readyToInsert functions
####usage example
```php
 if (isPost()) {

    $postData = $this->input->post();
    $readyUpdateData = $this->readyToUpdate($postData[dataBaseOperationType::update]);
            
    .
    .
    .
```
##### before
```
Array
(
    [update] => Array
        (
            [customers] => Array
                (
                    [c_24] => Array
                        (
                            [customer_name] => 3 NOLU AUTOPİA YÖNETİM HİZMETLERİ İNŞAAT SAN.VE TİC.A.Ş
                            [customer_id] => 24
                        )

                )

            [customer_addresses] => Array
                (
                    [ca_62] => Array
                        (
                            [customer_address_id] => 62
                            [customer_address_value] => asdasdsadasdsad
                        )

                )

        )

)
```


##### after
```
Array
(
    [customers] => Array
        (
            [c_24] => Array
                (
                    [customer_name] => 3 NOLU AUTOPİA YÖNETİM HİZMETLERİ İNŞAAT SAN.VE TİC.A.Ş
                    [customer_id] => 24
                    [customer_update_date] => 2019-06-18 19:38:04
                    [customer_hash] => 955a3ca1a08e101ba55d19871ad5144e // md5
                )

        )

    [customer_addresses] => Array
        (
            [ca_62] => Array
                (
                    [customer_address_id] => 62
                    [customer_address_value] => asdasdsadasdsad
                    [customer_address_update_date] => 2019-06-18 19:38:04
                    [customer_address_hash] => e020369202986c0955a68241db909142 // md5
                )

        )

)
```



### Model Example
```php

class Customers_Model extends Exe_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "customers";
        $this->specialKey = "c_";
    }

```
```php
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
```
##### getList Function example return
```php
 $this->tableName = "customers";
```
```
Array
(
    [0] => Array
        (
           [customer_id] => 1
           [customer_name] => apple
        )

    [1] => Array
        (
           [customer_id] => 2
           [customer_name] => google
        )
        .
        .
        .
```
##### getSpecialOwnIdArray used function return
```php
  $this->tableName = "customers";
  $this->specialKey = "c_";
```
```
Array
(
    [c_1] => Array
        (
           [customer_id] => 1
           [customer_name] => apple
        )

    [c_2] => Array
        (
           [customer_id] => 2
           [customer_name] => google
        )
        .
        .
        .

```


### Child Table Model Example
```php
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
    .
    .
    .
```
```php
    .
    . 
    . 
    public function getCustomerAddressListCaSpecialKeys()
    {
        $customerAddressList = $this->getList();
        return $this->getSpecialOwnIdArray($customerAddressList);
    }

    public function getCustomerAddressListCustomerIdSpecialKeys()
    {
        $customerAddressList = $this->getCustomerAddressListCaSpecialKeys();
        return $this->getSpecialOwnIdAndParentIdArray($customerAddressList);
    }

```
##### getSpecialOwnIdAndParentIdArray used function return
```
Array
(
    [c_2] => Array
        (
            [ca_1] => Array
                (
                    [customer_address_id] => 1
                    [customer_address_customer_id] => 2
                    [customer_address_value] => Saka Mehmet Sok. No:23 Sultanhamam/Eminönü İstanbul
                )

        )

    [c_12] => Array
        (
            [ca_2] => Array
                (
                    [customer_address_id] => 2
                    [customer_address_customer_id] => 12
                    [customer_address_value] => Velimeşe Org. San. Böl. Mah., 259. Sokak, No:4/1 Ergene / TEKİRDAĞ 
                )

        )
        
```

### data type logic

> **json**

If you type /:json at the end of the path, the data arriving on the page returns json

```
https://example.com/Customers/index/:json
```

```json
         {
           "db": {
             "customers": {
               "example_24": {
                 "example_id": "24",
                 "example_update_date": "31-05-2019",
                 "example_hash": "955a3ca1a08e101ba55d19871ad5144e"
               }
             },
             .
             .
             .
           }
         }
```    
> **array**

If you type /:array at the end of the path, the data arriving on the page returns array
```
https://example.com/Customers/index/:array
```
```   

Array(
     [db] => Array(
       [customers] => Array(
         [example_24] => Array(
         [example_id] => 24
         [example_update_date] => 31-05-2019
         [example_hash] => 955a3ca1a08e101ba55d19871ad5144e
         )
         .
         .
         .
       )
     )
   
```  
Each controller extend from Exe_Controller works the same way

#### page details and user details     
```               
income on every page : "pageDetails": {
                           "controller": "Customers",
                           "model": "Customers_Model",
                           "view": "customerForm",
                           "method": "see",
                           "params": ["24", ":json"],
                           "queryStrings": [],
                           "localStorage": {
                             "refresh": 0
                         }
                       },
                       
                       "userDetails": {
                       "user_id": "3",
                       "user_name": "tolga"
                       }
       
```

### Menu 

#### menu is created in the Menu_Model

###### example

<p align="center">
<img src="https://user-images.githubusercontent.com/39353278/59704036-e2d9ea00-9203-11e9-94a3-973231293604.jpg" alt="confetti" width="641" height="512">
</p>


### Html Head 

#### menu is created in the Menu_Model

###### example

<p align="center">
<img src="https://user-images.githubusercontent.com/39353278/59704030-e1102680-9203-11e9-9f44-12cf89906694.jpg" alt="confetti" width="641" height="568">
</p>

```
default is added to the head on each page

If you want to add a special item to the page you can do as in Customers/index
```

## Author

Tolga Özen

e-mail : mtolgaozen@gmail.com

## License

MIT License

Copyright (c) 2019 Tolga Ozen

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.