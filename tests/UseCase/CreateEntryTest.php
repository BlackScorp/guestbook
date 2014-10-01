<?php namespace GuestBook\Test\UseCase;


use GuestBook\Mock\Repository\MockEntryRepository;
use GuestBook\Mock\Response\MockCreateEntryResponse;
use GuestBook\Request\CreateEntryRequest;
use GuestBook\UseCase\CreateEntryUseCase;

class CreateEntryTest extends \PHPUnit_Framework_TestCase{
    private $entryRepository;

    public function setUp(){
         $this->entryRepository = new MockEntryRepository();
    }

    public function testCanCreateEntry(){
        $request = new CreateEntryRequest('Test','Test@foo.com','Hello World');
        $response = new MockCreateEntryResponse();
        $useCase = new CreateEntryUseCase($this->entryRepository);
        $useCase->process($request,$response);
        $this->assertFalse($response->failed());
    }
} 