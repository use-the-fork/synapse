# Changelog

All notable changes to this project will be documented in this file.

## [0.3.0] - 2025-03-11

### 🚀 Features

- Rename agent memory and messages tables to synapse_agent_memories and synapse_messages

### 🚜 Refactor

- *(tools)* [**breaking**] Reorganize tool structure and enhance ReturnType handling 🛠️
- *(tools)* [**breaking**] Restructure FirecrawlTool and update FirecrawlRequest
- *(agent/task)* Simplify task iteration management and response handling
- *(search-tools)* Enhance result formatting with structured JSON output

### 📚 Documentation

- *(changelog)* Add comprehensive changelog for version 0.1.0 and 0.2.0
- *(README)* Update project description and references 📝

### ⚙️ Miscellaneous Tasks

- *(workflows)* Remove unused `update-changelog.yml` workflow file 🗑️
- *(tests)* Remove obsolete RatAgentChain fixture files 🗑️

## [0.1.1] - 2024-10-16

### 🚀 Features

- *(helpers)* Add dedent method to remove common leading whitespace
- *(agent)* Enhance integration resolution mechanism 📦
- *(commands)* Add SynapseArtisan command and agent 🚀
- *(api)* Introduce new endpoint for user management 🆕✨
- *(sql-tool)* Introduce SQLToolAgent with documentation and tests 🎉
- *(traits)* Introduce configurable agents and conditionable utilities 🛠️
- *(integrations)* Add Ollama integration and tests 🛠️
- *(ollama)* Implement embedding creation functionality
- *(validation)* Improve response format handling and validation prompts 🛠️

### 🐛 Bug Fixes

- *(validation)* Enhance JSON response handling in output schema 🛠️
- *(integrations)* Replace OllamaAIConnector with ClaudeAIConnector and improve ChatRequest handling ✨

### 🚜 Refactor

- *(docs)* Update references from Laravel Synapse to Synapse 🚀
- *(docs)* Update references from Laravel Synapse to Synapse 🚀
- *(agent)* 🛠️ streamline tool management logic by rearranging methods
- *(SynapseArtisan)* Enhance command execution flow and update documentation 🛠️
- *(agents)* Remove direct OpenAI integrations
- *(agent)* Separate `use` statements for better readability and maintainability

### 📚 Documentation

- *(README)* Update usage instructions and add prebuilt agents info 🚀
- Correct package inspiration reference in documentation
- *(integrations)* Add Ollama integration 🌟
- *(integrations)* Add Ollama integration 🌟

### 🎨 Styling

- *(tests)* Adjust indentation in ClaudeIntegrationTest.php 📏

### 🧪 Testing

- *(SQLToolAgent)* Add new JSON fixtures and update test verification logic
- *(SQLToolAgentTest)* Simplify content assertion using multiple contains
- *(integrations)* Update tests with improved output assertions and fixture usage ♻️
- *(tests)* Update test expectations and improve readability 🧪
- *(memory)* Update conversation test summaries for clearer content verification 🧪

### ⚙️ Miscellaneous Tasks

- *(tests)* Remove outdated fixtures for clearbit, crunchbase, and firecrawl
- *(ci)* Update checkout action to v4
- *(workflows)* Update branch name and add OpenAI API key to tests.yml

## [0.1.0] - 2024-10-12

### 🚀 Features

