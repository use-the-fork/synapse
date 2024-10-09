# Anatomy Of A Tool

## Your First Tool
Creating your own tool is easy! You can start by extending the base `BaseTool` as seen below.

```php
<?php

    use UseTheFork\Synapse\Tools\BaseTool;

    final class SerperTool extends BaseTool
    {
    
    }
```

All Tools MUST have a `handle` method that conforms to PHP doc block comments and is strongly typed. This is because when parsing tools Synapse creates a reflection class and uses the doc block and param types to tell the agent what arguments must be passed in.

```php
<?php

    use UseTheFork\Synapse\Tools\BaseTool;

    final class SerperTool extends BaseTool
    {
      /**
       * Search Google using a query.
       *
       * @param string $query the search query to execute.
       * @param string $searchType the type of search must be one of `search`, `places`, `news`.  (usually search).
       * @param int $numberOfResults the number of results to return must be one of `10`, `20`, `30`, `40`, `50` (usually `10`).
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

In the above example the Agent would receive the tool and argument description from the Doc block and the typing and required arguments from the method arguments.

## Pending Agent Task
One of the more intresting things you can do with tools is have them modify, use, or make changes to the `PendingAgentTask`. All tools have a `boot` method that is automatically firerd and sets `protected PendingAgentTask $pendingAgentTask;`. This means your tool can use anything that is exposed by `PendingAgentTask` for example:


```php
<?php

    use UseTheFork\Synapse\Tools\BaseTool;
    use UseTheFork\Synapse\ValueObject\Message;

    final class CreateTagsTool extends BaseTool
    {
      /**
       * Useful for creating tags from text.
       *
       * @param string $text the text that should be used to create the tags.
       *
       */
      public function handle(
          string $text,
      ): string {
  
          $result = $this->pendingAgentTask->agent()->integration()->handleCompletion(Message::make([
            'role' => 'user'
            'content' => "Convert this text in to a list of comma seperated tags. {$text}"
            ]));
  
          return $result->content();
      }
    }
```

As you can see we have out tool make an additional integration call to get a list of tags and return them. In fact you can even use a seperate agent as a tool! the sky is the limit.
