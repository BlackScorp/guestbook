<?php

use BlackScorp\GuestBook\Entity\EntryEntity;
use BlackScorp\GuestBook\Fake\Repository\FakeEntryRepository;
use BlackScorp\GuestBook\Fake\MessageStream\FakeViewEntriesMessageStream;
use BlackScorp\GuestBook\Fake\Response\FakeViewEntriesResponse;
use BlackScorp\GuestBook\Fake\ViewFactory\FakeEntryViewFactory;
use BlackScorp\GuestBook\MessageStream\ViewEntriesMessageStream;
use BlackScorp\GuestBook\UseCase\ViewEntriesUseCase;

class ListEntriesTest extends \PHPUnit\Framework\TestCase
{
    public function testEntriesNotExists()
    {
        $entries = [];
        $request = new FakeViewEntriesMessageStream(5);
        $response = $this->processUseCase($request, $entries);
        $this->assertEmpty($response->entries);
    }

    public function testCanSeeEntries()
    {
        $entries = [
            new EntryEntity('testAuthor', 'test text')
        ];
        $request = new FakeViewEntriesMessageStream(5);
        $response = $this->processUseCase($request, $entries);
        $this->assertNotEmpty($response->entries);
    }

    public function testCanSeeFiveEntries()
    {
        $entities = [];
        for ($i = 0; $i < 10; $i++) {
            $entities[] = new EntryEntity('Author ' . $i, 'Text ' . $i);
        }
        $request = new FakeViewEntriesMessageStream(5);
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
        $request = new FakeViewEntriesMessageStream(5);
        $request->setPage(2);
        $response = $this->processUseCase($request, $entities);
        $this->assertNotEmpty($response->entries);
        $this->assertSame(5, count($response->entries));
        $this->assertEquals($expectedEntries, $response->entries);
    }

    /**
     * @param $messageStream
     * @param $entries
     * @return FakeViewEntriesMessageStream
     */
    private function processUseCase($messageStream, $entries)
    {
        $repository = new FakeEntryRepository($entries);
        $factory = new FakeEntryViewFactory();

        $useCase = new ViewEntriesUseCase($repository, $factory);
        $useCase->process($messageStream);
        return $messageStream;
    }
}