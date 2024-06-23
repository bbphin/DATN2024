<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestPaymentController extends Controller
{
    public function index()
    {
        return view('payments.index');
    }
    public function show()
    {
        return view('payment.index');
    }
}
