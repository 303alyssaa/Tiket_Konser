<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beli Tiket Konser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #qty::-webkit-inner-spin-button, #qty::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        #qty { -moz-appearance: textfield; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">Beli Tiket Konser</a>
    </div>
</nav>

@php $langkah = ['Pilih Kategori', 'Detail Pesanan', 'Metode Pembayaran', 'Pembayaran']; @endphp
<div class="bg-primary">
    <div class="container py-2 d-flex flex-wrap gap-3 justify-content-center">
        @foreach ($langkah as $i => $nama)
            <span class="text-white {{ $i + 1 === $step ? 'fw-bold' : 'opacity-75' }}">{{ $i + 1 }}. {{ $nama }}</span>
        @endforeach
    </div>
</div>

<main class="container py-4">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- LANGKAH 1: PILIH KATEGORI --}}
    @if ($step === 1)
        <h1 class="h3">{{ $event['nama'] }}</h1>
        <p class="text-muted">{{ $event['lokasi'] }} &middot; {{ $event['tanggal'] }}</p>

        <form method="POST" action="{{ route('pesan.simpan') }}">
            @csrf
            <h2 class="h5 mt-4">Kategori Tiket</h2>
            <div class="list-group mb-3">
                @foreach ($tiket as $key => $t)
                    <label class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <input class="form-check-input me-2" type="radio" name="tiket" value="{{ $key }}"
                                   @checked(old('tiket') === $key)>
                            {{ $t['nama'] }} <span class="badge text-bg-success">On Sale</span>
                        </span>
                        <strong>Rp{{ number_format($t['harga'], 0, ',', '.') }}</strong>
                    </label>
                @endforeach
            </div>

            <label class="form-label" for="qty">Jumlah tiket</label>
            <div class="input-group mb-3" style="max-width: 160px">
                <button class="btn btn-outline-secondary" type="button" id="kurang">&minus;</button>
                <input type="number" class="form-control text-center" name="qty" id="qty"
                       min="1" max="5" value="{{ old('qty', 1) }}" readonly>
                <button class="btn btn-outline-secondary" type="button" id="tambah">+</button>
            </div>

            <button class="btn btn-primary">Beli Sekarang</button>
        </form>

        <script>
            const qty = document.getElementById('qty');
            document.getElementById('kurang').onclick = () => { if (+qty.value > 1) qty.value = +qty.value - 1; };
            document.getElementById('tambah').onclick = () => { if (+qty.value < 5) qty.value = +qty.value + 1; };
        </script>

    {{-- LANGKAH 2: DETAIL PESANAN --}}
    @elseif ($step === 2)
        <h1 class="h4">Detail Pesanan</h1>
        <p>{{ $order->nama_tiket }} &times; {{ $order->qty }} &mdash;
           <strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></p>

        <form method="POST" action="{{ route('pesan.detail.simpan', $order) }}" style="max-width: 480px">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $order->nama) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $order->email) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">No. HP</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $order->phone) }}">
            </div>
            <button class="btn btn-primary">Lanjut</button>
        </form>

    {{-- LANGKAH 3: METODE PEMBAYARAN --}}
    @elseif ($step === 3)
        <h1 class="h4">Metode Pembayaran</h1>
        <p>Total: <strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></p>

        <form method="POST" action="{{ route('pesan.metode.simpan', $order) }}" style="max-width: 480px">
            @csrf
            <div class="list-group mb-3">
                @foreach ($metode as $key => $label)
                    <label class="list-group-item">
                        <input class="form-check-input me-2" type="radio" name="metode" value="{{ $key }}"
                               @checked(old('metode', $order->metode) === $key)>
                        {{ $label }}
                    </label>
                @endforeach
            </div>
            <a href="{{ route('pesan.detail', $order) }}" class="btn btn-outline-secondary">Kembali</a>
            <button class="btn btn-primary">Lanjut</button>
        </form>

    {{-- LANGKAH 4: PEMBAYARAN --}}
    @else
        <h1 class="h4">Pembayaran</h1>
        <div class="card" style="max-width: 520px">
            <div class="card-body">
                <p class="mb-1 text-muted">Kode pesanan</p>
                <p class="h5">{{ $order->code }}</p>

                <table class="table table-sm">
                    <tr><td>Event</td><td>{{ $event['nama'] }}</td></tr>
                    <tr><td>Tiket</td><td>{{ $order->nama_tiket }} &times; {{ $order->qty }}</td></tr>
                    <tr><td>Pemesan</td><td>{{ $order->nama }} ({{ $order->email }})</td></tr>
                    <tr><td>Metode</td><td>{{ $metode[$order->metode] ?? '-' }}</td></tr>
                    <tr><td>Total</td><td><strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></td></tr>
                    <tr><td>Status</td><td><span class="badge text-bg-warning">Menunggu pembayaran</span></td></tr>
                </table>

                <div class="alert alert-info mb-0">
                    Silakan lakukan pembayaran sesuai metode yang dipilih.
                </div>
            </div>
        </div>
    @endif
</main>

</body>
</html>