<?php

namespace noccyLabs\LogHoc;

use NoccyLabs\LogHoc\LogBuffer;

class LogBufferTest extends \PHPUnit\Framework\TestCase
{
    public function testWritingToTheBuffer()
    {
        $buffer = new LogBuffer();
        $buffer->info("This is info");
        $buffer->debug("This is debug");
        $msg = $buffer->getBuffer();

        $expectedMsg = "      info | This is info\n     debug | This is debug";

        $this->assertEquals($expectedMsg, $msg);
    }

    public function testFetchingBufferAsArray()
    {
        $buffer = new LogBuffer();
        $buffer->info("This is info");
        $buffer->debug("This is debug");
        $log = $buffer->getBufferArray();

        $this->assertEquals(2, count($log));
    }

    public function testStringCastingTheBuffer()
    {
        $buffer = new LogBuffer();
        $buffer->info("This is info");
        $buffer->debug("This is debug");
        $msg = (string)$buffer;

        $expectedMsg = "      info | This is info\n     debug | This is debug";

        $this->assertEquals($expectedMsg, $msg);
    }

    public function testDumpingToBuffer()
    {
        $buffer = new LogBuffer();
        $buffer->dump("hello");
        $msg = $buffer->getBuffer();
        $expectedMsg = "           | \"hello\"";
        $this->assertEquals($expectedMsg, $msg);

        $buffer = new LogBuffer();
        $buffer->dump(true);
        $msg = $buffer->getBuffer();
        $expectedMsg = "           | true";
        $this->assertEquals($expectedMsg, $msg);

        $buffer = new LogBuffer();
        $buffer->dump(["hello"]);
        $msg = $buffer->getBuffer();
        $expectedMsg = "           | [\n           |     \"hello\"\n           | ]";
        $this->assertEquals($expectedMsg, $msg);
    }

    public function testMarkingInBuffer()
    {
        $buffer = new LogBuffer();
        $buffer->mark("begin");
        $msg = $buffer->getBuffer();
        $expectedMsg = "      ---- | begin";
        $this->assertEquals($expectedMsg, $msg);
    }

    public function testChangingTheOutputFormat()
    {
        $buffer = new LogBuffer();
        $buffer->setFormat("%message%");
        $buffer->info("hello");
        $buffer->debug("world");
        $msg = $buffer->getBufferArray();
        $expectedMsg = [ "hello", "world" ];
        $this->assertEquals($expectedMsg, $msg);
    }

}