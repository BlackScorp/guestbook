<?php

namespace BlackScorp\GuestBook\UseCase;

use BlackScorp\GuestBook\Repository\EntryRepository;
use BlackScorp\GuestBook\Request\ViewEntriesRequest;
use BlackScorp\GuestBook\Response\ViewEntriesResponse;
use BlackScorp\GuestBook\ViewFactory\EntryViewFactory;

class ViewEntriesUseCase
{
    private $entryRepository;
    private $entryViewFactory;

    public function __construct(EntryRepository $entryRepository, EntryViewFactory $entryViewFactory)
    {
        $this->entryRepository = $entryRepository;
        $this->entryViewFactory = $entryViewFactory;
    }

    public function process(ViewEntriesRequest $request, ViewEntriesResponse $response)
    {

        $entries = $this->entryRepository->findAllPaginated($request->getOffset(), $request->getLimit());

        if (!$entries) {
            return;
        }

        foreach ($entries as $entry) {
            $entryView = $this->entryViewFactory->create($entry);
            $response->addEntry($entryView);
        }
    }
}