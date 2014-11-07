<?php
namespace GuestBook\Test\UseCase;


use GuestBook\Mock\Repository\MockEntryRepository;
use GuestBook\Mock\Request\MockListEntriesRequest;
use GuestBook\Mock\Response\MockListEntriesResponse;
use GuestBook\UseCase\ListEntriesUseCase;

class ListEntriesTest extends \PHPUnit_Framework_TestCase {
    private $entryRepository;
    public  function setUp(){
        $this->entryRepository = new MockEntryRepository();
    }
    public function testEmptyList(){
        $request = new MockListEntriesRequest();
        $response = new MockListEntriesResponse();
        $useCase = new ListEntriesUseCase($this->entryRepository);
        $useCase->process($request,$response);
        $this->assertEmpty($response->entries);
    }
}
 