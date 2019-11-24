# About The Test Project
I was given the task of developing a small project that will populate a database with properties via the external API.

This script updates the details in the database if any changes are made to the details of the property 
in the API. Internal entries which are in the same table will not be affected by this update process. 
(It was a little bit challenge for me) 

Look at the code which is below is doing this task:

src/ExternalData/PropertiesData.php
```
   /*
       * I used Dependecy Injection. "$db_driver" is an object injection to the method.
       */
      public function saveDataToDatabase($db_driver): PropertiesData
      {
          $this->db_connection = $db_driver;
  
          $this->db_connection->createTableWithColumns(self::TABLE_NAME, self::PROPERTY_TABLE_SCHEMA);
  
  
          $properties = $this->data;
  
  
          // prepare sql and bind parameters
          // This statement is checking the records which recorded to the database. If they are already in client's
          // database, do not add once again.
          $stmt = $this->db_connection->getConnection()
                                      ->prepare('INSERT INTO ' . self::TABLE_NAME .
                                          ' (' . self::PROPERTY_TABLE_COLUMNS . ') VALUES (' . self::PROPERTY_TABLE_COLUMNS2 .
                                          ') ON DUPLICATE KEY UPDATE uuid=:uuid2');
  
  
          // This foreach code block  updates the details in the database if any changes are made to the details
          // of the property in the API
          foreach ($properties as $property) {
  
              // mysql search query
              $searchQuery = 'SELECT uuid,updated_at FROM ' . self::TABLE_NAME . ' WHERE (uuid = :uuid and updated_at <> :updated_at)';
  
              $searchResult = $this->db_connection->getConnection()
                                                  ->prepare($searchQuery);
  
  
              //set your id to the query id
              $searchResult->bindParam(':uuid', $property['uuid']);
              $searchResult->bindParam(':updated_at', $property['updated_at']);
              $isUpdate = $searchResult->execute();
  
              if ($isUpdate) {
  
                  $stmt->bindParam(':uuid', $property['uuid']);
                  $stmt->bindParam(':uuid2', $property['uuid']);
                  $stmt->bindParam(':county', $property['county']);
                  $stmt->bindParam(':country', $property['country']);
                  $stmt->bindParam(':town', $property['town']);
                  $stmt->bindParam(':description', $property['description']);
                  $stmt->bindParam(':image_full', $property['image_full']);
                  $stmt->bindParam(':image_thumbnail', $property['image_thumbnail']);
                  $stmt->bindParam(':latitude', $property['latitude']);
                  $stmt->bindParam(':longitude', $property['longitude']);
                  $stmt->bindParam(':num_bedrooms', $property['num_bedrooms']);
                  $stmt->bindParam(':num_bathrooms', $property['num_bathrooms']);
                  $stmt->bindParam(':price', $property['price']);
                  $stmt->bindParam(':type', $property['type']);
                  $stmt->bindParam(':created_at', $property['created_at']);
                  $stmt->bindParam(':updated_at', $property['updated_at']);
                  $stmt->execute();
              }
          }
  
  
          return $this;
      }
```

## Installation:
1. composer update
2. You have to create a database, then put db credentials into "src/Settings.php"
There are constants. Just fill them out. Like that:

```
    CONST HOST     = 'localhost';
    CONST DB_NAME  = 'xxx';
    CONST USERNAME = 'xxx';
    CONST PASSWORD = 'xxx';
```
3. `index.php` is the main file of the app which is in the root directory.



## Github Links of the Test Project:

The source code of the test project is in GitHub repository:

