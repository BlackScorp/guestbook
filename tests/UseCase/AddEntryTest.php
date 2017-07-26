<?php
use BlackScorp\GuestBook\Fake\MessageStream\FakeAddEntryMessageStream;
use BlackScorp\GuestBook\Fake\Validator\FakeAddEntryValidator;
use BlackScorp\GuestBook\UseCase\AddEntryUseCase;

class AddEntryTest extends \PHPUnit\Framework\TestCase
{
    public function testEntrySaved()
    {
        $messageStream = new FakeAddEntryMessageStream('Test Author', 'Test entry text');
        $validator = new FakeAddEntryValidator();
        $useCase = new AddEntryUseCase($validator);
        $useCase->process($messageStream);

        $this->assertNotEmpty($messageStream->author);
        $this->assertNotEmpty($messageStream->text);
        $this->assertEmpty($messageStream->errors);
    }

    public function testAuthorIsEmptyMessage()
    {
        $messageStream = new FakeAddEntryMessageStream('', 'Test entry text');
        $validator = new FakeAddEntryValidator();
        $useCase = new AddEntryUseCase($validator);
        $useCase->process($messageStream);

        $this->assertTrue($messageStream->visibleAuthorIsEmptyMessage);
        $this->assertNotEmpty($messageStream->errors);
    }
}