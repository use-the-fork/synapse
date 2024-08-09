<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\OutputParsers;

use UseTheFork\Synapse\OutputParsers\Contracts\OutputParser;

class JsonOutputParser extends BaseOutputParser implements OutputParser
{
    public function __construct($expectedOutputFormat)
    {

        //    $schema = [];
        //    foreach ($expectedOutputFormat as $property => $value) {
        //      $schema[$property] = gettype($value);
        //    }
        //    dd($schema);

        $this->expectedOutputFormat = "```json\n".json_encode($expectedOutputFormat, JSON_PRETTY_PRINT)."\n```";
    }

    protected function parse($input)
    {
        return json_decode(
            str($input)->replace([
                '```json',
                '```',
            ], '')->toString(), true
        );
    }

    public function getOutputFormat(): string
    {
        return $this->expectedOutputFormat;
    }
}