- *(nix)* Introduce snow-blower configuration with PHP and Composer setups
- *(workbench)* Initialize Laravel project structure with essential configurations
- *(tests)* Add initial test infrastructure and setup
- *(synapse)* Introduce tools for handling AI-generated tasks and enhanced project management
- *(agents)* Enhance tool handling with new Service and ValueObject refactor
- *(parsers)* Implement output parsing for JSON and string
- *(services)* Add Clearbit company search tool and related tests
- *(toolList)* Add tool list view and enhance tool handling
- *(agent)* Support multi-message prompts and improve parsing
- *(flake)* Add publicKeys and enable agenix for .env secrets
- *(memory)* Add asString method to serialize agent messages
- *(provider)* Add migration publishing to SynapseServiceProvider
- *(crunchbase)* Add Crunchbase service and tool for organization data handling
- *(FirecrawlTool)* Introduce FirecrawlTool for webpage content scraping
- *(agent)* Add SearchAndScrapeAgent with error handling and new view template
- *(agent)* Add SearchAndScrapeAgent with error handling and new view template
- *(chat-rephrase)* Add Chat Rephrase Agent with prompt and test
- *(openai)* Add support for extra agent arguments in handle methods
- *(FirecrawlTool)* Enhance URL scraping with extraction prompts and add tests
- *(tool)* Enhance SerperTool, add test coverage
- *(tools)* Add SerpAPI Google search capability
- *(SerpAPIGoogleNewsTool)* Add tool for Google News searches, update agent and tests
- *(logging)* Add event logging to Agent class
- *(memory)* Add set and clear methods for agent memory management
- *(memory)* Add set and clear methods to CollectionMemory class
- *(memory)* Expand payload structure and add new tests
- *(output-rules)* Improve validation error handling and messaging
- *(VectorStores)* Add new contract and base implementation for vector store
- *(exceptions)* Add MissingApiKeyException class for handling missing API key errors
- *(integration)* Add support for Claude AI integration and improve tool handling
- *(openai)* Add validation request for output formatting in OpenAI integration
- *(integrations)* Update model configuration and add embeddings support
- *(embeddings)* Add embedding creation support
- *(output-rules)* Enhance validation prompt handling and add error view
- *(tools)* Enhance tool handling with better parameter description and validation
- *(tools)* Add AI integration capability with Saloon Connector
- *(tests)* Enhance ChatRephraseAgent tests with mock client, add message handling, and fixture data
- *(image input)* Update message type, improve test structure for ImageAgent
- *(agent)* Add custom exception for unknown finish reasons
- *(linting)* Add `refactor-file` command to flake.nix and composer.json
- *(docs)* Revamp documentation structure and add VitePress for better dev experience
- *(agent)* Add KnowledgeGraphExtractionAgent with prompt and test
- *(agent)* Add ContextualRetrievalPreprocessingAgent with fixtures and tests
- *(Synapse)* Add installation command and refine publishing logic
- *(agent)* Add ContextualRetrievalPreprocessingAgent with fixtures and tests
- *(logging)* Add comprehensive logging to middleware pipelines and agent tasks
- *(agent)* Add maximum iteration limit to prevent infinite loops
- *(agent)* Introduce HasOutputSchema interface for output schema validation
- *(agents)* Introduce boot agent pipeline and update memory management
- *(memory)* Enhance database memory functionality and add tests
- *(agent)* Introduce event handling and new Agent event classes
- *(agent)* Enhance agent functionality and documentation 🌟
- *(validation)* Implement output schema validation for agents 🚀
- *(memory)* Add DatabaseMemory implementation and improve documentation 💾📚
- *(agents)* Add prebuilt MultiQueryRetrieverAgent and documentation 🛠️
- *(agent)* Enhance memory management and update schemas 🎉
- *(contextual-retrieval-preprocessing-agent)* Add new Contextual Retrieval Preprocessing Agent with tests and documentation 🚀
- *(logging)* Enhance agent activity logging and add unit tests 📝
- *(tests)* Add SQL tools and integration tests 🎉

### 🐛 Bug Fixes

- *(memory)* Correct array keys to prevent improper payload handling
- *(models)* Update relationship in Message to use AgentMemory instead of Assistant
- *(memory)* Ensure proper formatting of message payloads with newlines
- *(BaseTool)* Correct class name retrieval in log method
- *(FirecrawlService)* Improve extraction prompt for content relevance
- *(logging)* Remove redundant results logging in tools and set default context in BaseTool
- *(FirecrawlService)* Improve clarity of extraction prompt
- *(FirecrawlService)* Improve clarity of extraction prompt
- *(FirecrawlService)* Correct grammar in extraction prompt for clarity
- *(tests)* Remove unused Serper service configuration in TestCase
- *(views)* Correct order of includes and message sorting
- *(OpenAI)* Remove debugging code and correct response data handling in EmbeddingsRequest
- *(agent)* Add memory and integration resolver exception handling 🚀
- *(memory)* Correct input key and debug statements in CollectionMemoryTest
- *(agent)* Correct middleware method for tool initialization
- *(agent)* Correct middleware method for tool initialization
- *(tests)* Remove outdated memory fixture files 🗑️
- *(tests)* Update assertions for messages and standalone_question

### 🚜 Refactor

