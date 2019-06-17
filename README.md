# codeigniter-ExeCore
#### Use of controller and model core to simplify Codeigniter usage


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
```
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
   If session does not exist, type which page to open - change redirect("/SignIn");
   If there is session, type which page to open - change redirect("/Dashboard");
```
   public $pagesWithoutSessionControl = array("SignIn", "SignUp");
```
  If you do not want session control on the page you can add $pagesWithoutSessionControl



### Controller Example
```
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
```

   
    public function index()
    {
        $customers = $this->Customers_Model->getList();
        
        $this->setData($customers);
        
        $this->render();
    }

}
```
```
  $this->loadModel() 
```
  loads the model with the same name whatever the controller name
 
```
 $this->setTitle("customers")
```
 html title
```
  $this->loadModels(array("Customer_Addresses"))
```
  extra model loads
  
```
  $this->loadHelpers(array('form', "url"))
```
  load helpers
  
```
    $this->loadLibraries(array('form_validation'))
```
   load libraries





### Model Example
```

class Customers_Model extends Exe_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "customers";
        $this->specialKey = "c_";
    }
    .
    .
    .
```
```
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
```
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
```
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
```
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
```
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

#### json
```
https://.../:json

example : "db": {
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
     
```    
#### array
```   
https://.../:array            
       
       example : Array(
                   [db] => Array(
                     [customers] => Array(
                       [example_24] => Array(
                         [example_id] => 24
                           [example_update_date] => 31-05-2019
                           [example_hash] => 955a3ca1a08e101ba55d19871ad5144e
                             )
                           )
                   .
                   .
                   .
   
```  

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
<img src="https://user-images.githubusercontent.com/39353278/59629755-3852c000-914c-11e9-9696-c12585f46104.jpg" alt="confetti" width="619" height="491">
</p>


### Html Head 

#### menu is created in the Menu_Model

###### example

<p align="center">
<img src="https://user-images.githubusercontent.com/39353278/59631169-d98f4580-914f-11e9-9e64-dabd86473aff.jpg" alt="confetti" width="619" height="491">
</p>

```
default is added to the head on each page

If you want to add a special item to the page you can do as in Customers/index
```