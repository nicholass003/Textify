<?php

/*
 * Copyright (c) 2024 - present nicholass003
 *   _______        _   _  __
 *  |__   __|      | | (_)/ _|
 *     | | _____  _| |_ _| |_ _   _
 *     | |/ _ \ \/ / __| |  _| | | |
 *     | |  __/>  <| |_| | | | |_| |
 *     |_|\___/_/\_\ __|_|_|  \__, |
 *                             __/ |
 *                            |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  nicholass003
 * @link    https://github.com/nicholass003/
 *
 *
 */

declare(strict_types=1);

$outputPath = __DIR__ . '/../builds/Textify.phar';
$basePath = realpath(__DIR__ . '/..');

if(!file_exists("$basePath/virion.yml")){
	die("Error: virion.yml not found.\n");
}

$virionConfig = yaml_parse_file("$basePath/virion.yml");

if(!isset($virionConfig['name']) || !isset($virionConfig['version'])){
	die("Error: virion.yml must contain 'name' and 'version'.\n");
}

if(!is_dir("$basePath/builds")){
	mkdir("$basePath/builds", 0777, true);
}

if(file_exists($outputPath)){
	unlink($outputPath);
}

$phar = new Phar($outputPath);
$phar->startBuffering();

$directory = new RecursiveDirectoryIterator("$basePath/src", RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($directory);

function getNamespaceFromFile(string $filePath) : ?string{
	$namespace = null;
	$handle = fopen($filePath, 'r');
	if($handle){
		while(($line = fgets($handle)) !== false){
			if(preg_match('/^namespace\s+(.+?);$/', trim($line), $matches)){
				$namespace = $matches[1];
				break;
			}
		}
		fclose($handle);
	}
	return $namespace;
}

foreach($iterator as $file){
	if($file->isFile() && pathinfo($file->getPathname(), PATHINFO_EXTENSION) === 'php'){
		$namespace = getNamespaceFromFile($file->getPathname());
		$className = basename($file->getPathname());

		if($namespace !== null){
			$namespacePath = str_replace("\\", "/", $namespace);
			$pharPath = "src/$namespacePath/$className";
		}else{
			$relativePath = str_replace("$basePath/src/", "", $file->getPathname());
			$pharPath = "src/$relativePath";
		}

		$phar->addFile($file->getPathname(), $pharPath);
		echo "Added {$file->getPathname()} as $pharPath\n";
	}
}

$phar->addFile("$basePath/virion.yml", "virion.yml");

$phar->setStub("<?php __HALT_COMPILER(); ?>");

$phar->stopBuffering();

echo "Build completed: $outputPath\n";
