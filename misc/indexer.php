<?php

require __DIR__ . '/../vendor/autoload.php';

use DeviceDetector\DeviceDetector;


/*
The goal is to reduce the number of iterations by indexing groups of regular expressions
1 Create AST tree for first regulars brand
2 Create all regular patterns from AST tree, discarding too specific options Beelink (GT1|LAKE I|SEA I|A1)
3 An example is a regular Beelink (GT1|LAKE I|SEA I|A1)[);\/ ] extract to array
Beelink GT1,
Beelink LAKE I
Beelink SEA I
Beelink A1
*/



class DeviceIndexer
{
    private $sourceData = [];

    public function build()
    {
        $deviceDetector = new DeviceDetector();
        $parsers = $deviceDetector->getDeviceParsers();
        // create regex map brand => regex
        foreach ($parsers as $parser) {
            $reflectionMethod = (new ReflectionClass($parser))->getMethod('getRegexes');
            $reflectionMethod->setAccessible(true);
            $regexes = $reflectionMethod->invoke($parser);
            $parserName = $parser->getName();
            $this->sourceData[$parserName] = [];
            foreach ($regexes as $brand => $regex) {
                $regex = $regex['regex'];
                $parserAst = new ParserAST($regex);
                $ast = $parserAst->parse();
                $this->sourceData[$parserName][$brand] = compact('regex', 'ast');
            }
        }


    }

}

//$indexer = new DeviceIndexer();
//$indexer->build();

$parser = new RegexParserAST('Beelink (GT1|LAKE I|SEA I|A1)[);\/ ]');
$ast = $parser->parse();

var_dump($ast);