<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('orders')
            ->withSum('orders', 'total')
            ->orderByDesc('id')
            ->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }
}
