@extends('layouts.app')

@section('title', 'Transaksi Baru')
@section('section', 'Transaksi baru')

@section('content')
    <div class="page-heading compact-heading"><div><p class="eyebrow">KASIR APOTEK</p><h1>Transaksi baru</h1><p class="page-description">Pilih obat dan jumlah; stok akan diperbarui setelah transaksi disimpan.</p></div></div>
    @if ($medicines->isEmpty())
        <div class="notice-panel"><strong>Belum ada obat yang dapat dijual.</strong><span>Pastikan obat sudah terdaftar, stok tersedia, dan belum kedaluwarsa.</span><a class="button button-primary" href="{{ route('medicines.create') }}">Tambah obat</a></div>
    @else
        <form action="{{ route('sales.store') }}" method="POST" id="sale-form">@csrf
            <div class="form-panel sale-panel">
                <div class="form-grid customer-fields">
                    <div class="field"><label for="customer_name">Nama pelanggan</label><input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Opsional"></div>
                    <div class="field"><label for="payment_method">Metode pembayaran <span>*</span></label><select id="payment_method" name="payment_method" required><option value="tunai" @selected(old('payment_method', 'tunai') === 'tunai')>Tunai</option><option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer</option><option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option></select></div>
                </div>
                <div class="line-items-heading"><div><h2>Obat yang dibeli</h2><p>Harga mengikuti harga jual saat ini.</p></div><button class="button button-light" id="add-item" type="button"><span class="button-symbol">+</span> Tambah baris</button></div>
                <div class="line-items" id="line-items">
                    <div class="sale-line"><div class="field"><label>Nama obat</label><select name="items[0][medicine_id]" class="medicine-select" required><option value="">Pilih obat</option>@foreach ($medicines as $medicine)<option value="{{ $medicine->id }}" data-price="{{ $medicine->selling_price }}" data-stock="{{ $medicine->stock }}">{{ $medicine->name }} · stok {{ $medicine->stock }}</option>@endforeach</select></div><div class="field quantity-field"><label>Jumlah</label><input name="items[0][quantity]" type="number" min="1" value="1" required class="quantity-input"></div><div class="line-total"><span>Subtotal</span><strong class="line-subtotal">Rp 0,00</strong></div><button class="icon-action remove-line" type="button" aria-label="Hapus baris" title="Hapus baris">×</button></div>
                </div>
                <div class="sale-total"><span>Total pembayaran</span><strong id="sale-total">Rp 0,00</strong></div>
                <div class="form-footer"><a class="button button-light" href="{{ route('sales.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan transaksi</button></div>
            </div>
        </form>
    @endif
@endsection

@push('scripts')
<script>
    const medicines = @json($medicineOptions);
    const lineItems = document.getElementById('line-items');
    if (lineItems) {
        let nextLineIndex = lineItems.querySelectorAll('.sale-line').length;
        const formatRupiah = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
        const refreshTotals = () => {
            let total = 0;
            const lines = lineItems.querySelectorAll('.sale-line');
            lines.forEach((line) => {
                const select = line.querySelector('.medicine-select');
                const quantity = Number(line.querySelector('.quantity-input').value || 0);
                const option = select.selectedOptions[0];
                const subtotal = Number(option?.dataset.price || 0) * quantity;
                line.querySelector('.line-subtotal').textContent = formatRupiah(subtotal);
                if (option?.dataset.stock) line.querySelector('.quantity-input').max = option.dataset.stock;
                line.querySelector('.remove-line').disabled = lines.length === 1;
                total += subtotal;
            });
            document.getElementById('sale-total').textContent = formatRupiah(total);
        };
        const addLine = () => {
            const index = nextLineIndex++;
            const line = document.createElement('div');
            line.className = 'sale-line';
            const medicineField = document.createElement('div');
            medicineField.className = 'field';
            const medicineLabel = document.createElement('label');
            medicineLabel.textContent = 'Nama obat';
            const select = document.createElement('select');
            select.name = `items[${index}][medicine_id]`;
            select.className = 'medicine-select';
            select.required = true;
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Pilih obat';
            select.append(placeholder);
            medicines.forEach((medicine) => {
                const option = document.createElement('option');
                option.value = medicine.id;
                option.dataset.price = medicine.price;
                option.dataset.stock = medicine.stock;
                option.textContent = `${medicine.name} · stok ${medicine.stock}`;
                select.append(option);
            });
            medicineField.append(medicineLabel, select);

            const quantityField = document.createElement('div');
            quantityField.className = 'field quantity-field';
            const quantityLabel = document.createElement('label');
            quantityLabel.textContent = 'Jumlah';
            const quantityInput = document.createElement('input');
            quantityInput.name = `items[${index}][quantity]`;
            quantityInput.type = 'number';
            quantityInput.min = '1';
            quantityInput.value = '1';
            quantityInput.required = true;
            quantityInput.className = 'quantity-input';
            quantityField.append(quantityLabel, quantityInput);

            const lineTotal = document.createElement('div');
            lineTotal.className = 'line-total';
            const totalLabel = document.createElement('span');
            totalLabel.textContent = 'Subtotal';
            const subtotal = document.createElement('strong');
            subtotal.className = 'line-subtotal';
            subtotal.textContent = formatRupiah(0);
            lineTotal.append(totalLabel, subtotal);

            const removeButton = document.createElement('button');
            removeButton.className = 'icon-action remove-line';
            removeButton.type = 'button';
            removeButton.setAttribute('aria-label', 'Hapus baris');
            removeButton.title = 'Hapus baris';
            removeButton.textContent = '×';

            line.append(medicineField, quantityField, lineTotal, removeButton);
            lineItems.append(line);
            refreshTotals();
        };
        document.getElementById('add-item').addEventListener('click', addLine);
        lineItems.addEventListener('change', refreshTotals);
        lineItems.addEventListener('input', refreshTotals);
        lineItems.addEventListener('click', (event) => {
            const removeButton = event.target.closest('.remove-line');
            if (removeButton && lineItems.querySelectorAll('.sale-line').length > 1) {
                removeButton.closest('.sale-line').remove();
                refreshTotals();
            }
        });
    }
</script>
@endpush