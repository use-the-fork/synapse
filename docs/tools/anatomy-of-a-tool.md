# Anatomy of a Tool

## Your First Tool

Creating your own tool is simple! Start by extending the base `BaseTool` class, as shown below:

```php
<?php

use UseTheFork\Synapse\Tools\BaseTool;

final class SerperTool extends BaseTool
{

}
```

All tools must have a `handle` method that follows PHP doc block standards and is strongly typed. This ensures that Synapse can parse the tool by creating a reflection class, using the doc block and parameter types to instruct the agent on what arguments must be passed.

```php
<?php

use UseTheFork\Synapse\Tools\BaseTool;

final class SerperTool extends BaseTool
{
  /**
   * Search Google using a query.
   *
   * @param string $query The search query to execute.
   * @param string $searchType The type of search, must be one of `search`, `places`, `news` (usually `search`).
   * @param int $numberOfResults The number of results to return, must be one of `10`, `20`, `30`, `40`, `50` (usually `10`).
   *
   */
  public function handle(
      string $query,
      string $searchType = 'search',
      int $numberOfResults = 10,
  ): string {

      $serperConnector = new SerperConnector($this->apiKey);
      $serperSearchRequest = new SerperSearchRequest($query, $searchType, $numberOfResults);
      $results = $serperConnector->send($serperSearchRequest)->array();

      return $this->parseResults($results);
  }
}
```

In this example, the agent will receive the tool and argument description from the doc block, and the typing and required arguments from the method parameters.

## Pending Agent Task

Tools can also modify, use, or make changes to the `PendingAgentTask`. All tools have a `boot` method that automatically fires and sets `protected PendingAgentTask $pendingAgentTask`. This means your tool can access anything exposed by `PendingAgentTask`. For example:

```php
<?php

use UseTheFork\Synapse\Tools\BaseTool;
use UseTheFork\Synapse\ValueObject\Message;

final class CreateTagsTool extends BaseTool
{
  /**
   * Useful for creating tags from text.
   *
   * @param string $text The text that should be used to create the tags.
   *
   */
  public function handle(
      string $text,
  ): string {

      $result = $this->pendingAgentTask->agent()->integration()->handleCompletion(Message::make([
        'role' => 'user',
        'content' => "Convert this text into a list of comma-separated tags: {$text}"
      ]));

      return $result->content();
  }
}
```

In this example, the tool makes an additional integration call to generate a list of tags and returns them. You can even use a separate agent as a tool—there are endless possibilities!
