# Packaged Tools

Below is a list of tools included in this package that you can use in your agent.

## Serper Tool

The Serper tool allows your agent to perform Google searches.

Add your Serper API key to your `.env` file:
You can get an API key by signing up here: [Serper API](https://serper.dev/)

```dotenv
SERPER_API_KEY=
```

Then, include the `SerperTool` in your agent:

```php
use UseTheFork\Synapse\Tools\SerperTool;

protected function resolveTools(): array
{
    return [new SerperTool];
}
```

## SERP API Tool

The SERP API tool enables your agent to search Google and Google News, depending on the tool you choose.

Add your SERP API key to your `.env` file:
You can get an API key by signing up here: [SERP API](https://serpapi.com/)

```dotenv
SERPAPI_API_KEY=
```

Then, include either the `SerpAPIGoogleSearchTool` or the `SerpAPIGoogleNewsTool` in your agent:

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

The Firecrawl tool enables your agent to scrape the content of a webpage using its full URL.

Add your Firecrawl API key to your `.env` file:
You can get an API key by signing up here: [Firecrawl](https://www.firecrawl.dev/)

```dotenv
FIRECRAWL_API_KEY=
```

Then, include the `FirecrawlTool` in your agent:

```php
use UseTheFork\Synapse\Tools\FirecrawlTool;

protected function resolveTools(): array
{
    return [new FirecrawlTool];
}
```

## Crunchbase Tool

The Crunchbase tool allows your agent to gather information about a company on Crunchbase using an `entityID`.

Add your Crunchbase API key to your `.env` file:
You can get an API key by signing up here: [Crunchbase API](https://data.crunchbase.com/docs/using-the-api)

```dotenv
CRUNCHBASE_API_KEY=
```

Then, include the `CrunchbaseTool` in your agent:

```php
use UseTheFork\Synapse\Tools\CrunchbaseTool;

protected function resolveTools(): array
{
    return [new CrunchbaseTool];
}
```

## Clearbit Company Tool

The Clearbit Company tool allows your agent to gather information about a company on Clearbit using its domain name.

Add your Clearbit API key to your `.env` file:
You can get an API key by signing up here: [Clearbit](https://clearbit.com/)

```dotenv
CLEARBIT_API_KEY=
```

Then, include the `ClearbitCompanyTool` in your agent:

```php
use UseTheFork\Synapse\Tools\ClearbitCompanyTool;

protected function resolveTools(): array
{
    return [new ClearbitCompanyTool];
}
```
