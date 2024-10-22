# Changelog

All notable changes to this project will be documented in this file.

## \[0.2.0\] - 2024-10-22

### 🚀 Features

- _(helpers)_ Add dedent method to remove common leading whitespace
- _(agent)_ Enhance integration resolution mechanism 📦
- _(commands)_ Add SynapseArtisan command and agent 🚀
- _(api)_ Introduce new endpoint for user management ✨
- _(sql-tool)_ Introduce SQLToolAgent with documentation and tests 🎉
- _(traits)_ Introduce configurable agents and conditionable utilities 🛠️
- _(integrations)_ Add Ollama integration and tests 🛠️
- _(ollama)_ Implement embedding creation functionality
- _(validation)_ Improve response format handling and validation prompts 🛠️
- _(integrations)_ Add Ollama integration 🌟
-

### 🐛 Bug Fixes

- _(validation)_ Enhance JSON response handling in output schema 🛠️
- _(integrations)_ Replace OllamaAIConnector with ClaudeAIConnector and improve ChatRequest handling ✨

### 🚜 Refactor

- _(docs)_ Update references from Laravel Synapse to Synapse 🚀
- _(agent)_ 🛠️ streamline tool management logic by rearranging methods
- _(SynapseArtisan)_ Enhance command execution flow and update documentation 🛠️
- _(agents)_ Remove direct OpenAI integrations
- _(agent)_ Separate `use` statements for better readability and maintainability

### 📚 Documentation

- _(README)_ Update usage instructions and add prebuilt agents info 🚀
- Correct package inspiration reference in documentation

### 🎨 Styling

- _(tests)_ Adjust indentation in ClaudeIntegrationTest.php 📏

### 🧪 Testing

- _(SQLToolAgent)_ Add new JSON fixtures and update test verification logic
- _(SQLToolAgentTest)_ Simplify content assertion using multiple contains
- _(integrations)_ Update tests with improved output assertions and fixture usage ♻️
- _(tests)_ Update test expectations and improve readability 🧪
- _(memory)_ Update conversation test summaries for clearer content verification 🧪

### ⚙️ Miscellaneous Tasks

- _(tests)_ Remove outdated fixtures for clearbit, crunchbase, and firecrawl
- _(ci)_ Update checkout action to v4
- _(workflows)_ Update branch name and add OpenAI API key to tests.yml

## \[0.1.0\] - 2024-10-12

### 🚀 Features

