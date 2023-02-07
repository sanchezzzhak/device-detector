<?php


namespace DeviceDetector\Parser;

class TokenParser
{
    private $userAgent = '';
    public $tokens = [];

    public function __construct(string $userAgent)
    {
        $this->userAgent = $userAgent;
        $this->parse();
    }

    private const TOKEN_MAIN_SPLIT = '~ (?![^()]*(?:\(?.*\))?\))~';
    private const TOKEN_SPLIT = '~ (?![^(]*\))~';
    private const TOKEN_GROUP = '~[;,] ?~';

    private function reset(): void
    {
        $this->userAgent = '';
        $this->tokens = [];
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->reset();
        $this->userAgent = $userAgent;
    }

    public function parse(): void
    {
        $index = 0;
        $tokens = preg_split(self::TOKEN_SPLIT, $this->getUserAgent(), -1);
        $this->tokens = array_reduce($tokens, static function ($group, $token) use (&$index) {
            if (strpos($token, '(') === 0) {
                $groups = preg_split(self::TOKEN_GROUP, preg_replace('~^\(|\)$~', '', $token));
                if (count($groups) > 1) {
                    $group['#group' . ($index++)] = $groups;
                    return $group;
                }
            }

            $parts = explode('/', $token);

            $count = count($parts);
            if ($count === 2 && $parts[0]) {
                $group[$parts[0]] = $parts[1];
                return $group;
            }
            if ($count === 2) {
                $group[$parts[1]] = '';
                return $group;
            }
            if ($count === 1) {
                $group[$parts[0]] = true;
                return $group;
            }

            return $group;
        }, []);

    }
}
