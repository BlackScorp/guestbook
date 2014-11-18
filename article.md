#Clean Code Architecture

How can you develop software which is independent of the framework and at the same time uses features from that framework?

[Robert C Marin](http://en.wikipedia.org/wiki/Robert_Cecil_Martin) presented a solution - "Clean Code Architecture":
[![Clean Code Architecture](http://blog.8thlight.com/uncle-bob/images/2012-08-13-the-clean-architecture/CleanArchitecture.jpg)](http://blog.8thlight.com/uncle-bob/2012/08/13/the-clean-architecture.html)

The goals and ideas behind it look very nice but it's a bit hard to understand at first look, and there are many interpretations around the internet. In my opinion, this picture just explains the general concept but not the details - which I believe are better explained with following picture:

![Clean Code Architecture](https://raw.githubusercontent.com/BlackScorp/guestbook/master/cleancode.png)

I have tried to formulate it as I have understood it in the simplest way.

#Design Pattern
First of all, forget all about the design pattern - the design pattern is realized by the framework - only few of them are used in the clean code architecture.

In all of my projects created with the clean code architecture, I mostly used [Command Design Pattern](http://en.wikipedia.org/wiki/Command_pattern) and [Repository Design Pattern](http://www.sitepoint.com/repository-design-pattern-demystified/).

##Repository Design Pattern

A small note: many developers have argued against it because they've never had to switch databases and the implementation of this pattern would just take more time.

Yes, they are right in a way - it is rare that you have to change the database; however, a repository is a container of data (or data source), which means that it could also be the file system, an API... even LDAP is a repository. While I didn't have to switch the databases in my projects, I still had to add additional resources for the data (login via LDAP and/or Facebook API), display profile pictures from file system or Gravatar API and so on.

I'd also like to add - the database is NOT the one and only DataSource for an application.

#PHP

So, how do you realize Clean Code Architecture in PHP? There are a few small rules.

1. Every action on your website is realized by a Command Pattern (even simple CRUD actions -  you never know if you will need to add additional logic later)
2. Every action has its own Request and Response interface and of course the concrete implementations
3. All dependencies are injected and contain an interface - don't inject concrete classes


Personally, I've changed some definitions in my code to make it more understandable for developers.

#Example

Let's start with a small example. Say we have to create a guestbook - we would have two features:

1. Create new entry
2. List entries

Let's begin with the feature 'Create new entry'. Our test method is as follows:

```php
<?php
//tests/UseCase/CreateEntryTest.php
namespace GuestBook\Test\UseCase;
class CreateEntryTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateEntry()
    {
		$entryRepository = new MockEntryRepository();
		$createEntryValidator = new MockCreateEntryValidator();
        $request  = new MockCreateEntryRequest('Test', 'Test@foo.com', 'Hello World');
        $response = new MockCreateEntryResponse();
        $useCase  = new CreateEntryUseCase($entryRepository, $createEntryValidator);
        $useCase->process($request, $response);
        $this->assertFalse($response->hasErrors());
    }
}
```

If we run the test - it will fail, of course. None of the classes exist, so let's create them.

First, we create the UseCase.

```php
<?php
//src/UseCase/CreateEntryUseCase.php
namespace GuestBook\UseCase;
class CreateEntryUseCase{
	public function process($request,$response){
	}
}
```
Then, we create the Request interface

```php
<?php
//src/Request/CreateEntryRequest.php
namespace GuestBook\Request;
interface CreateEntryRequest{}
```
and the Response interface
```php
//src/Response/CreateEntryResponse.php
namespace GuestBook\Response;
interface CreateEntryResponse{}
```

Next, we'll create interfaces for our dependencies - since we need to store data, we'll need an EntryRepository. Also, we want to store only valid data, so we need a CreateEntryValidator.

```php
<?php
//src/Repository/EntryRepository.php
namespace GuestBook\Repository;
interface EntryRepository{}
```

We'll create the validator later. For now, let's add type hints to our UseCase and shape our logic code.

```php
//src/UseCase/CreateEntryUseCase.php
namespace GuestBook\UseCase;
use GuestBook\Repository\EntryRepository;
use GuestBook\Request\CreateEntryRequest;
use GuestBook\Response\CreateEntryResponse;
use GuestBook\Validator\CreateEntryValidator;
class CreateEntryUseCase {
    private $entryRepository;
    private $createEntryValidator;
    public function __construct(EntryRepository $entryRepository,CreateEntryValidator $createEntryValidator){
        $this->entryRepository = $entryRepository;
        $this->createEntryValidator = $createEntryValidator;
    }
    private function applyValidatorData(CreateEntryRequest $request){
        $this->createEntryValidator->authorEmail = $request->getAuthorEmail();
        $this->createEntryValidator->authorName = $request->getAuthorName();
        $this->createEntryValidator->content = $request->getContent();
    }
    public function process(CreateEntryRequest $request,CreateEntryResponse $response){
        $response->setRequestData($request);
        $this->applyValidatorData($request);
        if(!$this->createEntryValidator->isValid()){
            $response->setErrors($this->createEntryValidator->getErrors());
            return;
        }
        $entryId = $this->entryRepository->getUniqueId();
        $entry = $this->entryRepository->create(
            $entryId,
            $request->getAuthorName(),
            $request->getAuthorEmail(),
            $request->getContent()
        );
        $this->entryRepository->add($entry);
    }
} 
```

So the process method is where the magic happens. First, we copy the request data to the response data - it’s useful to display old input entries in the form.
Next, we set the request data to our validator; the createEntryValidator is just a data structure, we don’t need private methods here.

Then we just check - if the validator is not valid, we set the errors from validator to the response and stop execution. I prefer to negate the logic and stop the execution of the code to using an if/else statement.

If the input is valid, we'll create the entry object with the help of our repository. Note that the auto_increment is a MySQL feature. Since the repository is not a specific datasource, we have to assume that auto_increment does not exist.

Let's say we want to store our entries in MongoDB so that the getUniqueId method would return a new MongoID.

After creating our entities, we put them into our repository

That's the complete logic: validate input -> create an object based on input -> add the object to the repository.

Now we know what we expect from our classes, so we can add the methods to our interfaces. I also added an ErrorInterface which helps me handle error messages.


```php
<?php
//src/ErrorInterface.php
namespace GuestBook;
interface ErrorInterface {
    public function hasErrors();
    public function setErrors(array $errors);
    public function getErrors();
    public function appendError($message);
}
```
```php
<?php
//src/Request/CreateEntryRequest.php
namespace GuestBook\Request;
interface CreateEntryRequest{
    public function getAuthorEmail();
    public function getAuthorName();
    public function getContent();
}
```
```php
<?php
//src/Response/CreateEntryResponse.php
namespace GuestBook\Response;
use GuestBook\Request\CreateEntryRequest;
interface CreateEntryResponse implements ErrorInterface{
public function setRequestData(CreateEntryRequest $request);
}
```
```php
<?php
//src/Repository/EntryRepository.php
namespace GuestBook\Repository;
interface EntryRepository{
    public function getUniqueId();
    public function create($entryId,$authorName,$authorEmail,$content);
    public function add(EntryEntity $entity);
}
```
```php
<?php
//src/Validator/Validator.php
namespace GuestBook\Validator;
use GuestBook\ErrorInterface;
abstract class Validator implements ErrorInterface{
    public function isValid(){
        $this->validate();
        return !$this->hasErrors();
    }
    abstract public function validate();
}
```

Interfaces are nice to define what methods we expect, but we need to create classes and fill the methods with logic. So, for test purposes, we will implement mock classes.

```php
//mock/Repository/MockEntryRepository.php
namespace GuestBook\Mock\Repository;
use GuestBook\Entity\EntryEntity;
use GuestBook\Repository\EntryRepository;
class MockEntryRepository implements EntryRepository{
    private $entries = array();
    public function getUniqueId()
    {
        $countEntries = count($this->entries);
        return ++$countEntries;
    }
    public function create($entryId, $authorName, $authorEmail, $content)
    {
        return new EntryEntity($entryId,$authorName,$authorEmail,$content);
    }
    public function add(EntryEntity $entity)
    {
        $this->entries[$entity->getEntryId()] = $entity;
    }
} 
```
```php
<?php
//mock/Request/MockCreateEntryRequest.php
namespace GuestBook\Mock\Request;
use GuestBook\Request\CreateEntryRequest;
class MockCreateEntryRequest implements CreateEntryRequest{
    private $authorName = '';
    private $authorEmail ='';
    private $content = '';
    public function __construct($authorName, $authorEmail, $content)
    {
        $this->authorName  = $authorName;
        $this->authorEmail = $authorEmail;
        $this->content     = $content;
    }
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }
    public function getAuthorName()
    {
        return $this->authorName;
    }
    public function getContent()
    {
        return $this->content;
    }
}
```
```php
<?php
//mock/Response/MockCreateEntryResponse.php
namespace GuestBook\Mock\Response;
use GuestBook\ErrorTrait;
use GuestBook\Request\CreateEntryRequest;
use GuestBook\Response\CreateEntryResponse;
class MockCreateEntryResponse implements CreateEntryResponse{
    use ErrorTrait;
    public $authorName = '';
    public $authorEmail = '';
    public $content = '';
    public function setRequestData(CreateEntryRequest $request)
    {
        $this->authorEmail = $request->getAuthorEmail();
        $this->authorName = $request->getAuthorName();
        $this->content = $request->getContent();
    }
} 
```
```php
<?php
//mock/Validator/MockCreateEntryValidator.php
namespace GuestBook\Mock\Validator;
use GuestBook\ErrorTrait;
use GuestBook\Validator\CreateEntryValidator;
class MockCreateEntryValidator extends CreateEntryValidator{
    use ErrorTrait;
    public function validate()
    {
        if(empty($this->authorName)){
            $this->appendError('Authors Name is empty');
        }
        if(empty($this->authorEmail)){
            $this->appendError('Authors E-Mail is empty');
        }
        if(empty($this->content)){
            $this->appendError('Content is empty');
        }
        if(!empty($this->authorEmail) && !filter_var($this->authorEmail, FILTER_VALIDATE_EMAIL)){
            $this->appendError('Authors E-Mail is invalid');
        }
    }
} 
```

To prevent the copy and pasting of the message handling in my validator and response, we create a trait.

```php
//src/ErrorTrait.php
namespace GuestBook;
trait ErrorTrait{
    private $errors = array();
    public function hasErrors(){
        return count($this->errors) > 0;
    }
    public function setErrors(array $errors){
        $this->errors = $errors;
    }
    public function getErrors(){
        return $this->errors;
    }
    public function appendError($message){
        $this->errors[]=$message;
    }
} 
```

Now let's finish our test and see how our UseCase is working.

```php
//tests/UseCase/CreateEntryTest.php
namespace GuestBook\Test\UseCase;
use GuestBook\Mock\Repository\MockEntryRepository;
use GuestBook\Mock\Request\MockCreateEntryRequest;
use GuestBook\Mock\Response\MockCreateEntryResponse;
use GuestBook\Mock\Validator\MockCreateEntryValidator;
use GuestBook\UseCase\CreateEntryUseCase;
class CreateEntryTest extends \PHPUnit_Framework_TestCase
{
    private $entryRepository;
    private $createEntryValidator;
    public function setUp()
    {
        $this->entryRepository      = new MockEntryRepository();
        $this->createEntryValidator = new MockCreateEntryValidator();
    }
    /**
     * @param $authorName
     * @param $authorEmail
     * @param $content
     *
     * @return MockCreateEntryResponse
     */
    private function executeUseCase($authorName, $authorEmail, $content){
        $request  = new MockCreateEntryRequest($authorName, $authorEmail, $content);
        $response = new MockCreateEntryResponse();
        $useCase  = new CreateEntryUseCase($this->entryRepository, $this->createEntryValidator);
        $useCase->process($request, $response);
        return $response;
    }
    public function failedRequests()
    {
        return array(
            'empty data'=> array('', '', '', array('Authors Name is empty', 'Authors E-Mail is empty', 'Content is empty')),
            'empty name'=> array('', 'test@foo.com', 'this is a test content', array('Authors Name is empty')),
            'empty email'=> array('Test', '', 'this is a test content', array('Authors E-Mail is empty')),
            'empty content'=> array('Test', 'test@foo.com', '', array('Content is empty')),
            'invalid email'=> array('Test', 'test', 'this is a test content', array('Authors E-Mail is invalid')),
        );
    }
    public function testCanCreateEntry()
    {
        $response = $this->executeUseCase('Test', 'Test@foo.com', 'Hello World');
        $this->assertFalse($response->hasErrors());
    }
    /**
     * @param $authorName
     * @param $authorEmail
     * @param $content
     * @param $expectedErrors
     *
     * @dataProvider failedRequests
     */
    public function testFailCreateEntry($authorName, $authorEmail, $content, $expectedErrors)
    {
        $response = $this->executeUseCase($authorName,$authorEmail,$content);
        $this->assertTrue($response->hasErrors());
        $this->assertEquals($expectedErrors, $response->getErrors());
    }
} 
```

We can also do the same thing with our second feature "List Entries".

You can find the code at [github](https://github.com/BlackScorp/guestbook)

##conclusion
You're now able to create testable code that is independent of other frameworks, the web, and the database. You can easily create dummy data and test if the response object contains the proposed values.

You're also able to create concrete classes based on the mocks and use there the framework specific implementations and inject them into the usecases 

Edited by [Seth Pyenson](http://www.linkedin.com/pub/seth-pyenson/9/704/15a)
