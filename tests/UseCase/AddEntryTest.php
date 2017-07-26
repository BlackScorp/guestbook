<?php
use BlackScorp\GuestBook\Fake\MessageStream\FakeAddEntryMessageStream;
use BlackScorp\GuestBook\UseCase\AddEntryUseCase;

class AddEntryTest extends \PHPUnit\Framework\TestCase
{
    public function testEntrySaved()
    {
        $messageStream = new FakeAddEntryMessageStream('Test Author','Test entry text');
        $useCase = new AddEntryUseCase();
        $useCase->process($messageStream);

        $this->assertNotEmpty($messageStream->author);
        $this->assertNotEmpty($messageStream->text);
        $this->assertEmpty($messageStream->errors);
    }
}