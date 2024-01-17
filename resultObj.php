<?php
class LyricResult {
	public $list = array();
	public function addTrackInfoToList($artist, $title, $id, $partialLyric) {
		array_push($this->list, array(
			"artist" => $artist,
			"title" => $title,
			"id" => $id,
			"partialLyric" => $partialLyric
		));
	}
	public function addLyrics($lyric, $id) {
		echo trim($lyric), "\n\n";
	}
}
?>
