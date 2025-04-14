<?php

namespace App\Interfaces;

use App\Models\Report;
use Illuminate\Http\Request;

interface ReportRepositoryInterface
{
    public function create(Request $request) : Report;
}
