<?php
/**
 * Created by PhpStorm.
 * User: jitheshgopan
 * Date: 28/03/15
 * Time: 4:28 AM
 */

class AdminCategoriesController extends AdminBaseController{

    public function view() {
        $grid = DataGrid::source('categories');  //same source types of DataSet
        $grid->add('name','Name', true);
        $grid->edit(route('iluvcricketCategoriesAddEdit'), 'Actions','modify|delete');
        return View::make('iluvcricket.categories', compact('grid'));
    }

    public function addEdit() {
        $edit = DataEdit::source(new Category());
        if(!Input::get('modify'))
            $edit->label('Create Category');
        else
            $edit->label('Edit Category');
        $edit->add('name','Name', 'text')->rule('required|min:3');
        $edit->add('meta_title','Meta title', 'text')->rule('required|min:3');
        $edit->add('meta_description','Meta description', 'textarea')->rule('required|min:3');
        //$edit->add('slug','Url slug', 'text')->rule('required|min:3');
        $edit->build();
        return $edit->view('iluvcricket.createEditCategory', compact('edit'));
    }
}
