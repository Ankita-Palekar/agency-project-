<?php

class moviesController extends Controller {
	function movies_list(){
		$opts = Array('language' => 'hindi', 'genre' => 'reality', 'sort' => 'release_date.desc');
		$result = Movie::filter($opts);
		return $result;
	}
}
