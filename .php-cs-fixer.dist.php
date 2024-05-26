<?php

declare(strict_types=1);

use PhpCsFixer\Config;

$rules = [
    '@Symfony' => true,
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    'array_syntax' => ['syntax' => 'short'],
    'concat_space' => ['spacing' => 'one'],
    'declare_strict_types' => true,
    'no_unused_imports' => true,
    'no_superfluous_phpdoc_tags' => true,
    'ordered_imports' => ['sort_algorithm' => 'length'],
    'phpdoc_order' => true,
    'trailing_comma_in_multiline' => true,
    'multiline_whitespace_before_semicolons' => false,
    'phpdoc_to_comment' => [
        'ignored_tags' => [
            'var', 'todo', 'property', 'throws', 'noinspection', 'mixin', 'property-read', 'method', 'param',
        ],
    ],
    'ordered_class_elements' => ['order' => ['use_trait', 'constant_public', 'constant_protected', 'constant_private', 'case', 'property_public', 'property_protected', 'property_private', 'construct', 'destruct', 'magic', 'phpunit', 'method_public', 'method_protected', 'method_private']],
    'curly_braces_position' => true,
    'yoda_style' => ['equal' => true, 'identical' => true, 'less_and_greater' => true],
    'echo_tag_syntax' => false,
    'semicolon_after_instruction' => false,
    'fully_qualified_strict_types' => true,
    'global_namespace_import' => true,
    'increment_style' => false,
];
$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'vendor', 'public'])
    ->name('*.php')
    ->notName('*_generated.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setLineEnding("\n")
    ->setFinder($finder);
