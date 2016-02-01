<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use BlackScorp\GuestBook\Entity\EntryEntity;
use BlackScorp\GuestBook\Fake\Repository\FakeEntryRepository;
use BlackScorp\GuestBook\Fake\ViewFactory\FakeEntryViewFactory;
use BlackScorp\GuestBook\Fake\Request\FakeViewEntriesRequest;
use BlackScorp\GuestBook\Fake\Response\FakeViewEntriesResponse;
use BlackScorp\GuestBook\UseCase\ViewEntriesUseCase;

class ListEntriesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FakeEntryViewFactory
     */
    private $entryViewFactory;

    public function setUp()
    {
        $this->entryViewFactory = new FakeEntryViewFactory();
    }

    public function testCanSeeEntries()
    {
        $entities = [];
        $entities[] = new EntryEntity('test author', 'test entry');
        $request = new FakeViewEntriesRequest(5);
        $response = $this->processUseCase($request, $entities);
        $this->assertNotEmpty($response->entries);
    }

    public function testEntriesNotExists()
    {
        $entities = [];
        $request = new FakeViewEntriesRequest(5);
        $response = $this->processUseCase($request, $entities);
        $this->assertEmpty($response->entries);
    }

    public function testCanSeeFiveEntries()
    {
        $entities = [];
        for ($i = 0; $i < 10; $i++) {
            $entities[] = new EntryEntity('test author ' . $i, 'test entry ' . $i);
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

        for ($i = 0; $i < 10; $i++) {
            $entryEntity = new EntryEntity('test author ' . $i, 'test entry ' . $i);

            if ($i >= 5) {
                $expectedEntries[] = $this->entryViewFactory->create($entryEntity);
            }
            $entities[] = $entryEntity;
        }
        $request = new FakeViewEntriesRequest(5);
        $request->setPage(2);
        $response = $this->processUseCase($request, $entities);
        $this->assertNotEmpty($response->entries);
        $this->assertSame(5, count($response->entries));
        $this->assertEquals($expectedEntries, $response->entries);
    }

    /**
     * @param $entities
     * @param $request
     * @return FakeViewEntriesResponse
     */
    private function processUseCase($request, $entities)
    {
        $entryRepository = new FakeEntryRepository($entities);
        $response = new FakeViewEntriesResponse();
        $useCase = new ViewEntriesUseCase($entryRepository, $this->entryViewFactory);
        $useCase->process($request, $response);
        return $response;
    }
}