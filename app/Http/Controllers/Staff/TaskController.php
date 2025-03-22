<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function assigned()
    {
        return view('staff.tasks.assigned');
    }

    public function pending()
    {
        return view('staff.tasks.pending');
    }

    public function completed()
    {
        return view('staff.tasks.completed');
    }
}
//

