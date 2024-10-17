<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Helpers;

use Closure;
use ReflectionClass;
use UseTheFork\Synapse\AgentTask\PendingAgentChain;
use UseTheFork\Synapse\AgentTask\PendingAgentTask;

/**
 * @internal
 *
 * credits to https://github.com/saloonphp/saloon/blob/v3/src/Helpers/Helpers.php
 */
final class Helpers
{
    /**
     * Boot a plugin
     *
     * @param  class-string  $trait
     *
     * @throws \ReflectionException
     */
    public static function bootPlugin(PendingAgentTask|PendingAgentChain $pendingAgentTask, string $trait): void
    {
        $agent = $pendingAgentTask->agent();

        $traitReflection = new ReflectionClass($trait);

        $bootMethodName = 'boot'.$traitReflection->getShortName();

        if (! method_exists($agent, $bootMethodName)) {
            return;
        }

        $agent->{$bootMethodName}($pendingAgentTask);
    }

    /**
     * Returns all interfaces implemented by a class, its parent classes and interfaces of their interfaces.
     *
     * @param  object|class-string  $class
     * @return array<class-string, class-string>
     */
    public static function classImplementsRecursive(object|string $class): array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        // Get interfaces from the class and its parent classes
        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
            $results += self::interfaceImplementsRecursive($class);
        }

        return array_unique($results);
    }

    /**
     * Returns all interfaces implemented by a class and interfaces of their interfaces.
     *
     * @param  class-string  $class
     * @return array<class-string, class-string>
     */
    public static function interfaceImplementsRecursive(string $class): array
    {
        /** @var array<class-string, class-string> $interfaces */
        $interfaces = class_implements($class) ?: [];

        // Get interfaces of interfaces
        foreach ($interfaces as $interface) {
            $interfaces += self::interfaceImplementsRecursive($interface);
        }

        return $interfaces;
    }

    /**
     * Returns all traits used by a class, its parent classes and trait of their traits.
     *
     * @param  object|class-string  $class
     * @return array<class-string, class-string>
     */
    public static function classUsesRecursive(object|string $class): array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
            $results += self::traitUsesRecursive($class);
        }

        return array_unique($results);
    }

    /**
     * Returns all traits used by a trait and its traits.
     *
     * @param  class-string  $trait
     * @return array<class-string, class-string>
     */
    public static function traitUsesRecursive(string $trait): array
    {
        /** @var array<class-string, class-string> $traits */
        $traits = class_uses($trait) ?: [];

        foreach ($traits as $trait) {
            $traits += self::traitUsesRecursive($trait);
        }

        return $traits;
    }


    /**
     * Remove any common leading whitespace from every line in `text`.
     *
     * This can be used to make multi-line strings line up with the left edge of
     * the display while still being indented in the source code.
     *
     * Tabs and spaces are treated as whitespace, but they are not equal.
     * Blank lines are normalized to a newline character.
     *
     * adapted from https://github.com/python/cpython/blob/3.13/Lib/textwrap.py
     */
    public static function dedent(string $text) {
        // Define regular expressions
        $whitespaceOnlyPattern = '/^[ \t]+$/m';
        $leadingWhitespacePattern = '/(^[ \t]*)(?:[^ \t\n])/m';

        // Remove lines that only contain whitespace
        $text = preg_replace($whitespaceOnlyPattern, '', $text);

        // Find all leading whitespaces
        preg_match_all($leadingWhitespacePattern, $text, $matches);
        $indents = $matches[1];
        $margin = null;

        // Determine the common leading whitespace (if any)
        foreach ($indents as $indent) {
            if ($margin === null) {
                $margin = $indent;
            } elseif (strpos($indent, $margin) === 0) {
                // Current line more deeply indented, no change
            } elseif (strpos($margin, $indent) === 0) {
                // Current line has less or equal indentation, it's the new margin
                $margin = $indent;
            } else {
                // Find the common prefix between the current indent and margin
                for ($i = 0; $i < strlen($margin) && $i < strlen($indent); $i++) {
                    if ($margin[$i] !== $indent[$i]) {
                        $margin = substr($margin, 0, $i);
                        break;
                    }
                }
            }
        }

        // If we have a margin, remove it from all lines
        if ($margin) {
            $text = preg_replace('/^' . preg_quote($margin, '/') . '/m', '', $text);
        }

        return $text;
    }

    /**
     * Check if a class is a subclass of another.
     *
     * @param  class-string  $class
     */
    public static function isSubclassOf(string $class, string $subclass): bool
    {
        if ($class === $subclass) {
            return true;
        }

        return (new ReflectionClass($class))->isSubclassOf($subclass);
    }

    /**
     * Return the default value of the given value.
     */
    public static function value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }

}