- *(agent)* Streamline agent implementation, enhance OpenAI integration
- *(core)* Update tool call handling and Google search feature
- *(memory)* Implement new agent executor, enhance tool handling and OpenAI integration
- *(SystemPrompts)* Convert BaseSystemPrompt to abstract class and clean up imports in tests
- *(prompts)* Standardize prompt architecture and update invocation flow
- *(core)* Remove obsolete classes and decouple concerns
- *(Message)* Rename MessageValueObject to Message
- *(messages)* Update prompt syntax and improve parsing logic
- *(SerperService)* Inject apiKey via constructor, enhance SearchGoogleTool output format
- *(output)* Convert Message object to array for prompt handling
- *(api)* Replace Message with Response for better encapsulation
- *(memory)* Split memory into two formats, update prompts to use new memory format
- *(models)* Remove unused Eloquent models
- *(Message)* Flatten tool attributes in value object and remove unused method
- *(tools)* Extract common logic to BaseTool and remove unused method from Tool contract
- *(events)* Rename model event methods to agent event methods and update related logic
- *(FirecrawlTool)* Remove unused imports and adjust indentation
- *(logging)* Consolidate logging logic into BaseTool class
- *(SimpleAgent)* Replace SearchGoogleTool with SerperTool
- *(search)* Streamline SerpAPIService parameter handling and update method signature
- *(test)* Remove unused 'serper' config from TestCase setup
- *(migrations)* Change `agent_memory_id` to UUID in `messages` table migration
- *(tools)* Simplify query parameter descriptions in search tools
- *(SynapseServiceProvider)* Replace publishesMigrations with loadMigrationsFrom
- *(tools)* Standardize API key retrieval using config files
- *(config)* Restructure API key and model configurations
- *(openai)* Replace direct integration calls with Saloon for OpenAI interactions
- *(serper)* Modularize Serper integration and update usage
- *(services)* Modularize ClearbitService into separate request and connector classes
- *(crunchbase)* Modularize and enhance Crunchbase integration
- *(Firecrawl)* Modernize Firecrawl service architecture
- *(serpapi)* Integrate new SerpApiConnector, remove deprecated service, and enhance API key handling
- *(SerpAPI)* Streamline Google News tool handling; update tests and fixtures
- *(integrations)* Reorganize OpenAI integration structure
- *(tests)* Remove redundant `only` call in ClaudeIntegrationTest
- *(connectors/claude)* Remove unused import in ValidateOutputRequest
- *(integrations)* Update message formatting and tool handling
- *(integrations)* Relocate Integration contract for better organization
- *(ValueObjects)* Update comment to accurately reflect the Message validator
- *(agent)* Streamline constructor and optimize methods for clarity and efficiency
- *(integrations)* Update docblocks in HasIntegration.php for clarity
- *(memory)* Reorganize memory methods and improve doc comments
- *(memory)* Improve method visibility and documentation in memory classes
- *(logging)* Consolidate logging functionality into HasLogging trait
- *(models)* Reorganize memory-related models to improve structure
- *(tests)* Relocate and update MultiQueryRetrieverAgent tests for improved structure and mocking
- *(agent)* Remove ProfessionalEditorAgent and associated tests and resources
- *(SearchAndScrapeAgent)* Remove deprecated agent and related files
- *(codebase)* Optimize null/empty checks and type hints across various classes
- *(codebase)* Optimize null/empty checks and type hints across various classes
- *(src)* Standardize variable names and signatures
- *(schema)* Rename OutputRules to OutputSchema across the codebase
- *(tests)* Reorganize CollectionMemoryTest directory structure
- *(agent)* Enhance PendingAgentTask and integration handling
- *(agents)* Restructure traits for better organization and functionality
- *(integrations)* Remove unused Message import and method
- *(agent)* Move Agent class to base namespace 🛠️
- *(project)* Restructure namespaces and traits for consistency ♻️
- *(agent)* Standardize namespaces and manage memory/tools
- *(value-objects)* Reorganize Message and EmbeddingResponse namespaces 🔄
- *(memory)* Move Memory contract to new namespace ♻️
- *(integrations)* Relocate `Response` value object to new namespace ♻️
- *(integration)* Rename OpenAiIntegration to OpenAIIntegration and update methods
- *(integration)* Rename OpenAiIntegration to OpenAIIntegration and update methods
- *(agent)* Rename traits for clarity 🌟
- *(models)* 🦊 relocate models to a new namespace for better organization
- *(tools)* Remove ToolCallValueObject and update references 💅
- *(tool)* Modify BaseTool to initialize with PendingAgentTask
- *(tools)* Improve tool handling and integration
- *(agent)* Rename Agent namespace to AgentTask for clarity
- *(agents)* Move agent classes from Templates to Agents namespace
- *(agent)* Streamline integration management and tool handling
- *(memory)* Unify memory management and enhance tool initialization 🌟✨
- *(agent/memory)* Synchronize memory management with middleware pipeline and integrate new interfaces 🧠
- *(tests)* Rename fixture files for OpenAi integration tests for better organization
- *(tests)* Streamline fixture paths and remove unused imports
- *(SerpAPIGoogleSearchTool)* Streamline API key handling and enhance tests
- *(SerpAPIGoogleNewsTool)* Streamline API key handling and enhance test coverage
- *(firecrawl)* Update endpoint and extraction schema handling
- *(CrunchbaseTool)* Simplify initialization and enhance boot method
- *(clearbit)* Streamline ClearbitCompanyTool logic, update tests
- *(Message)* Update content return type to mixed
- *(agent-hooks)* Split and enhance hook interfaces, add tests🐛
- *(agent-hooks)* Split and enhance hook interfaces, add tests🐛
- *(memory)* Remove deprecated memory trait and methods 💾
- *(tools)* Streamline API key handling and remove unnecessary boot method 🔧
- *(tools)* Streamline API key handling and remove unnecessary boot method 🔧
- *(KnowledgeGraphExtractionAgent)* Implement HasOutputSchema interface and update schema validation 🎯
- *(command)* Streamline SynapseInstall.php structure