- _(nix)_ Introduce snow-blower configuration with PHP and Composer setups
- _(workbench)_ Initialize Laravel project structure with essential configurations
- _(tests)_ Add initial test infrastructure and setup
- _(synapse)_ Introduce tools for handling AI-generated tasks and enhanced project management
- _(agents)_ Enhance tool handling with new Service and ValueObject refactor
- _(parsers)_ Implement output parsing for JSON and string
- _(services)_ Add Clearbit company search tool and related tests
- _(toolList)_ Add tool list view and enhance tool handling
- _(agent)_ Support multi-message prompts and improve parsing
- _(flake)_ Add publicKeys and enable agenix for .env secrets
- _(memory)_ Add asString method to serialize agent messages
- _(provider)_ Add migration publishing to SynapseServiceProvider
- _(crunchbase)_ Add Crunchbase service and tool for organization data handling
- _(FirecrawlTool)_ Introduce FirecrawlTool for webpage content scraping
- _(agent)_ Add SearchAndScrapeAgent with error handling and new view template
- _(agent)_ Add SearchAndScrapeAgent with error handling and new view template
- _(chat-rephrase)_ Add Chat Rephrase Agent with prompt and test
- _(openai)_ Add support for extra agent arguments in handle methods
- _(FirecrawlTool)_ Enhance URL scraping with extraction prompts and add tests
- _(tool)_ Enhance SerperTool, add test coverage
- _(tools)_ Add SerpAPI Google search capability
- _(SerpAPIGoogleNewsTool)_ Add tool for Google News searches, update agent and tests
- _(logging)_ Add event logging to Agent class
- _(memory)_ Add set and clear methods for agent memory management
- _(memory)_ Add set and clear methods to CollectionMemory class
- _(memory)_ Expand payload structure and add new tests
- _(output-rules)_ Improve validation error handling and messaging
- _(VectorStores)_ Add new contract and base implementation for vector store
- _(exceptions)_ Add MissingApiKeyException class for handling missing API key errors
- _(integration)_ Add support for Claude AI integration and improve tool handling
- _(openai)_ Add validation request for output formatting in OpenAI integration
- _(integrations)_ Update model configuration and add embeddings support
- _(embeddings)_ Add embedding creation support
- _(output-rules)_ Enhance validation prompt handling and add error view
- _(tools)_ Enhance tool handling with better parameter description and validation
- _(tools)_ Add AI integration capability with Saloon Connector
- _(tests)_ Enhance ChatRephraseAgent tests with mock client, add message handling, and fixture data
- _(image input)_ Update message type, improve test structure for ImageAgent
- _(agent)_ Add custom exception for unknown finish reasons
- _(linting)_ Add `refactor-file` command to flake.nix and composer.json
- _(docs)_ Revamp documentation structure and add VitePress for better dev experience
- _(agent)_ Add KnowledgeGraphExtractionAgent with prompt and test
- _(agent)_ Add ContextualRetrievalPreprocessingAgent with fixtures and tests
- _(Synapse)_ Add installation command and refine publishing logic
- _(agent)_ Add ContextualRetrievalPreprocessingAgent with fixtures and tests
- _(logging)_ Add comprehensive logging to middleware pipelines and agent tasks
- _(agent)_ Add maximum iteration limit to prevent infinite loops
- _(agent)_ Introduce HasOutputSchema interface for output schema validation
- _(agents)_ Introduce boot agent pipeline and update memory management
- _(memory)_ Enhance database memory functionality and add tests
- _(agent)_ Introduce event handling and new Agent event classes
- _(agent)_ Enhance agent functionality and documentation 🌟
- _(validation)_ Implement output schema validation for agents 🚀
- _(memory)_ Add DatabaseMemory implementation and improve documentation 💾📚
- _(agents)_ Add prebuilt MultiQueryRetrieverAgent and documentation 🛠️
- _(agent)_ Enhance memory management and update schemas 🎉
- _(contextual-retrieval-preprocessing-agent)_ Add new Contextual Retrieval Preprocessing Agent with tests and documentation 🚀
- _(logging)_ Enhance agent activity logging and add unit tests 📝
- _(tests)_ Add SQL tools and integration tests 🎉

### 🐛 Bug Fixes

- _(memory)_ Correct array keys to prevent improper payload handling
- _(models)_ Update relationship in Message to use AgentMemory instead of Assistant
- _(memory)_ Ensure proper formatting of message payloads with newlines
- _(BaseTool)_ Correct class name retrieval in log method
- _(FirecrawlService)_ Improve extraction prompt for content relevance
- _(logging)_ Remove redundant results logging in tools and set default context in BaseTool
- _(FirecrawlService)_ Improve clarity of extraction prompt
- _(FirecrawlService)_ Improve clarity of extraction prompt
- _(FirecrawlService)_ Correct grammar in extraction prompt for clarity
- _(tests)_ Remove unused Serper service configuration in TestCase
- _(views)_ Correct order of includes and message sorting
- _(OpenAI)_ Remove debugging code and correct response data handling in EmbeddingsRequest
- _(agent)_ Add memory and integration resolver exception handling 🚀
- _(memory)_ Correct input key and debug statements in CollectionMemoryTest
- _(agent)_ Correct middleware method for tool initialization
- _(agent)_ Correct middleware method for tool initialization
- _(tests)_ Remove outdated memory fixture files 🗑️
- _(tests)_ Update assertions for messages and standalone_question

### 🚜 Refactor

