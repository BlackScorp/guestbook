<?php namespace GuestBook\Test\UseCase;

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