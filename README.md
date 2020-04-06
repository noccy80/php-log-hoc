# LogHoc: An ad-hoc logger

This is an ad-hoc logger that implements the PSR `LoggerInterface`and shoves
its output into an easily accessible buffer. Use it to collect information on
different processes in a system without cluttering the application log.

## Installation

Install using composer:

    $ composer require noccylabs/log-hoc

## Using

The logger implements `Psr\LoggerInterface` so you can use it like any other
logger:

    $log = new LogBuffer();
    $log->info("Things are going well");
    $log->debug("You should know this tho: {message}", [ 'message'=>"xyzzy" ]);
    $log->warning("Uh-oh");
    $log->error("Something went wrong");

You can also dump variables to it, as long as they can be serialized to json:

    $log = new LogBuffer();
    $log->dump($customerOrder);
    $log->dump($result);

Marking will drop a marker in the log, representing a split point, start or stop of
a process etc.

    $log = new LogBuffer();
    $log->mark("Starting order processing");

To see what has been logged, you can either string-cast it, or use the `getBuffer()`
or `getBufferArray()` methods to fetch the contents.

    $log = new LogBuffer();
    $orderProcessor->process($order, $log);

    $order->setProcessingLog($log->getBuffer());

You can customize the format slightly:

    $log = new LogBuffer();
    $log->setFormat("%levelpad% %timestamp% // %message%");

You can use `%time%`, `%message%`, `%level%`, `%levelpad%` and `%context%` placeholders
in the format specifier:

    %time%      "19:37:44"
    %message%   "This is a log message"
    %level%     "info"
    %levelpad%  "      info" (right-aligned to 10 chars)
    %context%   "{"foo":"bar"}"

