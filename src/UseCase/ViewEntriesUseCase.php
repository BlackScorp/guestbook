<?php
namespace BlackScorp\GuestBook\UseCase;

use BlackScorp\GuestBook\MessageStream\ViewEntriesMessageStream;
use BlackScorp\GuestBook\Repository\EntryRepository;
use BlackScorp\GuestBook\Factory\EntryViewFactory;

final class ViewEntriesUseCase
{
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var EntryViewFactory
     */
    private $entryViewFactory;

    /**
     * ViewEntriesUseCase constructor.
     * @param EntryRepository $entryRepository
     * @param EntryViewFactory $entryViewFactory
     */
    public function __construct(EntryRepository $entryRepository, EntryViewFactory $entryViewFactory)
    {
        $this->entryRepository = $entryRepository;
        $this->entryViewFactory = $entryViewFactory;
    }


    public function process(ViewEntriesMessageStream $messageStream)
    {
        $entries = $this->entryRepository->findAllPaginated($messageStream->getOffset(), $messageStream->getLimit());
        if (!$entries) {
            return;
        }

        foreach ($entries as $entry) {
            $entryView = $this->entryViewFactory->create($entry);
            $messageStream->addEntry($entryView);
        }
    }
}