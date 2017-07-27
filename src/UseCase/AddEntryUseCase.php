<?php
namespace BlackScorp\GuestBook\UseCase;


use BlackScorp\GuestBook\Factory\EntryEntityFactory;
use BlackScorp\GuestBook\MessageStream\AddEntryMessageStream;
use BlackScorp\GuestBook\Repository\EntryRepository;
use BlackScorp\GuestBook\Validator\AddEntryValidator;

final class AddEntryUseCase
{
    /**
     * @var AddEntryValidator
     */
    private $addEntryValidator;
    /**
     * @var EntryEntityFactory
     */
    private $entryEntityFactory;
    /**
     * @var EntryRepository
     */
    private $entryRepository;

    /**
     * AddEntryUseCase constructor.
     * @param AddEntryValidator $addEntryValidator
     * @param EntryEntityFactory $entryEntityFactory
     * @param EntryRepository $entryRepository
     */
    public function __construct(AddEntryValidator $addEntryValidator, EntryEntityFactory $entryEntityFactory, EntryRepository $entryRepository)
    {
        $this->addEntryValidator = $addEntryValidator;
        $this->entryEntityFactory = $entryEntityFactory;
        $this->entryRepository = $entryRepository;
    }


    public function process(AddEntryMessageStream $messageStream)
    {
        $this->addEntryValidator->setMessageStream($messageStream);

        if (!$this->addEntryValidator->isValid()) {
            return;
        }
        $entry = $this->entryEntityFactory->create($messageStream->getAuthor(),$messageStream->getText());
        if(!$entry){
            return;
        }
        $this->entryRepository->add($entry);
    }
}