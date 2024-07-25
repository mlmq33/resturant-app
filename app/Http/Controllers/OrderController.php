<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {   
        $filledTables = User::with(['order', 'order.menuOption'])
            ->where('role_id', '3')
            ->get();


        return view('order.index', [
            'filledTables' => $filledTables,
        ]);
    }

    public function complete($id)
    {
        $order = Transaction::where('user_id', $id)
            ->where('completion_status', 'no')
            ->update(['completion_status' => 'yes']);

        if(auth()->user()->hasRole('admin')){
            return redirect()
            ->route('home.admin')
            ->with('success','Order successfully marked as completed');
        }
        
        elseif(auth()->user()->hasRole('staff')){
            return redirect()
            ->route('home.staff')
            ->with('success','Order successfully marked as completed');
        }
    }

    public function paid($id)
    {
        $order = Transaction::where('user_id', $id)
            ->where([
                ['payment_status', 'no'],
            ])
            ->update([
                'payment_status' => 'yes',
                'completion_status' => 'yes',
            ]);

        if(auth()->user()->hasRole('admin')){
            return redirect()
            ->route('home.admin')
            ->with('success','Order successfully marked as paid');
        }
        
        elseif(auth()->user()->hasRole('staff')){
            return redirect()
            ->route('home.staff')
            ->with('success','Order successfully marked as paid');
        }
    }

    public function show($id)
    {   
        $tableNo = User::find($id);
        
        $orders = Transaction::with(['menu', 'menu.category', 'menuOption'])
            ->where('user_id', $id)
            ->where('payment_status', 'no')
            ->get();

        $remark = Transaction::where('user_id', $id)
            ->where('payment_status', 'no')
            ->where('remarks', '<>', '')
            ->first();

        // dd($remark);

        return view('order.show', [
            'tableNo' => $tableNo,
            'orders' => $orders,
            'remark' => $remark,
        ]);
    }

    public function completeSingleOrder($id)
    {

        $order = Transaction::find($id);
        $order->completion_status = "yes";
        $order->save();

        return redirect()
            ->route('order.show', $order->user_id)
            ->with('success','Order successfully marked as complete');
        
    }

    public function cancelSingleOrder($id)
    {

        $order = Transaction::find($id);
        $order->delete();

        return redirect()
            ->route('order.show', $order->user_id)
            ->with('success','Order successfully canceled');
        
    }
}