<?php
namespace GuestBook\Test\UseCase;

use GuestBook\Mock\Repository\MockEntryRepository;
use GuestBook\Mock\Request\MockListEntriesRequest;
use GuestBook\Mock\Response\MockListEntriesResponse;
use GuestBook\UseCase\ListEntriesUseCase;

class ListEntriesTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var MockEntryRepository
     */
    private $entryRepository;
    public  function setUp(){
        $this->entryRepository = new MockEntryRepository();
    }
    private function executeUseCase(){
        $request = new MockListEntriesRequest();
        $response = new MockListEntriesResponse();
        $useCase = new ListEntriesUseCase($this->entryRepository);
        $useCase->process($request,$response);
        return $response;
    }
    private function addDummyEntry(){
        $id = $this->entryRepository->getUniqueId();
        $entry = $this->entryRepository->create($id,'Test','test@foo.com','this is a content with id '.$id);
        $this->entryRepository->add($entry);
    }
    public function testEmptyList(){
        $response = $this->executeUseCase();
        $this->assertEmpty($response->entries);
    }
    public function testNotEmptyList(){
        $this->addDummyEntry();
        $response = $this->executeUseCase();
        $this->assertNotEmpty($response->entries);
    }
    public function testMoreThanOneEntries(){
        $this->addDummyEntry();
        $this->addDummyEntry();
        $response = $this->executeUseCase();
        $this->assertNotEmpty($response->entries);
        $this->assertGreaterThan(1,count($response->entries));
    }
}
 