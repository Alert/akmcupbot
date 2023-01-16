<?php
declare(strict_types=1);

namespace App\Object;

use LogicException;

/**
 * Parsed callback data
 */
class CallbackParsedDataObj
{
    public int $seasonNum;
    public int $eventNum;
    public string $action;

    public function __construct(string $text)
    {
        [$this->seasonNum, $this->eventNum, $this->action] = $this->parseString($text);
    }

    private function parseString(string $text): array
    {
        if (!preg_match('/(?<season>\d+)e(?<event>\d+)(?:\.(?<action>[^\s]+))?/', $text, $matches)) {
            throw new LogicException('Can\'t parse callback data: ' . $text);
        }

        return [(int)$matches['season'], (int)$matches['event'], (string)$matches['action']];
    }
}
