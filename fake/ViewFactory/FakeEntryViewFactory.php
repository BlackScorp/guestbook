<?php
namespace BlackScorp\GuestBook\Fake\ViewFactory;

use BlackScorp\GuestBook\Entity\EntryEntity;
use BlackScorp\GuestBook\Fake\View\FakeEntryView;
use BlackScorp\GuestBook\View\EntryView;
use BlackScorp\GuestBook\ViewFactory\EntryViewFactory;

class FakeEntryViewFactory implements EntryViewFactory
{
    /**
     * @param EntryEntity $entity
     * @return EntryView
     */
    public function create(EntryEntity $entity)
    {

        $view = new FakeEntryView();
        $view->author = $entity->getAuthor();
        $view->text = $entity->getText();
        return $view;
    }
}