<?php namespace GuestBook\UseCase;

use GuestBook\Repository\EntryRepository;
use GuestBook\Request\CreateEntryRequest;
use GuestBook\Response\CreateEntryResponse;

class CreateEntryUseCase {
    private $entryRepository;
    public function __construct(EntryRepository $entryRepository){
        $this->entryRepository = $entryRepository;
    }
    public function process(CreateEntryRequest $request,CreateEntryResponse $response){

        $entryId = $this->entryRepository->getUniqueId();
        $entry = $this->entryRepository->create(
            $entryId,
            $request->getAuthorName(),
            $request->getAuthorEmail(),
            $request->getContent()
        );
        $this->entryRepository->add($entry);
    }
} 