<?php namespace GuestBook\Test\UseCase;


use GuestBook\Mock\Repository\MockEntryRepository;
use GuestBook\Mock\Response\MockCreateEntryResponse;
use GuestBook\Mock\Validator\MockCreateEntryValidator;
use GuestBook\Request\CreateEntryRequest;
use GuestBook\UseCase\CreateEntryUseCase;

class CreateEntryTest extends \PHPUnit_Framework_TestCase{
    private $entryRepository;
    private $createEntryValidator;
    public function setUp(){
         $this->entryRepository = new MockEntryRepository();
        $this->createEntryValidator = new MockCreateEntryValidator();
    }

    public function testCanCreateEntry(){
        $request = new CreateEntryRequest('Test','Test@foo.com','Hello World');
        $response = new MockCreateEntryResponse();
        $useCase = new CreateEntryUseCase($this->entryRepository,$this->createEntryValidator);
        $useCase->process($request,$response);
        $this->assertFalse($response->failed());
    }
    public function failedRequests(){
        return array(
            array('','','',array('Authors Name is empty','Authors E-Mail is empty','Content is empty'))
        );
    }

    /**
     * @param $authorName
     * @param $authorEmail
     * @param $content
     * @param $expectedErrors
     * @dataProvider failedRequests
     */
    public function testFailCreateEntry($authorName,$authorEmail,$content,$expectedErrors){
        $request = new CreateEntryRequest($authorName,$authorEmail,$content);
        $response = new MockCreateEntryResponse();
        $useCase = new CreateEntryUseCase($this->entryRepository,$this->createEntryValidator);
        $useCase->process($request,$response);
        $this->assertTrue($response->failed());
        $this->assertEquals($expectedErrors,$response->errors());
    }
} 