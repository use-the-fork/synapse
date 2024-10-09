# Packaged Tools

Below is a list of Tools that come with this package that you can use in your agent.

## Serper Tool

The Serper tool gives your agent the ability to search Google.

You will need to add Serper Api key to your .env file.
You can get one by signing up here: https://serper.dev/

```dotenv
SERPER_API_KEY=
```

then you can include the `SerperTool` in your agent and your good to go!

```php
  use UseTheFork\Synapse\Tools\SerperTool;

  protected function resolveTools(): array
  {
      return [new SerperTool];
  }
```

## SERP API Tool

The SERP API tool gives your agent the ability to search Google as well as Google news depending on the tool you pick.

You will need to add SERP API key to your .env file.
You can get one by signing up here: https://serpapi.com/

```dotenv
SERPAPI_API_KEY=
```

then you can include the `SerpAPIGoogleNewsTool` or the `SerpAPIGoogleSearchTool` in your agent and your good to go!

```php
  use UseTheFork\Synapse\Tools\SerpAPIGoogleNewsTool;
  use UseTheFork\Synapse\Tools\SerpAPIGoogleSearchTool;

  protected function resolveTools(): array
  {
      return [
            new SerpAPIGoogleSearchTool,
            new SerpAPIGoogleNewsTool,
        ];
  }
```

## Firecrawl Tool

The Firecrawl tool gives your agent the ability to scrape the content of a webpage given a full URL.

You will need to add a Firecrawl API key to your .env file.
You can get one by signing up here: https://www.firecrawl.dev/

```dotenv
FIRECRAWL_API_KEY=
```

then you can include the `FirecrawlTool` in your agent and your good to go!

```php
  use UseTheFork\Synapse\Tools\FirecrawlTool;

  protected function resolveTools(): array
  {
      return [
            new FirecrawlTool
        ];
  }
```

## Crunchbase Tool

The Crunchbase tool gives your agent the ability to gather information about a Company on crunchbase using a entityID.

You will need to add a Crunchbase API key to your .env file.
You can get one by signing up here: https://data.crunchbase.com/docs/using-the-api

```dotenv
CRUNCHBASE_API_KEY=
```

then you can include the `CrunchbaseTool` in your agent and your good to go!

```php
  use UseTheFork\Synapse\Tools\CrunchbaseTool;

  protected function resolveTools(): array
  {
      return [
            new CrunchbaseTool
        ];
  }
```

## Clearbit Company Tool

The Clearbit Company tool gives your agent the ability to gather information about a Company on Clearbit using a domain name.

You will need to add a Clearbit API key to your .env file.
You can get one by signing up here: https://clearbit.com/

```dotenv
CLEARBIT_API_KEY=
```

then you can include the `ClearbitCompanyTool` in your agent and your good to go!

```php
  use UseTheFork\Synapse\Tools\ClearbitCompanyTool;

  protected function resolveTools(): array
  {
      return [
            new ClearbitCompanyTool
        ];
  }
```
