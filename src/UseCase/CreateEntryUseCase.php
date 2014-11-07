<?php namespace GuestBook\UseCase;

use GuestBook\Repository\EntryRepository;
use GuestBook\Request\CreateEntryRequest;
use GuestBook\Response\CreateEntryResponse;
use GuestBook\Validator\CreateEntryValidator;

class CreateEntryUseCase {
    private $entryRepository;
    private $createEntryValidator;
    public function __construct(EntryRepository $entryRepository,CreateEntryValidator $createEntryValidator){
        $this->entryRepository = $entryRepository;
        $this->createEntryValidator = $createEntryValidator;
    }

    public function process(CreateEntryRequest $request,CreateEntryResponse $response){

        $response->setRequestData($request);
        $this->createEntryValidator->setRequestData($request);

        if(!$this->createEntryValidator->isValid()){
            $response->fail();
            $response->setErrors($this->createEntryValidator->getErrors());
            return;
        }

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