[https://github.com/burhanmt/property.git](https://github.com/burhanmt/property.git)

## Code Standards
It is very important for me, therefore I strictly followed  [PSR-1](https://www.php-fig.org/psr/psr-1/) code standard 
and [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloading standard for the PHP. 

For example:
(PSR-4) autoloading classes from file paths.
Folder name: Database, file name: MySql.php, so MySql class:

src/Database/MySql.php
```
namespace Database;

use PDO;
use PDOException;

class MySql
{

```

My `composer.json` PSR-4 settings:

```
"autoload": {
    "classmap": [
      "src/",
      "models/",
      "controller/"
    ]
  },
```

## Architecture and Design Patterns


> ### The intention of this test project is to show you my skills, so I preferred Vanilla PHP instead of any framework.

I used simple MVC architecture model.  I created my own MVC system. I didn't use any PHP Framework and I used less 3rd party component(**Actually Guzzle only**).  I  used some  "Design Patterns" as much as possible.
Such as Factory, Builder and Method Chaining (also known as Fluent Interface) and Strategy Design Pattern. And also I strictly followed  -S- Single Responsibility Principle of "**S**OLID".

Example-1:
src/Database/MySqlCredentialBuilder.php

```
    public function setHost(string $host): MySqlCredentialBuilder
    {
        $this->host = $host;

        return $this;
    }

```

If you look at the code that is above, It returns an object like: `return this;`, allowing the calls to be chained together in a single statement.
Other methods also return the object in "MySqlCredentialBuilder.php" class.
So When I use it, it looks like a chain, like that:


```
 *                $credentials=  (new MySqlCredentialBuilder())->setHost('127.0.0.1')
 *                                                             ->setDbName('sample_db_name')
 *                                                             ->setUsername('burhan')
 *                                                             ->setPassword('2345);
```
Actually this class is the combination of "Builder Design Pattern and Method Chaining",
 so you can easily inject the credentials in a beautiful way, like that:

```
               $credentials=  (new MySqlCredentialBuilder())->setHost('127.0.0.1')
                                                             ->setDbName('sample_db_name')
                                                             ->setUsername('burhan')
                                                             ->setPassword('2345);

$my_db = new MySql($credentials);

```


**Example-2:**
I preferred to use "Factory Design Pattern" to create external data source independently. A factory is an object that 
can creates object without use of the constructor. I can create external sources  with this beautiful technique 
in a convenient way. In the future we can have more external sources, so this class is the one responsible to
instantiate them without using constructor.

Look at the code below how to create a class(external source component) with "DataFactory" class:
```
      $properties = DataFactory::obtainData(PropertiesData::class)
                                 ->getJsonDataFromExternalSource()
                                 ->showData();
```
"PropertiesData::class" is an external source component. It is sealed. You can not inherit or change the properties of 
this class. If you want to get data from  another external source, create another component following the common
interface of the "ObtainDataInterface.php" file. Like that:

src/Database/ObtainDataInterface.php
```
<?php

namespace ExternalData;

interface ObtainDataInterface
{
    public function getJsonDataFromExternalSource(): PropertiesData;

    public function saveDataToDatabase($db_driver): PropertiesData;

    public function showData(): array;

}
```

So you will not have any incompatible interfaces among the "External Data Source" components. After building your
external data, you can create it via DataFactory. My technique which is used in DataFactory is also  "Strategy
Design Pattern". Because you can take a group of algorithms(like "PropertiesData::class" ) and makes them
interchangeable from common interface at runtime. We can do it. Look at below:


```
      $properties = DataFactory::obtainData(PropertiesData::class)
                                 ->getJsonDataFromExternalSource()
                                 ->showData();
```

You can inject another external data component instead of `PropertiesData::class` at runtime. Isn't it?

## File Structure
I mentioned above regarding MVC architectural pattern.
I simplified the folder structure as much as possible like below:

```
- assets : This folder consists of images,css and js files of the project.
- model : This folder consists of the generated data of the project.
- view: All page templates of the project is inside this folder.
- controller: All page controllers of the project is in this folder. 
- src: Core system components are inside this folder.
- vendor: Third party components are inside this folder.
- Routes.php :  All routes are inside this file.
```
It allows me to work Model, View, Controller layer separately, so there is less
code complexity and confusion.

## Security
I added simple but robust CSRF protection for the AJAX requests. I admit, it was difficult to develop a new system without using framework. Because  you should make everything from scratch and your
project will be more vulnerable. 

CSRF is Cross Site Request Forgery Attack. That attack is performed by making fake forms or requests that behaves exactly same as in original website but it is not coming the target's
website's server.

For the CSRF protection, the website generates a random token in each form as a hidden value. This token is associated with the users’ current session. Once the form is submitted, website verifies whether the
  random token comes via request. If yes, then verify whether it is right. By using this method, developers can easily identify whether the request was made by the user of attacker.

I used it.

Look at the

view/AdminPanel.php file:
```
                    <input type="hidden" id="token_" value="<?php
                    echo hash_hmac('sha256', 'AdminPanel.php', $_SESSION['csrf_token']);
                    ?>"/>
```

`$_SESSION['csrf_token']` is generating in our test project's server for the user's session.

like that:
```
//--- Creating CSRF token for the security reason of the ajax requests.
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // it requires PHP 7.0+

    } catch (Exception $e) {

        die('Failed to generate the CSRF token!');
    }
}
```

And we can easily check the CSRF token with a small function , it is valid or not. Like that:

src/CsrfVerify.php
```
    public static function csrfCheck(string $session_token, string $form_token, string $form_name): bool
    {
        //This statement is CSRF security control spot.
        if (isset($session_token, $form_token)) {
            $calc = hash_hmac('sha256', $form_name, $session_token);


            if (hash_equals($calc, $form_token)) {

                return true;

            } else {

                return false;
            }
        } else {
            return false;
        }
    }
```
I used different CSRF token for each page, adding  their path like that: "AdminPanel.php". It is twofold security.
In the test project, Of course  I used one page only but if I use another page for the future development, the logic 
is ready to apply for the rests.

## Design
For the design,I didn't spend too much time. I used Vue.js for adding a property and validation of the form.
The test project design is very simple and primitive. 
I didn't add image upload feature to the test project. Because of the time limitation. Honestly, I focused on
code standards, design patterns and creating an architecture of the test program.


## Any future plans for how the application could be improved

- My existing architecture doesn't have a proper route system. In the future, I can enhance the route mechanism.
- The MVC architecture is very primitive. I can make it more useful in the future.
- There is no image upload and resize of the image feature. I didn't develop it yet due to time pressure.
- I didn't add PHP Unit test. But I should definitely add it in the future.
- I should improve error-handling of the app.
- I didn't write: "I followed strictly DRY(Don't Repeat Yourself)" principle. Because after checking my whole codes I
realised that I broke this principle in some part of the code.

A concrete example of broken DRY principle in my code is in src/ExternalData/PropertiesData.php:

```
    const PROPERTY_TABLE_COLUMNS  = 'uuid, county, country, town, description, image_full, image_thumbnail, latitude, longitude, num_bedrooms, num_bathrooms, price, type, created_at, updated_at';
    const PROPERTY_TABLE_COLUMNS2 = ':uuid, :county, :country, :town, :description, :image_full, :image_thumbnail, :latitude, :longitude, :num_bedrooms, :num_bathrooms, :price, :type, :created_at, :updated_at';
```

But I can fix it. Firstly I can make "PROPERTY_TABLE_COLUMNS" an "Array",
after that I can overcome this issue using "implode" function. Like that:

```
const PROPERTY_TABLE_COLUMNS  = ['uuid','county', 'country', .....

           $columns1 =  implode(", ", array_keys(self::PROPERTY_TABLE_COLUMNS));
            
           $columns2 =   ":" . implode(", :", array_keys(self::PROPERTY_TABLE_COLUMNS))
```

It means no need to define  "PROPERTY_TABLE_COLUMNS2" constant. Anyway, I didn't update it in the code.
 

## Requirements

- PHP 7.0 and above.
- to create a db and fill in the credentials constants which are in src/Settings.php file.

## Conclusion
In a nutshell, I developed  readable, expandable test project. I am not so satisfied of the codes which I've written for this
test project, so quality code requires to spend more time and think.




# Developer

Burhan TANIS

_Full-Stack Web Developer_

**Portfolio and digital resume:** [https://portfolio.burhantanis.com](https://portfolio.burhantanis.com)
 
**E-mail:** burhanmt@outlook.com



