<?php namespace Shaphira\Composers\sidebar\news;

use Tag;

class TagsComposers {

	function compose($view) {

		$tags = new Tag();
        $items = $tags->orderBy('index', 'desc')->take(20)->get();
        $view->with('tags', $items);
	}
}

