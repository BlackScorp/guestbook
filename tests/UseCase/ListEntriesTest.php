<?php
require_once __DIR__ . '/../../vendor/autoload.php';

class ListEntriesTest extends PHPUnit_Framework_TestCase
{
    public function testEntriesNotExists()
    {
        $request = new FakeViewEntriesRequest();
        $response = new FakeViewEntriesResponse();
        $useCase = new ViewEntriesUseCase();
        $useCase->process($request, $response);
        $this->assertEmpty($response->entries);
    }
}