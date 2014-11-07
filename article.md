#once upon a time...

there was a developer, he had to realize a nice clean greenfield project with any framework he like.

He had no idea which PHP Frameworks were outside there and after a small research he decide to use Kohana 3.2 because at this time this was the only one framework which the developer could understand and use.

after 9 months of developing the project was born and launched. the developer realized that a new version of the Kohana framework was released, it was a minor release.

one small simple update to Kohana 3.3 and the entire project was broken, he reverted the framework update.

after doing view more researches the developer realized that this problems have every framework, there are tons of blog posts, articles etc outside on the internet about upgraiding current code base of framework x to newer version(eg Zend 1 to Zend 2, Symfony 1 to Symfony 2 and so on)

Conclusion of this, frameworks are nice and you can speed up initial development, but on the other hand you either stuck to a version or to the framework and have to maintain this project while other frameworks offer much cooler features in the meantime.

Also many companies using their custom in house created framework(for what ever reason)

#Clean Code Architecture

How can you develop a software which is independant of the framework and at same time using features from a framework?

[Robert C Marin](http://en.wikipedia.org/wiki/Robert_Cecil_Martin) presented a solution "Clean Code Architecture"
[![Clean Code Architecture](http://blog.8thlight.com/uncle-bob/images/2012-08-13-the-clean-architecture/CleanArchitecture.jpg)](http://blog.8thlight.com/uncle-bob/2012/08/13/the-clean-architecture.html)

The goals and idea behind it looks very nice but hard to understand in the first time there are many interpretations about it on the internet. I tryed to realize it in the easist way i could understand.

#Design Pattern
First of all forget about all design pattern, the design pattern are realized by the framework, only view of them are used in the clean code architecture.

In all my projects i realized with the clean code architecture i used mostly [Command Design Pattern](http://en.wikipedia.org/wiki/Command_pattern) and [Repository Design Pattern](http://www.sitepoint.com/repository-design-pattern-demystified/)

##Repository Design Pattern

a small note, many developers argued against it, because they never had to switch the database and the realization of this pattern would just take more time.

yes they are right, it is rarely that you have to change the database, however, a Repository is a container of data(or Datasource), which means, it could be also the Filesystem, an API even LDAP is a repository. While i had not to switch the Databases in my Projects still i had to add additional resources for data(Login via LDAP and/or Facebook API), display Profilepictures from Filesystem or gravatar API and so on.

Just want to say, Database is NOT the one and only datasource for an application

#PHP

So how to realized Clean Code Architecture in PHP? There are view small Rules.

1. Every action on your website is realized by a Command Pattern(even simple CRUD action, you never know if you have to add additional logic later)
2. Every action has its own Request and Response class
3. All dependencies are injected and contains an interface, dont inject concrete classes


Personally i changed some definitions in my code to make it more understandable for developers.

#Example

Lets start with a small example. Lets say we have to create a guestbook we would have 2 Features

1. Create new entry
2. List entries

Lets begin with the feature Create new Entry. Our Test Method:

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

if we run the tests it will fail of course, non of the classes exists, so lets create them.
first we create the use case 

```php
<?php
//src/UseCase/CreateEntryUseCase.php
namespace GuestBook\UseCase;
class CreateEntryUseCase{
	public function process($request,$response){
	}
}
```
then we create the request/response interfaces

```php
<?php
//src/Request/CreateEntryRequest.php
namespace GuestBook\Request;
interface CreateEntryRequest{}
//src/Response/CreateEntryResponse.php
namespace GuestBook\Response;
interface CreateEntryResponse{}
```

next we create interfaces for our dependencies, since we need to store data , we require an EntryRepository also we want to store valid data only so we need a CreateEntryValidator

```php
<?php
//src/Repository/EntryRepository.php
namespace GuestBook\Repository;
interface EntryRepository{}
```

the validator we are going to create later, now lets add typehints to our usecase and shape our logic code

```php
namespace GuestBook\UseCase;
//src/UseCase/CreateEntryUseCase.php
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

so the process method is the place with our magic happends, first we copy the request data to the response data, its usefull to display old input entries in the form.
next we set the request data to our validator,the createEntryValidator is just a data structure, we dont need private methods at this place.

then we just check, if the validator is not valid, set the errors from validator to the response and stop execute, i rather perfer to negate the logic and stop the execution of the code than an if / else statement.

if the input is valid we going to create the entry object with the help of our repository. note that the auto_increment is a mysql feature, since the repository is not a specific datasource we have to assume that auto_increment not exists.

lets say we would store our entries in MongoDB, so the getUniqueId method would return a new MongoID.

after creating our entity we put them into our repository

thats the complete logic, validate input -> create object based on input -> add the object to the repository

now we know what we expect from our classes so we can add the methods to our interfaces, i also added an errorinterface which helps me to handle error messages

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
//src/Request/CreateEntryRequest.php
namespace GuestBook\Request;
interface CreateEntryRequest{
    public function getAuthorEmail();
    public function getAuthorName();
    public function getContent();
}
//src/Response/CreateEntryResponse.php
namespace GuestBook\Response;
use GuestBook\Request\CreateEntryRequest;
interface CreateEntryResponse implements ErrorInterface{
public function setRequestData(CreateEntryRequest $request);
}
//src/Repository/EntryRepository.php
namespace GuestBook\Repository;
interface EntryRepository{
    public function getUniqueId();
    public function create($entryId,$authorName,$authorEmail,$content);
    public function add(EntryEntity $entity);
}
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