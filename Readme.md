# About The Test Project
I was given the task of developing a small project that will populate a database with properties via the external API.
The script has populated the database from the external API source. I did not implement pagination feature due to time pressure 
of the test project. This script updates the details in the database if any changes are made to the details of the property 
in the API. Internal entries which are in the same table will not be affected by this update process. 
(It was a little bit challenge for me) 

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
and [PSR-4](https://www.php-fig.org/psr/psr-4/) standard for the PHP. 

For example:
(PSR-4) autoloading classes from file paths.
Folder name: Database, file name: MySql.php, so MySql class:

```
namespace Database;

use PDO;
use PDOException;

class MySql
{

```

## Architecture and Design Patterns
I used simple MVC model.  I created my own MVC system. I didn't use any PHP Framework and I used less 3rd party component.  I  used some  "Design Patterns" as much as possible.
Such as Factory, Builder and Method Chaining (also known as Fluent Interface) patterns. 

Example-1:
src/Database/MySqlCredentialBuilder.php

```
    public function setHost(string $host): MySqlCredentialBuilder
    {
        $this->host = $host;

        return $this;
    }

```

If you look at the code that is above, It returns an object like: `return this;`, allowing the calls to be chained together in a single statement without requiring variables to store the intermediate results.
Other methods also return the object in "MySqlCredentialBuilder.php" class.
So When I use it, it looks like a chain, like that:

controller/WindTurbinePageController.php
```
 *                $credentials=  (new MySqlCredentialBuilder())->setHost('127.0.0.1')
 *                                                             ->setDbName('sample_db_name')
 *                                                             ->setUsername('burhan')
 *                                                             ->setPassword('2345);
```
Actually this class is the combination of "Builder Design Pattern and Method Chaining",
 so you can easily inject the credentials in a beautiful way, like that:

```


$my_db = new MySql($credentials);

```


**Example-2:**
I preferred to use "Factory Design Pattern" to create external data source independently. A factory is an object that 
can creates object without use of the constructor. I can create external source factory with this beautiful technique 
in a convenient way. In the future we can have more external source, so this class is the one responsible to
instantiate them without complexity.

Look at the code below how to instantiate a class with "FactroyData":
```
      $properties = DataFactory::obtainData(PropertiesData::class)
                                 ->getJsonDataFromExternalSource()
                                 ->showData();
```
"PropertiesData::class" is a external source component. It is sealed. You can not inherit or change the properties of 
this class. You can instantiate it via DataFactory. It makes our code more readable and more expandable.


## File Structure
I mentioned above regarding MVC architectural pattern.
I simplified the folder structure as much as possible like below:

```
- assets : This folder consists of images,css and js files of the project.
- model : This folder consists of the generated data of the project.
- view: All page templates of the project is inside this folder.
- controller: All page controllers of the project is in this folder. 
- src: Core system components are inside this folder.
- Routes.php :  All routes are inside this file.
```
It allows me to work Model, View, Controller layer separately, so there is less
code complexity and confusion.

## Security
I added simple but robust CSRF protection for the AJAX requests. I admit, it was difficult to develop a new system without using framework. Because Everything, you should make from scratch and your
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

src/Settings.php
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
In the test project, Of course  I used one form only but if I use another form for the future development, the logic 
is ready to apply for the rests.

## Design
For the design,I didn't spend too much time. I used Vue.js for adding a propery and validation of the form.
The test project design is very simple and primitive. 
I didn't add image upload feature to the test project. Because of the time limitation. Honestly, I focused on
code standards, design patterns and creating an architecture of the test program.


## Any future plans for how the application could be improved

- My existing architecture doesn't have a proper route system. In the future, I can enhance the route mechanism.
- The MVC architecture is very primitive. I can make it more useful in the future.
- There is no image upload and resize of the image feature. I didn't develop it yet due to time pressure.
- I didn't add PHP Unit test. But I should definitely add it in the future.
 

## Requirements

- PHP 7.0 and above.
- to create a db and fill up the credentials constants which are in src/Settings.php file.

## Conclusion
In a nutshell, I developed  readable, expandable test project. I didn't be satisfied  this project. As I mentioned above,
I focused on code quality rather then adding functionalities within short period of time. 



# Developer

Burhan TANIS

_Full-Stack Web Developer_

**Portfolio and digital resume:** [https://portfolio.burhantanis.com](https://portfolio.burhantanis.com)
 
**E-mail:** burhanmt@outlook.com



