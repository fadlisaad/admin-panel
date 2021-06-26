<?php

namespace App\Admin\Controllers;

use App\Models\Transaction;
use App\Models\Service;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use DB;

class TransactionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Transaction';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Transaction());

        $grid->model()->whereNotNull('receipt_no')->orderBy('id', 'desc');
        $grid->column('receipt_no', __('Receipt no'));
        $grid->column('service.name', __('Service'));
        $grid->column('amount', __('Amount'));
        $grid->column('payment_method', __('Payment Method'));
        $grid->column('status', __('Status'))->bool();
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        $grid->filter(function($filter){

            // Remove the default id filter
            $filter->disableIdFilter();
        
            // Add a column filter
            $filter->like('receipt_no', 'Receipt No');
            $filter->like('payment_method', __('Payment Method'))->select(['FPX', 'Kad Kredit/Debit']);
            $filter->equal('service_id', __('Service'))->select(Service::all()->pluck('name','id'));
        
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });

        $grid->disableCreateButton();

        $grid->header(function ($query) {
            $method = $query->select(DB::raw('count(payment_method) as count, payment_method'))
                ->groupBy('payment_method')->get()->pluck('count', 'payment_method')->toArray();
            $doughnut = view('admin.payment-type', compact('method'));
            return new Box('Payment Menthod', $doughnut);
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
        $show = new Show(Transaction::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('amount', __('Amount'));
        $show->field('payment_mode', __('Payment mode'));
        $show->field('payment_method', __('Payment method'));
        $show->field('status', __('Status'))->using(['0' => 'Failes', '1' => 'Success']);
        $show->field('receipt_no', __('Receipt no'));
        $show->field('payment_id', __('Payment id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->service(__('Service'), function ($service){
            $service->name();
            $service->code();
            $service->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });
        });

        $show->payment('Payment Details', function ($payment) {
            $payment->status_code();
            $payment->status_message();
            $payment->payment_transaction_id();
            $payment->payment_datetime();
            $payment->buyer_bank();
            $payment->buyer_name();
            $payment->merchant_order_no();
            $payment->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
                $tools->disableList();
            });
        });

        $show->details('Transaction Details', function ($details) {
            $details->key();
            $details->value();

            $details->disableFilter();
            $details->disableCreateButton();
            $details->disablePagination();
            $details->disableExport();
            $details->disableActions();
            $details->disableColumnSelector();
            $details->disableRowSelector();
        });

        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableDelete();
            $tools->disableList();
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
        $form = new Form(new Transaction());

        $form->number('user_id', __('User id'));
        $form->number('service_id', __('Service id'));
        $form->text('amount', __('Amount'))->default('double');
        $form->text('payment_mode', __('Payment mode'))->default('string');
        $form->text('payment_method', __('Payment method'))->default('string');
        $form->switch('status', __('Status'))->default(2);
        $form->text('receipt_no', __('Receipt no'));
        $form->number('payment_id', __('Payment id'));

        return $form;
    }
}
