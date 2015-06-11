<?php namespace Shaphira\Composers\navigator;

use User;
use GutloPoint;
use GutloRank;
use Media;
use DB;
use CategoryController;
use Cache;

class CategoriesComposers {

	function compose($view) {
		$CategoryController = new CategoryController();
		$categories = $CategoryController->cache_catgories();

		$view->with('categories', $categories);
	}
}