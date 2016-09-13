<?php

require __DIR__ . '/../vendor/autoload.php';

$path = ['./views']; 
$cachePath = './cache';

$compiler = new \CmlExt\Blade\BladeCompiler($cachePath);


$compiler->directive('datetime', function ($timestamp) {
    return preg_replace('/(\(\d+\))/', '<?php echo date("Y-m-d H:i:s", $1); ?>', $timestamp);
});

$finder = new \CmlExt\Blade\FileViewFinder($path);


$factory = new \CmlExt\Blade\Factory($compiler, $finder);


echo $factory->make('hello', ['name' => 'test'])->render();
