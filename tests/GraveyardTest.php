<?php
namespace Scheb\Tombstone\Tests;

use Scheb\Tombstone\Graveyard;
use Scheb\Tombstone\Tests\Fixtures\TraceFixture;

class GraveyardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $handler;

    /**
     * @var Graveyard
     */
    private $graveyard;

    public function setUp()
    {
        $this->handler = $this->getHandlerMock();
        $this->graveyard = new Graveyard(array($this->handler));
    }

    private function getHandlerMock()
    {
        return $this->getMock('Scheb\Tombstone\Handler\HandlerInterface');
    }

    /**
     * @test
     */
    public function addHandler_anotherHandler_isCalledOnTombstone()
    {
        $handler = $this->getHandlerMock();
        $this->graveyard->addHandler($handler);

        $this->handler
            ->expects($this->once())
            ->method('log')
            ->with($this->isInstanceOf('Scheb\Tombstone\Vampire'));

        $trace = TraceFixture::getTraceFixture();
        $this->graveyard->tombstone('date', 'author', 'label', $trace);
    }

    /**
     * @test
     */
    public function tombstone_handlersRegistered_callAllHandlers()
    {
        $this->handler
            ->expects($this->once())
            ->method('log')
            ->with($this->isInstanceOf('Scheb\Tombstone\Vampire'));

        $trace = TraceFixture::getTraceFixture();
        $this->graveyard->tombstone('date', 'author', 'label', $trace);
    }

    /**
     * @test
     */
    public function flush_handlerRegistered_flushAllHandlers()
    {
        $this->handler
            ->expects($this->once())
            ->method('flush');

        $this->graveyard->flush();
    }
}
