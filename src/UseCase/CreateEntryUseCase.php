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
    private function applyValidatorData(CreateEntryRequest $request){
        $this->createEntryValidator->authorEmail = $request->getAuthorEmail();
        $this->createEntryValidator->authorName = $request->getAuthorName();
        $this->createEntryValidator->content = $request->getContent();
    }
    public function process(CreateEntryRequest $request,CreateEntryResponse $response){

        $response->setRequestData($request);
        $this->applyValidatorData($request);

        if(!$this->createEntryValidator->isValid()){
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