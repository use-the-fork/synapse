<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Tools\SQL\InfoSQLDatabaseTool;
use UseTheFork\Synapse\Tools\SQL\ListSQLDatabaseTool;
use UseTheFork\Synapse\Tools\SQL\QuerySQLDataBaseTool;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

//Implementation of https://github.com/langchain-ai/langchain/blob/master/libs/community/langchain_community/agent_toolkits/sql/prompt.py
class SQLToolAgent extends Agent implements HasOutputSchema
{
    use ValidatesOutputSchema;

    protected string $promptView = 'synapse::Prompts.SQLToolPrompt';

    public function resolveOutputSchema(): array
    {
        return [
            SchemaRule::make([
                'name' => 'answer',
                'rules' => 'required|string',
                'description' => 'The answer to the users question.`',
            ])
        ];
    }

    protected function resolveTools(): array
    {
        return [
            new ListSQLDatabaseTool,
            new InfoSQLDatabaseTool,
            new QuerySQLDataBaseTool,
        ];
    }
}
