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
        $request = new FakeViewEntriesRequest(5);
        $response = $this->processUseCase($request, $entries);
        $this->assertEmpty($response->entries);
    }

    public function testCanSeeEntries()
    {
        $entries = [
            new EntryEntity('testAuthor', 'test text')
        ];
        $request = new FakeViewEntriesRequest(5);
        $response = $this->processUseCase($request, $entries);
        $this->assertNotEmpty($response->entries);
    }

    public function testCanSeeFiveEntries()
    {
        $entities = [];
        for ($i = 0; $i < 10; $i++) {
            $entities[] = new EntryEntity('Author ' . $i, 'Text ' . $i);
        }
        $request = new FakeViewEntriesRequest(5);
        $response = $this->processUseCase($request, $entities);
        $this->assertNotEmpty($response->entries);
        $this->assertSame(5, count($response->entries));
    }

    public function testCanSeeFiveEntriesOnSecondPage()
    {
        $entities = [];
        $expectedEntries = [];
        $entryViewFactory = new FakeEntryViewFactory();
        for ($i = 0; $i < 10; $i++) {
            $entryEntity = new EntryEntity('Author ' . $i, 'Text ' . $i);
            if ($i >= 5) {
                $expectedEntries[] = $entryViewFactory->create($entryEntity);
            }
            $entities[] = $entryEntity;
        }
        $request = new FakeViewEntriesRequest(5);

        $response = $this->processUseCase($request, $entities);
        $this->assertNotEmpty($response->entries);
        $this->assertSame(5, count($response->entries));
        $this->assertEquals($expectedEntries, $response->entries);
    }

    /**
     * @param $request
     * @param $entries
     * @return FakeViewEntriesResponse
     */
    private function processUseCase($request, $entries)
    {
        $repository = new FakeEntryRepository($entries);
        $factory = new FakeEntryViewFactory();
        $response = new FakeViewEntriesResponse();
        $useCase = new ViewEntriesUseCase($repository, $factory);
        $useCase->process($request, $response);
        return $response;
    }
}