- _(agent)_ Streamline agent implementation, enhance OpenAI integration
- _(core)_ Update tool call handling and Google search feature
- _(memory)_ Implement new agent executor, enhance tool handling and OpenAI integration
- _(SystemPrompts)_ Convert BaseSystemPrompt to abstract class and clean up imports in tests
- _(prompts)_ Standardize prompt architecture and update invocation flow
- _(core)_ Remove obsolete classes and decouple concerns
- _(Message)_ Rename MessageValueObject to Message
- _(messages)_ Update prompt syntax and improve parsing logic
- _(SerperService)_ Inject apiKey via constructor, enhance SearchGoogleTool output format
- _(output)_ Convert Message object to array for prompt handling
- _(api)_ Replace Message with Response for better encapsulation
- _(memory)_ Split memory into two formats, update prompts to use new memory format
- _(models)_ Remove unused Eloquent models
- _(Message)_ Flatten tool attributes in value object and remove unused method
- _(tools)_ Extract common logic to BaseTool and remove unused method from Tool contract
- _(events)_ Rename model event methods to agent event methods and update related logic
- _(FirecrawlTool)_ Remove unused imports and adjust indentation
- _(logging)_ Consolidate logging logic into BaseTool class
- _(SimpleAgent)_ Replace SearchGoogleTool with SerperTool
- _(search)_ Streamline SerpAPIService parameter handling and update method signature
- _(test)_ Remove unused 'serper' config from TestCase setup
- _(migrations)_ Change `agent_memory_id` to UUID in `messages` table migration
- _(tools)_ Simplify query parameter descriptions in search tools
- _(SynapseServiceProvider)_ Replace publishesMigrations with loadMigrationsFrom
- _(tools)_ Standardize API key retrieval using config files
- _(config)_ Restructure API key and model configurations
- _(openai)_ Replace direct integration calls with Saloon for OpenAI interactions
- _(serper)_ Modularize Serper integration and update usage
- _(services)_ Modularize ClearbitService into separate request and connector classes
- _(crunchbase)_ Modularize and enhance Crunchbase integration
- _(Firecrawl)_ Modernize Firecrawl service architecture
- _(serpapi)_ Integrate new SerpApiConnector, remove deprecated service, and enhance API key handling
- _(SerpAPI)_ Streamline Google News tool handling; update tests and fixtures
- _(integrations)_ Reorganize OpenAI integration structure
- _(tests)_ Remove redundant `only` call in ClaudeIntegrationTest
- _(connectors/claude)_ Remove unused import in ValidateOutputRequest
- _(integrations)_ Update message formatting and tool handling
- _(integrations)_ Relocate Integration contract for better organization
- _(ValueObjects)_ Update comment to accurately reflect the Message validator
- _(agent)_ Streamline constructor and optimize methods for clarity and efficiency
- _(integrations)_ Update docblocks in HasIntegration.php for clarity
- _(memory)_ Reorganize memory methods and improve doc comments
- _(memory)_ Improve method visibility and documentation in memory classes
- _(logging)_ Consolidate logging functionality into HasLogging trait
- _(models)_ Reorganize memory-related models to improve structure
- _(tests)_ Relocate and update MultiQueryRetrieverAgent tests for improved structure and mocking
- _(agent)_ Remove ProfessionalEditorAgent and associated tests and resources
- _(SearchAndScrapeAgent)_ Remove deprecated agent and related files
- _(codebase)_ Optimize null/empty checks and type hints across various classes
- _(codebase)_ Optimize null/empty checks and type hints across various classes
- _(src)_ Standardize variable names and signatures
- _(schema)_ Rename OutputRules to OutputSchema across the codebase
- _(tests)_ Reorganize CollectionMemoryTest directory structure
- _(agent)_ Enhance PendingAgentTask and integration handling
- _(agents)_ Restructure traits for better organization and functionality
- _(integrations)_ Remove unused Message import and method
- _(agent)_ Move Agent class to base namespace 🛠️
- _(project)_ Restructure namespaces and traits for consistency ♻️
- _(agent)_ Standardize namespaces and manage memory/tools
- _(value-objects)_ Reorganize Message and EmbeddingResponse namespaces 🔄
- _(memory)_ Move Memory contract to new namespace ♻️
- _(integrations)_ Relocate `Response` value object to new namespace ♻️
- _(integration)_ Rename OpenAiIntegration to OpenAIIntegration and update methods
- _(integration)_ Rename OpenAiIntegration to OpenAIIntegration and update methods
- _(agent)_ Rename traits for clarity 🌟
- _(models)_ 🦊 relocate models to a new namespace for better organization
- _(tools)_ Remove ToolCallValueObject and update references 💅
- _(tool)_ Modify BaseTool to initialize with PendingAgentTask
- _(tools)_ Improve tool handling and integration
- _(agent)_ Rename Agent namespace to AgentTask for clarity
- _(agents)_ Move agent classes from Templates to Agents namespace
- _(agent)_ Streamline integration management and tool handling
- _(memory)_ Unify memory management and enhance tool initialization 🌟✨
- _(agent/memory)_ Synchronize memory management with middleware pipeline and integrate new interfaces 🧠
- _(tests)_ Rename fixture files for OpenAi integration tests for better organization
- _(tests)_ Streamline fixture paths and remove unused imports
- _(SerpAPIGoogleSearchTool)_ Streamline API key handling and enhance tests
- _(SerpAPIGoogleNewsTool)_ Streamline API key handling and enhance test coverage
- _(firecrawl)_ Update endpoint and extraction schema handling
- _(CrunchbaseTool)_ Simplify initialization and enhance boot method
- _(clearbit)_ Streamline ClearbitCompanyTool logic, update tests
- _(Message)_ Update content return type to mixed
- _(agent-hooks)_ Split and enhance hook interfaces, add tests🐛
- _(agent-hooks)_ Split and enhance hook interfaces, add tests🐛
- _(memory)_ Remove deprecated memory trait and methods 💾
- _(tools)_ Streamline API key handling and remove unnecessary boot method 🔧
- _(tools)_ Streamline API key handling and remove unnecessary boot method 🔧
- _(KnowledgeGraphExtractionAgent)_ Implement HasOutputSchema interface and update schema validation 🎯
- _(command)_ Streamline SynapseInstall.php structure