### 📚 Documentation

- *(getting-started)* Add installation guide for Laravel Synapse
- *(Response)* Correct annotation for validationRules method
- *(agent)* Add detailed docblocks for methods
- *(ClearbitCompanyTool)* Add missing docblocks and exceptions, improve method annotations
- *(OutputRule)* Add PHPDoc comments for better code clarity and documentation
- *(SynapseServiceProvider)* Enhance boot method docblock for clarity and detail
- *(vitepress)* Add initial VitePress configuration for documentation
- *(agents)* Add comprehensive documentation for agent memory and collection memory 🍱
- *(agent-traits)* Add `HasHooks` contract and `ManagesHooks` trait ✍️
- Reorganize and add new sections to documentation 📄
- *(prompt)* Enhance documentation with new prompt parts and examples ✍️
- *(agents)* Add integration setup documentation for OpenAI and Claude 🚀
- *(prompts)* Enhance documentation for clarity and consistency ✨📚
- *(traits)* Add documentation for events and log traits 📝
- *(agent-lifecycle)* Fix broken link to message tags 📚
- *(license)* Add MIT License to the repository 📄

### 🎨 Styling

- *(codebase)* Adhere to strict type declarations and coding standards
- *(output-rules)* Refine prompt formatting for better readability
- *(provider)* Specify return type and update doc comment in SynapseServiceProvider
- *(docblocks)* Add comprehensive docblocks for constructors and methods
- *(codebase)* Improve code formatting and consistency across files
- *(agent, tests)* ⭐️ Ensure consistent code indentation 🌟

### 🧪 Testing

- *(agent)* Add SearchAndScrapeAgentTest and cleanup existing tests
- *(ImageAgent)* Add basic test structure for scraping functionality
- *(SerperTool)* Enhance coverage and restructure tests with mocking
- *(OpenAiIntegration)* Add test for Connects With OutputSchema
- *(memory)* Update and reorganize CollectionMemory test fixtures
- *(ImageAgent)* Add new fixture for image agent test 🤖
- *(memory)* Add new CollectionMemory test and fixture 🚀

### ⚙️ Miscellaneous Tasks

- *(dependencies)* Remove unused dependencies 'saloonphp/saloon' and 'spatie/laravel-data'
- *(tests)* Restructure OpenAiIntegrationTest.php directory
- *(config)* Add .editorconfig for consistent code formatting
- *(secrets)* Update encrypted environment variables
- *(docs)* Add GitHub Actions workflow to deploy VitePress site to Pages 🚀
- *(ci)* Cleanup workflow and add funding configuration 🧹
- *(workflows)* Add GitHub Actions workflow for running tests 🚀
- *(workflow)* Add GitHub Action to automate changelog updates upon release 🚀
- *(workflows)* Update CI config to use PHP 8.2 and streamline testing process 🛠
- *(workflow)* Add environment API keys for workflows 🛠️

<!-- generated by git-cliff -->
