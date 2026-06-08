<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderByDesc('id')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'       => 'required|string|unique:coupons,code|max:30',
            'type'       => 'required|in:percent,fixed,frete',
            'value'      => 'required|numeric|min:0',
            'min_value'  => 'nullable|numeric|min:0',
            'max_uses'   => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create([
            'code'       => strtoupper($request->code),
            'type'       => $request->type,
            'value'      => $request->value,
            'min_value'  => $request->min_value ?? 0,
            'max_uses'   => $request->max_uses ?? 100,
            'expires_at' => $request->expires_at,
            'active'     => true,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupom criado!');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['active' => !$coupon->active]);
        return back()->with('success', 'Cupom atualizado!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Cupom excluído!');
    }
}