### 📚 Documentation

- _(getting-started)_ Add installation guide for Laravel Synapse
- _(Response)_ Correct annotation for validationRules method
- _(agent)_ Add detailed docblocks for methods
- _(ClearbitCompanyTool)_ Add missing docblocks and exceptions, improve method annotations
- _(OutputRule)_ Add PHPDoc comments for better code clarity and documentation
- _(SynapseServiceProvider)_ Enhance boot method docblock for clarity and detail
- _(vitepress)_ Add initial VitePress configuration for documentation
- _(agents)_ Add comprehensive documentation for agent memory and collection memory 🍱
- _(agent-traits)_ Add `HasHooks` contract and `ManagesHooks` trait ✍️
- Reorganize and add new sections to documentation 📄
- _(prompt)_ Enhance documentation with new prompt parts and examples ✍️
- _(agents)_ Add integration setup documentation for OpenAI and Claude 🚀
- _(prompts)_ Enhance documentation for clarity and consistency ✨📚
- _(traits)_ Add documentation for events and log traits 📝
- _(agent-lifecycle)_ Fix broken link to message tags 📚
- _(license)_ Add MIT License to the repository 📄

### 🎨 Styling

- _(codebase)_ Adhere to strict type declarations and coding standards
- _(output-rules)_ Refine prompt formatting for better readability
- _(provider)_ Specify return type and update doc comment in SynapseServiceProvider
- _(docblocks)_ Add comprehensive docblocks for constructors and methods
- _(codebase)_ Improve code formatting and consistency across files
- _(agent, tests)_ ⭐️ Ensure consistent code indentation 🌟

### 🧪 Testing

- _(agent)_ Add SearchAndScrapeAgentTest and cleanup existing tests
- _(ImageAgent)_ Add basic test structure for scraping functionality
- _(SerperTool)_ Enhance coverage and restructure tests with mocking
- _(OpenAiIntegration)_ Add test for Connects With OutputSchema
- _(memory)_ Update and reorganize CollectionMemory test fixtures
- _(ImageAgent)_ Add new fixture for image agent test 🤖
- _(memory)_ Add new CollectionMemory test and fixture 🚀

### ⚙️ Miscellaneous Tasks

- _(dependencies)_ Remove unused dependencies 'saloonphp/saloon' and 'spatie/laravel-data'
- _(tests)_ Restructure OpenAiIntegrationTest.php directory
- _(config)_ Add .editorconfig for consistent code formatting
- _(secrets)_ Update encrypted environment variables
- _(docs)_ Add GitHub Actions workflow to deploy VitePress site to Pages 🚀
- _(ci)_ Cleanup workflow and add funding configuration 🧹
- _(workflows)_ Add GitHub Actions workflow for running tests 🚀
- _(workflow)_ Add GitHub Action to automate changelog updates upon release 🚀
- _(workflows)_ Update CI config to use PHP 8.2 and streamline testing process 🛠
- _(workflow)_ Add environment API keys for workflows 🛠️

<!-- generated by git-cliff -->
