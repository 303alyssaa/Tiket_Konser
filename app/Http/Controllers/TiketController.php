<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TiketController extends Controller
{
    // Langkah 1: pilih kategori
    public function pilih()
    {
        return view('tiket', ['step' => 1, 'event' => Order::EVENT, 'tiket' => Order::TIKET]);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'tiket' => ['required', Rule::in(array_keys(Order::TIKET))],
            'qty'   => ['required', 'integer', 'min:1', 'max:5'],
        ], [
            'tiket.required' => 'Pilih salah satu kategori tiket dulu.',
        ]);

        $order = Order::create([
            'code'  => 'ATX-' . strtoupper(Str::random(8)),
            'tiket' => $data['tiket'],
            'qty'   => $data['qty'],
            'total' => Order::TIKET[$data['tiket']]['harga'] * $data['qty'],
        ]);

        return redirect()->route('pesan.detail', $order);
    }

    // Langkah 2: detail pesanan
    public function detail(Order $order)
    {
        return view('tiket', ['step' => 2, 'order' => $order]);
    }

    public function simpanDetail(Request $request, Order $order)
    {
        $order->update($request->validate([
            'nama'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
        ]));

        return redirect()->route('pesan.metode', $order);
    }

    // Langkah 3: metode pembayaran
    public function metode(Order $order)
    {
        return view('tiket', ['step' => 3, 'order' => $order, 'metode' => Order::METODE]);
    }

    public function simpanMetode(Request $request, Order $order)
    {
        $order->update($request->validate([
            'metode' => ['required', Rule::in(array_keys(Order::METODE))],
        ], [
            'metode.required' => 'Pilih metode pembayaran dulu.',
        ]));

        return redirect()->route('pesan.bayar', $order);
    }

    // Langkah 4: pembayaran
    public function bayar(Order $order)
    {
        return view('tiket', [
            'step'   => 4,
            'order'  => $order,
            'event'  => Order::EVENT,
            'metode' => Order::METODE,
        ]);
    }
}