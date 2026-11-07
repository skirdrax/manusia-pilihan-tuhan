<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_and_sales_pages_render(): void
    {
        $this->get(route('medicines.index'))->assertOk()->assertSee('Data obat');
        $this->get(route('sales.index'))->assertOk()->assertSee('Riwayat transaksi');
        $this->get(route('sales.create'))->assertOk()->assertSee('Belum ada obat yang dapat dijual');
    }

    public function test_medicine_can_be_created_updated_and_deleted(): void
    {
        $this->post(route('medicines.store'), [
            'sku' => 'OB-001',
            'name' => 'Paracetamol',
            'category' => 'Analgesik',
            'unit' => 'strip',
            'purchase_price' => 5000,
            'selling_price' => 7500,
            'stock' => 20,
        ])->assertRedirect(route('medicines.index'));

        $medicine = Medicine::where('sku', 'OB-001')->firstOrFail();
        $this->assertDatabaseHas('medicines', ['id' => $medicine->id, 'stock' => 20]);

        $this->put(route('medicines.update', $medicine), [
            'sku' => 'OB-001',
            'name' => 'Paracetamol 500 mg',
            'category' => 'Analgesik',
            'unit' => 'strip',
            'purchase_price' => 5000,
            'selling_price' => 8000,
            'stock' => 18,
        ])->assertRedirect(route('medicines.index'));

        $this->assertDatabaseHas('medicines', ['id' => $medicine->id, 'name' => 'Paracetamol 500 mg', 'stock' => 18]);
        $this->delete(route('medicines.destroy', $medicine))->assertRedirect(route('medicines.index'));
        $this->assertDatabaseMissing('medicines', ['id' => $medicine->id]);
    }

    public function test_sale_records_items_and_reduces_medicine_stock(): void
    {
        $medicine = Medicine::create([
            'sku' => 'OB-002',
            'name' => 'Vitamin C',
            'unit' => 'botol',
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock' => 8,
        ]);

        $response = $this->post(route('sales.store'), [
            'customer_name' => 'Pelanggan Uji',
            'payment_method' => 'tunai',
            'items' => [['medicine_id' => $medicine->id, 'quantity' => 3]],
        ]);

        $sale = Sale::firstOrFail();
        $response->assertRedirect(route('sales.show', $sale));
        $this->assertDatabaseHas('medicines', ['id' => $medicine->id, 'stock' => 5]);
        $this->assertDatabaseHas('sales', ['id' => $sale->id, 'total' => '45000.00']);
        $this->assertDatabaseHas('sale_items', ['sale_id' => $sale->id, 'medicine_id' => $medicine->id, 'quantity' => 3]);
    }

    public function test_sale_with_insufficient_stock_is_rejected_without_changing_data(): void
    {
        $medicine = Medicine::create([
            'sku' => 'OB-003',
            'name' => 'Obat Stok Terbatas',
            'unit' => 'pcs',
            'purchase_price' => 1000,
            'selling_price' => 2000,
            'stock' => 2,
        ]);

        $this->from(route('sales.create'))->post(route('sales.store'), [
            'payment_method' => 'tunai',
            'items' => [['medicine_id' => $medicine->id, 'quantity' => 3]],
        ])->assertRedirect(route('sales.create'))->assertSessionHasErrors('items');

        $this->assertDatabaseHas('medicines', ['id' => $medicine->id, 'stock' => 2]);
        $this->assertDatabaseCount('sales', 0);
    }
}
