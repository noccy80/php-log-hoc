<?php

namespace NoccyLabs\LogHoc;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class LogBuffer implements LoggerInterface
{

    use LoggerTrait;

    private $format = "%levelpad% | %message%";

    private $buffer = [];

    public function __invoke($level, $message)
    {
        $this->log($level, $message);
    }

    public function log($level, $message, array $context = [])
    {
        $contextSubst = array_combine(
            array_map(function ($v) { return "{".$v."}"; }, array_keys($context)),
            array_values($context)
        );
        $formattedMessage = strtr($message, $contextSubst);
        $messageParts = [
            '%time%' => date('H:i:s'),
            '%message%' => $formattedMessage,
            '%level%' => $level,
            '%levelpad%' => str_pad($level, 10, " ", STR_PAD_LEFT),
            '%context%' => json_encode($context),
        ];
        $formatted = strtr($this->format, $messageParts);
        array_push($this->buffer, $formatted);
    }

    public function dump($data)
    {
        foreach (explode("\n", json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)) as $line)
            $this->log(null, $line);
    }

    public function mark($message=null)
    {
        $this->log("----", $message);
    }

    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getBuffer(): string
    {
        return join("\n", $this->buffer);
    }

    public function getBufferArray(): array
    {
        return $this->buffer;
    }

    public function __toString()
    {
        return $this->getBuffer();
    }
}
