<?php
require("resultObj.php");
if (count($argv) < 4) {
	echo "Not enough arguments\n";
	exit(1);
}
if (!file_exists($argv[1])) {
	echo "File does not exist\n";
	exit(1);
}
try {
	$tmpDir = tempnam(sys_get_temp_dir(), "SYNOAUM_");
	unlink($tmpDir);
	mkdir($tmpDir);
	copy($argv[1], $tmpDir . "/lyric.aum");
	chdir($tmpDir);
	$p = new PharData("lyric.aum");
	$p->decompress();
	$phar = new PharData("lyric.tar");
	$phar->extractTo(".");
	$info = json_decode(file_get_contents("INFO"));
	require($info->module);
	$instance = (new ReflectionClass($info->class))->newInstance();
	$result = new LyricResult();
	if ($instance->getLyricsList($argv[3], $argv[2], $result) > 0) {
		foreach ($result->list as $k => $v) {
			printf("Result %d, artist: %s, title: %s.\n", $k, $v["artist"], $v["title"]);
			$instance->getLyrics($v["id"], $result);
		}
	} else {
		echo "Cannot find any lyric\n";
	}
	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(".", RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
		if ($file->isDir()) {
			rmdir($file->getRealPath());
		} else {
			unlink($file->getRealPath());
		}
	}
	rmdir($tmpDir);
} catch (Exception $e) {
	echo "Runtime error: ", $e->getMessage(), "\n";
	exit(1);
}
?>
