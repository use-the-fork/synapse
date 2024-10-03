<?php

declare(strict_types=1);

//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Traits\Agent;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionParameter;
use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Tool;

/**
 * Trait HasTools
 *
 * @author Hermann D. Schimpf (hschimpf)
 * Refer https://github.com/openai-php/client/issues/285#issuecomment-1883895076
 */
trait ManagesTools
{
    protected array $registeredTools = [];

    protected array $tools = [];

    /**
     * Registers the tools.
     *
     * @return array<Tool> The registered tools.
     */
    protected function resolveTools(): array
    {
        return [];
    }

    /**
     * Calls a registered tool with the given name and arguments.
     *
     * @param  string  $toolName  The name of the tool to call.
     * @param  array|null  $arguments  The arguments to pass to the tool.
     * @return mixed The result of calling the tool, or null if the tool is not registered.
     *               If a required parameter is missing, a string error message is returned.
     *               If the parameter type is an enum, it attempts to fetch a valid value,
     *               using the provided argument or the parameter's default value.
     *
     * @throws ReflectionException
     */
    public function call(PendingAgentTask $pendingAgentTask, string $toolName, ?array $arguments = []): mixed
    {
        if (null === $toolClass = $pendingAgentTask->tools()[$toolName]) {
            return null;
        }
        $tool = $toolClass['tool'];

        $toolClass = new ReflectionClass($toolClass['tool']);
        $reflectionMethod = $toolClass->getMethod('handle');

        $params = [];
        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {

            //TODO: need to add relooping here.
            //            $parameter_description = $this->getParameterDescription($reflectionParameter);
            //            if (! array_key_exists($reflectionParameter->name, $arguments) && ! $reflectionParameter->isOptional() && ! $reflectionParameter->isDefaultValueAvailable()) {
            //                return sprintf('Parameter %s(%s) is required for the tool %s', $reflectionParameter->name, $parameter_description, $tool_name);
            //            }

            // check if parameter type is an Enum and add fetch a valid value
            if (($parameter_type = $reflectionParameter->getType()) !== null && ! $parameter_type->isBuiltin() && enum_exists($parameter_type->getName())) {
                $params[$reflectionParameter->name] = $parameter_type->getName()::tryFrom($arguments[$reflectionParameter->name]) ?? $reflectionParameter->getDefaultValue();

                continue;
            }

            $params[$reflectionParameter->name] = $arguments[$reflectionParameter->name] ?? $reflectionParameter->getDefaultValue();
        }

        return $tool->handle(...$params);
    }

    /**
     * Retrieves the type of the tool parameter.
     *
     * @param  ReflectionParameter  $reflectionParameter  The reflection parameter.
     * @return string The type of the tool parameter.
     */
    private function getToolParameterType(ReflectionParameter $reflectionParameter): string
    {
        if (null === $parameter_type = $reflectionParameter->getType()) {
            return 'string';
        }

        if (! $parameter_type->isBuiltin()) {
            return $parameter_type->getName();
        }

        return match ($parameter_type->getName()) {
            'bool' => 'boolean',
            'int' => 'integer',
            'float' => 'number',

            default => 'string',
        };
    }

    /**
     * Initializes the tools registered in the application.
     *
     * This method loops through the registered tools and performs the following tasks:
     * 1. Retrieves the reflection class of the tool.
     * 2. Generates the tool name based on the class name.
     * 3. Checks if the tool class has a "handle" method. If not, it logs a warning and continues to the next tool.
     * 4. Defines the tool function with its type and name.
     * 5. Sets the function description if available.
     * 6. Parses tool parameters if the "handle" method has parameters.
     * 7. Stores the registered tool with its definition and class.
     *
     * @throws ReflectionException
     */
    public function initializeTools(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {

        $lexer = new Lexer;
        $constExprParser = new ConstExprParser;
        $typeParser = new TypeParser($constExprParser);
        $phpDocParser = new PhpDocParser($typeParser, $constExprParser);

        foreach ($this->resolveTools() as $tool) {

            $reflection = new ReflectionClass($tool);

            $toolName = Str::snake(basename(str_replace('\\', '/', $tool::class)));

            if (! $reflection->hasMethod('handle')) {
                Log::warning(sprintf('Tool class %s has no "handle" method', $tool::class));

                continue;
            }

            $toolDefinition = [
                'type' => 'function',
                'function' => ['name' => $toolName],
            ];

            $tokens = new TokenIterator($lexer->tokenize($reflection->getMethod('handle')->getDocComment()));
            $phpDocNode = $phpDocParser->parse($tokens); // PhpDocNode

            //First we get the comment text block
            $textNodes = array_filter($phpDocNode->children, fn ($node): bool => $node instanceof PhpDocTextNode && ! empty($node->text));

            if ($textNodes !== []) {
                $toolDefinition['function']['description'] = (string) $textNodes[0];
            }

            if ($reflection->getMethod('handle')->getNumberOfParameters() > 0) {
                $paramTags = $phpDocNode->getParamTagValues();
                $toolDefinition['function']['parameters'] = $this->parseToolParameters($reflection, $paramTags);
            }

            $tool->boot($pendingAgentTask);

            $pendingAgentTask->addTool($toolName, [
                'definition' => $toolDefinition,
                'tool' => $tool,
            ]);

        }

        return $pendingAgentTask;
    }

    /**
     * Parses the parameters of a tool.
     *
     * @param  ReflectionClass  $reflectionClass  The tool reflection class.
     * @param  array<ParamTagValueNode>  $paramTagValueNode  The Param tags that will be used to get the description.
     * @return array The parsed parameters of the tool.
     *
     * @throws ReflectionException
     */
    private function parseToolParameters(ReflectionClass $reflectionClass, array $paramTagValueNode): array
    {
        $parameters = ['type' => 'object'];

        if (count($methodParameters = $reflectionClass->getMethod('handle')->getParameters()) > 0) {
            $parameters['properties'] = [];
        }

        foreach ($methodParameters as $methodParameter) {

            $property = ['type' => $this->getToolParameterType($methodParameter)];

            // set property description, if it has one
            if (($descriptions = array_filter($paramTagValueNode, fn (ParamTagValueNode $paramTagValueNode): bool => $paramTagValueNode->parameterName == '$'.$methodParameter->getName())) !== []) {
                $property['description'] = implode(
                    separator: "\n",
                    array: array_map(static fn (ParamTagValueNode $paramTagValueNode) => $paramTagValueNode->description, $descriptions),
                );
            }

            // register parameter to the required properties list if it's not optional
            if (! $methodParameter->isOptional()) {
                $parameters['required'] ??= [];
                $parameters['required'][] = $methodParameter->getName();
            }

            // check if parameter type is an Enum and add it's valid values to the property
            if (($parameter_type = $methodParameter->getType()) !== null && ! $parameter_type->isBuiltin() && enum_exists($parameter_type->getName())) {
                $property['type'] = 'string';
                $property['enum'] = array_column((new ReflectionEnum($parameter_type->getName()))->getConstants(), 'value');
            }

            $parameters['properties'][$methodParameter->getName()] = $property;
        }

        return $parameters;
    }

    public function bootManagesTools(PendingAgentTask $pendingAgentTask): void
    {
        $this->middleware()->onStartThread(fn () => $this->initializeTools($pendingAgentTask), 'initializeTools');
    }
}
