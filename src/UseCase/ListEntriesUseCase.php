<?php namespace GuestBook\UseCase;

use GuestBook\Repository\EntryRepository;
use GuestBook\Request\ListEntriesRequest;
use GuestBook\Response\ListEntriesResponse;
use GuestBook\View\EntryView;

class ListEntriesUseCase {
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    public function __construct(EntryRepository $entryRepository){
        $this->entryRepository = $entryRepository;
    }
    public function process(ListEntriesRequest $request,ListEntriesResponse $response){

        $entries = $this->entryRepository->findAll();

        foreach($entries as $entry){
            $entryView = new EntryView();
            $entryView->authorName = $entry->getAuthorName();
            $entryView->authorEmail = $entry->getAuthorEmail();
            $entryView->content = $entry->getContent();
            $response->addEntry($entryView);
        }
    }
} 