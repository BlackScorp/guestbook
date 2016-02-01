<?php
namespace BlackScorp\GuestBook\UseCase;

use BlackScorp\GuestBook\Request\ViewEntriesRequest;
use BlackScorp\GuestBook\Response\ViewEntriesResponse;

class ViewEntriesUseCase
{

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