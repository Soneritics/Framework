# Soneritics Framework - Version 1.0 #

[![Build Status](https://api.travis-ci.org/Soneritics/Framework.svg?branch=master)](https://travis-ci.org/Soneritics/Framework)
![License](http://img.shields.io/badge/license-MIT-green.svg)

by
* [@Soneritics](https://github.com/Soneritics) - Jordi Jolink


## Introduction ##
A brief summarization of what this framework does:

> The Soneritics framework is a framework that is object oriented and provides some useful functionalities for
> creating websites and web applications very easy.

## Minimum Requirements ##

- PHP 5.5+
- PDO driver for your respective database (atm only MySQL is supported)

## Supported Databases ##

- MySQL

## Features ##

- MVC structure
- Routing
- Database finder
- REST
- And more

### Installation ###

Setup is very easy and straight-forward. You can choose on of the following options:

1. Upload the framework to your include path.
2. Upload the framework to a private directory, on level higher than your website/web app's folder.


Then start the framework by including the bootstrap:

```php
require_once('Framework/Bootstrap.php');
new Framework\Bootstrap();
```

### Database querying ###
Database querying is very easy. A few examples can be found in the code below.

```php
// Define the tables we have as Table extends
class Father extends Table {}
class Mother extends Table {}
class Child extends Table {}

// Use the Child table as a base for the queries
$child = new Child;

// Select everything from the children table
$child
    ->select()
    ->execute();

// Join a child with it's parents
$child
    ->select()
    ->leftJoin(new Father, 'Father.id = father_id')
    ->leftJoin(new Mother, 'Mother.id = mother_id')
    ->execute();

// A new child has been born!
$child
    ->insert()
    ->values(array(
        'firstname' => 'first name',
        'lastname' => 'last name',
        'father_id' => 1,
        'mother_id' => 1
    ))
    ->execute();

// Typo in the baby's name :-)
$child
    ->update()
    ->set('firstname', 'new first name')
    ->where(array(
        'firstname' => 'first name',
        'lastname' => 'last name'
    ))
    ->execute();

// Typo in the first and lastname of the baby :O
$child
    ->update()
    ->set(array('firstname' => 'new first name', 'lastname' => 'new last name'))
    ->where(array(
        'firstname' => 'first name',
        'lastname' => 'last name'
    ))
    ->execute();

// Selecting with some sorting and limiting
$child
    ->select()
    ->leftJoin(new Father, 'Father.id = father_id')
    ->leftJoin(new Mother, 'Mother.id = mother_id')
    ->orderAsc('lastname')
    ->orderAsc('firstname')
    ->limit(25)
    ->execute();
```

### User input ###
Handling input from a user can be done using the FormHelper. In your views, the FormHelper is accessible through the $form property.

The following example shows how a user can be added to the database.

**View file**
```php
    // Render the form's start tag <form>
    $this->form
        ->start()
        ->setMethod('post')
        ->setAction('/add-user')
        ->setParam('id', 'loginForm')
        ->setClass('form-horizontal')
        ->render();

    /* Render the inputs for the user's email and name.
     * When the user submits a form that is invalid, the
     * object automatically assigns an error class.
     * By using the setValueFromPost() function, the 
     * previously entered value gets set.
     */
    $this->form
        ->text('User.email')
        ->setParam('id', 'email')
        ->setParam('placeholder', 'Email address here')
        ->setClass('form-control')
        ->setValueFromPost()
        ->render();

    $this->form
        ->text('User.name')
        ->setParam('placeholder', 'Your name')
        ->setClass('form-control')
        ->setValueFromPost()
        ->render();

    $this->form->submit()->render();

    // Form's end tag
    $this->form->end()->render();
```

**Controller**
```php
class User extends Controller
{
    public function addAction()
    {
        return new View('User/add');
    }

    public function addPost()
    {
        // Create the model, based on the Table object
        $userModel = new Models\User;

        // Validate data
        if (!$userModel->validates($this->fromPost('User'))) {
            return $this->addAction();
        }

        // Insert data and get the last insert id
        $userId = $userModel
            ->insert()
            ->set($this->fromPost('User'))
            ->execute();

        /* The user is now added to the database.
         * The following code can fetch the user data:
         * $userData = $userModel->select()->where(['id' => $userId])->execute()->get();
         *
         * This will return the following array:
         * [
         *     'User' => [
         *         'id' => 1,
         *         'email' => 'User email address',
         *         'name' => 'User name'
         *     ]
         * ]
         */

        // Done, redirect to the homepage
        new Redirect('/');
    }
}
```