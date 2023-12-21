<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Agency;
use Carbon\Carbon;

class AgencyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Agencies';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Agency);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('code', __('Code'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('email', __('E-mail'));
        $grid->updated_at()->display(function ($updated_at) {
            return Carbon::parse($updated_at)->diffForHumans();
        });
        $grid->filter(function($filter){

            // Remove the default id filter
            $filter->disableIdFilter();
        
            // Add a column filter
            $filter->like('code', 'Code');
            $filter->like('name', 'Name');
        
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Agency::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('code', __('Code'));
        $show->field('name', __('Name'));
        $show->field('email', __('E-mail'));
        $show->field('url', __('URL'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->services('Services', function ($services) {

            $services->resource('/admin/services');
        
            $services->id();
            $services->code();
            $services->name();

            $services->disableFilter();
            $services->disableCreateButton();
            $services->disablePagination();
            $services->disableExport();
            $services->disableActions();
            $services->disableColumnSelector();
            $services->disableRowSelector();
        
        });

        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Agency);

        $form->display('id', __('ID'));
        $form->text('code', __('Code'));
        $form->text('name', __('Name'));
        $form->text('email', __('E-mail'));
        $form->url('url', __('URL'));

        return $form;
    }
}
