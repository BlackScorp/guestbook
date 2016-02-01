<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use BlackScorp\GuestBook\Entity\EntryEntity;
use BlackScorp\GuestBook\Fake\Repository\FakeEntryRepository;
use BlackScorp\GuestBook\Fake\Request\FakeViewEntriesRequest;
use BlackScorp\GuestBook\Fake\Response\FakeViewEntriesResponse;
use BlackScorp\GuestBook\Fake\ViewFactory\FakeEntryViewFactory;
use BlackScorp\GuestBook\UseCase\ViewEntriesUseCase;

class ListEntriesTest extends PHPUnit_Framework_TestCase
{
    public function testEntriesNotExists()
    {
        $entries = [];
        $repository = new FakeEntryRepository($entries);
        $factory = new FakeEntryViewFactory();
        $request = new FakeViewEntriesRequest();
        $response = new FakeViewEntriesResponse();
        $useCase = new ViewEntriesUseCase($repository,$factory);
        $useCase->process($request, $response);
        $this->assertEmpty($response->entries);
    }

    public function testCanSeeEntries()
    {
        $entries = [
            new EntryEntity('testAuthor','test text')
        ];
        $repository = new FakeEntryRepository($entries);
        $factory = new FakeEntryViewFactory();
        $request = new FakeViewEntriesRequest();
        $response = new FakeViewEntriesResponse();
        $useCase = new ViewEntriesUseCase($repository,$factory);
        $useCase->process($request, $response);
        $this->assertNotEmpty($response->entries);
    }
}