<?php namespace App\Controllers\Admin;

use Categories;
use BaseController;
use Validator;
use Redirect;
use Input;
use DB;

class CategoriesController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function list_categories()
	{
		return DB::table('gutlo_categories')->select('id','name as text')->get();
	}

	public function newCategory () {
		$dataInput = array(
			'name' => Input::get('name')
		);

		$rule = array(
			'name'	=> 'required'
		);
		$msg = array(
			'name'	=> 'không được để trống'
		);

		$validate = Validator::make($dataInput,$rule,$msg);

		if($validate->fails()){
			return Redirect::back()->withErrors($validation)->withInput();
		}else {
			$Category = new Categories();
			$Category->name = $dataInput['name'];
			$Category->save();

			return $this->list_categories();
		}
	}

}
