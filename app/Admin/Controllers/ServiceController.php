<?php

namespace App\Admin\Controllers;

use App\Models\Service;
use App\Models\Agency;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ServiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Services';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Service());

        $grid->column('code', __('Code'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('agency.name', __('Agency'));
        $grid->column('enabled', __('Enabled'))->bool();
        $grid->column('created_at')->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        $grid->filter(function($filter){

            // Remove the default id filter
            $filter->disableIdFilter();
        
            // Add a column filter
            $filter->like('code', 'Code');
            $filter->like('name', 'Name');
            $filter->equal('agency_id', __('Agency'))->select(Agency::all()->pluck('name','id'));
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Service::findOrFail($id));

        $show->field('code', __('Code'));
        $show->field('name', __('Name'));
        $show->field('enabled', __('Enabled'))->using(['0' => 'No', '1' => 'Yes']);
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->agency('Agency Details', function ($agency) {

            $agency->setResource('/admin/agencies');
            $agency->code();
            $agency->name();
            $agency->email();

            $agency->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });
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
        $form = new Form(new Service());

        $form->text('code', __('Code'))->required();
        $form->text('name', __('Name'))->required();
        $form->select('agency_id', __('Agency'))->options(Agency::all()->pluck('name','id'))->required();
        $status = [
            'on'  => ['value' => 1, 'text' => 'Enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Disable', 'color' => 'danger'],
        ];
        
        $form->switch('enabled', __('Enabled'))->states($status)->required();

        return $form;
    }
}
