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
        $response = $this->processUseCase($entries);
        $this->assertEmpty($response->entries);
    }

    public function testCanSeeEntries()
    {
        $entries = [
            new EntryEntity('testAuthor', 'test text')
        ];
        $response = $this->processUseCase($entries);
        $this->assertNotEmpty($response->entries);
    }

    public function testCanSeeFiveEntries()
    {
        $entities = [];
        for ($i = 0; $i < 10; $i++) {
            $entities[] = new EntryEntity('Author '.$i,'Text '.$i);
        }

        $response = $this->processUseCase($entities);
        $this->assertNotEmpty($response->entries);
        $this->assertSame(5, count($response->entries));
    }

    /**
     * @param $entries
     * @return FakeViewEntriesResponse
     */
    private function processUseCase($entries)
    {
        $repository = new FakeEntryRepository($entries);
        $factory = new FakeEntryViewFactory();
        $request = new FakeViewEntriesRequest(5);
        $response = new FakeViewEntriesResponse();
        $useCase = new ViewEntriesUseCase($repository, $factory);
        $useCase->process($request, $response);
        return $response;
    }
}