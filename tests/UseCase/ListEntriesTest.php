<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use BlackScorp\GuestBook\Fake\Request\FakeViewEntriesRequest;
use BlackScorp\GuestBook\Fake\Response\FakeViewEntriesResponse;
use BlackScorp\GuestBook\UseCase\ViewEntriesUseCase;

class ListEntriesTest extends PHPUnit_Framework_TestCase
{
    public function testEntriesNotExists()
    {
        $request = new FakeViewEntriesRequest();
        $response = new FakeViewEntriesResponse();
        $useCase = new ViewEntriesUseCase();
        $useCase->process($request, $response);
        $this->assertEmpty($response->entries);
    }

    public function testCanSeeEntries()
    {
        $request = new FakeViewEntriesRequest();
        $response = new FakeViewEntriesResponse();
        $useCase = new ViewEntriesUseCase();
        $useCase->process($request, $response);
        $this->assertNotEmpty($response->entries);
    }
}