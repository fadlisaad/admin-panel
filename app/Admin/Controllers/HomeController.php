<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use App\Models\Agency;
use DB;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $agency = Agency::all()->count();
        $box = new Box('Box Title', $agency);
        $box->removable();
        $box->collapsable();
        $box->style('info');
        $box->solid();
            
        return $content
            ->title('Dashboard')
            ->description('Overview')
            ->withInfo('Welcome', 'This is the dashboard view for '.config('site_title'))
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });

            });
    }
}
