<?php

declare(strict_types=1);

    use UseTheFork\Synapse\Helpers\Helpers;

    test('dedent method should correctly dedent text with common leading whitespace', function () {
    $text = '
    This is a test
        This line is indented
    This line is not indented';

    $expected = "\nThis is a test\n    This line is indented\nThis line is not indented";

    $result = Helpers::dedent($text);

    expect($result)->toBe($expected);
});

test('dedent method should correctly dedent text with common leading whitespace using tab', function () {
    $text = "\t\t\tThis is a test
\t\t\t\tThis line is indented
\t\t\tThis line is not indented";

    $expected = "This is a test\n\tThis line is indented\nThis line is not indented";


    $result = Helpers::dedent($text);

    expect($result)->toBe($expected);
});

test('dedent method should correctly dedent text with no common leading whitespace', function () {
    $text = '
This is a test
    This line is indented
This line is not indented';

    $expected = "\nThis is a test\n    This line is indented\nThis line is not indented";

    $result = Helpers::dedent($text);

    expect($result)->toBe($expected);
});

test('dedent method should return the same text if no leading whitespace is present', function () {
    $text = "\nThis is a test\nThis line is not indented\nThis line is not indented";

    $expected = $text;

    $result = Helpers::dedent($text);

    expect($result)->toBe($expected);
});
