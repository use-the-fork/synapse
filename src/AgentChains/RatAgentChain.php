<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\AgentChains;

    use UseTheFork\Synapse\AgentChain;
    use UseTheFork\Synapse\Agents\Rat\GetQueryAgent;
    use UseTheFork\Synapse\Agents\Rat\RatDraftAgent;
    use UseTheFork\Synapse\Agents\Rat\RatSplitAnswerAgent;
    use UseTheFork\Synapse\Agents\Rat\ReflectAnswerAgent;
    use UseTheFork\Synapse\Agents\Rat\ReviseAnswerAgent;
    use UseTheFork\Synapse\Contracts\Tool\ScrapeTool;
    use UseTheFork\Synapse\Contracts\Tool\SearchTool;
    use UseTheFork\Synapse\ValueObject\Message;

    /*
     * See: https://github.com/CraftJarvis/RAT/tree/main
     */
    class RatAgentChain extends AgentChain
    {
        protected ScrapeTool $scrapeTool;
        protected SearchTool $searchTool;

        public function __construct(SearchTool $searchTool, ScrapeTool $scrapeTool)
        {
            $this->searchTool = $searchTool;
            $this->scrapeTool = $scrapeTool;
        }

        public function handle(?array $input, ?array $extraAgentArgs = []): Message
        {

            $ratDraftAgent = new RatDraftAgent;
            $result = $ratDraftAgent->handle($input);

            $ratSplitAnswerAgent = new RatSplitAnswerAgent;
            $result = $ratSplitAnswerAgent->handle([
                ...$input,
               ...$result->content()
                                                   ]);

            $answer = str('');
            foreach ($result->content()['paragraphs'] as $value) {

                $answer = $answer->append("\n\n", $value);

                $getQueryAgent = new GetQueryAgent;
                $result = $getQueryAgent->handle([
                                           ...$input,
                                           'answer' => $answer->toString()
                                       ]);

                $searchResult = $this->searchTool->handle($result->content()['query']);
                $searchResult = json_decode($searchResult, true);

                # Get the first Result scrape page and enhance
                if(!empty($searchResult[0])){
                    $scrapeResult = $this->scrapeTool->handle($searchResult[0]['link']);

                    $getQueryAgent = new ReviseAnswerAgent;
                    $result = $getQueryAgent->handle([
                                                         ...$input,
                                                         'content' => $scrapeResult,
                                                         'answer' => $answer->toString()
                                                     ]);
                    $answer = str($result->content()['answer']);
                }

            }

            $reflectAnswerAgent = new ReflectAnswerAgent;
            return $reflectAnswerAgent->handle([
                                                 ...$input,
                                                 'answer' => $answer->toString()
                                             ]);
        }
    }
