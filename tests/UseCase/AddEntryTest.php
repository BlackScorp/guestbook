<?php
use BlackScorp\GuestBook\Fake\Factory\FakeEntryEntityFactry;
use BlackScorp\GuestBook\Fake\MessageStream\FakeAddEntryMessageStream;
use BlackScorp\GuestBook\Fake\Repository\FakeEntryRepository;
use BlackScorp\GuestBook\Fake\Validator\FakeAddEntryValidator;
use BlackScorp\GuestBook\UseCase\AddEntryUseCase;

class AddEntryTest extends \PHPUnit\Framework\TestCase
{
    public function testEntrySaved()
    {
        $messageStream = new FakeAddEntryMessageStream('Test Author', 'Test entry text');
        $addEntryValidator = new FakeAddEntryValidator();
        $entryFactory = new FakeEntryEntityFactry();
        $entryRepository = new FakeEntryRepository();
        $useCase = new AddEntryUseCase($addEntryValidator, $entryFactory, $entryRepository);
        $useCase->process($messageStream);

        $this->assertNotEmpty($messageStream->author);
        $this->assertNotEmpty($messageStream->text);

    }

    public function testAuthorIsEmptyMessage()
    {
        $messageStream = new FakeAddEntryMessageStream('', 'Test entry text');
        $addEntryValidator = new FakeAddEntryValidator();
        $entryFactory = new FakeEntryEntityFactry();
        $entryRepository = new FakeEntryRepository();
        $useCase = new AddEntryUseCase($addEntryValidator, $entryFactory, $entryRepository);
        $useCase->process($messageStream);

        $this->assertTrue($messageStream->visibleAuthorIsEmptyMessage);

    }
}