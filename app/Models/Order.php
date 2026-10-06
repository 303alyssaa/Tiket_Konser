<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    // Data event, tiket, dan metode pembayaran. Ubah di sini kalau mau ganti.
    const EVENT = [
        'nama'    => 'Beli Tiket Konser Anda Disini',
        'lokasi'  => 'Surabaya',
        'tanggal' => '7-8 Nov 2030',
    ];

    const TIKET = [
        'konser Boynextdoor' => ['nama' => 'Konser BoyNextDoor',     'harga' => 1250000],
        'konser NIKI'     => ['nama' => 'Konser NIKI', 'harga' => 225000],
        'konser Tulus'     => ['nama' => 'Konser Tulus', 'harga' => 250000],
    ];

    const METODE = [
        'qris'    => 'QRIS',
        'bca'     => 'Transfer Bank BCA',
        'mandiri' => 'Transfer Bank Mandiri',
        'gopay'   => 'GoPay',
    ];

    // URL pakai kode pesanan, bukan id
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function getNamaTiketAttribute(): string
    {
        return self::TIKET[$this->tiket]['nama'];
    }
}
