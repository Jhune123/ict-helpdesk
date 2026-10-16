<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AssetManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_asset_form_contains_only_the_fields_in_the_inventory_form(): void
    {
        $this->actingAs($this->createAdmin())
            ->get(route('assets.create'))
            ->assertOk()
            ->assertSee('name="entity_name"', false)
            ->assertSee('name="fund_cluster"', false)
            ->assertSee('name="par_no"', false)
            ->assertSee('name="quantity"', false)
            ->assertSee('name="unit"', false)
            ->assertSee('name="property_no"', false)
            ->assertSee('name="date_acquired"', false)
            ->assertSee('name="amount"', false)
            ->assertSee('name="unit_status"', false)
            ->assertSee('name="description"', false)
            ->assertSee('name="purpose"', false)
            ->assertSee('name="approved_for_issuance"', false)
            ->assertSee('name="received_from"', false)
            ->assertSee('name="received_by"', false)
            ->assertSee('name="date_counted"', false)
            ->assertSee('name="po_no"', false)
            ->assertDontSee('name="asset_code"', false)
            ->assertDontSee('name="purchase_date"', false)
            ->assertDontSee('name="notes"', false);
    }

    public function test_asset_details_are_preserved_and_visible_in_show_and_edit(): void
    {
        $admin = $this->createAdmin();
        $assetData = $this->assetData();

        $this->actingAs($admin)
            ->post(route('assets.store'), $assetData)
            ->assertRedirect(route('assets.index'));

        $asset = Asset::firstOrFail();
        $this->assertSame('ICTO-001', $asset->asset_code);
        $this->assertSame('Equipment Notes', $asset->notes);
        $this->assertSame(str_repeat('PO details ', 39) . 'PO details', $asset->po_no);

        $this->actingAs($admin)
            ->get(route('assets.show', $asset))
            ->assertOk()
            ->assertSee('ICT Office')
            ->assertSee('PO. NO.')
            ->assertSee('PO details')
            ->assertDontSee('ICTO-001')
            ->assertDontSee('Laptop Pro')
            ->assertDontSee('Equipment Notes');

        $this->get(route('assets.edit', $asset))
            ->assertOk()
            ->assertSee('name="entity_name"', false)
            ->assertSee('value="ICT Office"', false)
            ->assertSee('name="po_no"', false)
            ->assertSee('PO details')
            ->assertDontSee('name="asset_code"', false)
            ->assertDontSee('name="purchase_date"', false)
            ->assertDontSee('name="notes"', false);

        $assetData['name'] = 'Updated Laptop';
        $assetData['notes'] = 'Updated equipment notes';
        $assetData['po_no'] = str_repeat('Updated PO ', 39) . 'Updated PO';

        $this->put(route('assets.update', $asset), $assetData)
            ->assertRedirect(route('assets.index'));

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Updated Laptop',
            'notes' => 'Updated equipment notes',
            'cost' => 1250.50,
        ]);
        $this->assertSame('2026-04-15', $asset->fresh()->purchase_date->format('Y-m-d'));
        $this->assertSame($assetData['po_no'], $asset->fresh()->po_no);
    }

    public function test_asset_pdf_export_returns_a_pdf_with_asset_details(): void
    {
        $admin = $this->createAdmin();
        $asset = Asset::create($this->assetData());

        $response = $this->actingAs($admin)->get(route('assets.export.pdf'));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertStringStartsWith(
            'inline;',
            $response->headers->get('content-disposition')
        );

        $this->assertStringStartsWith('%PDF', $response->getContent());

        $pdfView = view('assets.pdf', ['assets' => collect([$asset])])->render();
        foreach ([
            'Republic of the Philippines',
            'KALINGA STATE UNIVERSITY',
            'Supply and Property Management Office',
            'Bulanao, Tabuk City, Kalinga',
            'PROPERTY ACKNOWLEDGEMENT RECEIPT',
            'data:image/png;base64,',
        ] as $headerContent) {
            $this->assertStringContainsString($headerContent, $pdfView);
        }

        foreach ([
            'Entity Name',
            'Fund Cluster',
            'PAR No.',
            'Quantity',
            'Unit',
            'Property No.',
            'Date Acquired',
            'Amount',
            'Unit Status',
            'Description / Item Details',
            'Purpose',
            'Approved for Issuance',
            'Received From',
            'Received By',
            'Date Counted',
            'PO. NO.',
        ] as $label) {
            $this->assertStringContainsString($label, $pdfView);
        }

        foreach (['ICTO-001', 'Laptop Pro', 'Example Brand', 'Equipment Notes', 'Purchase Date'] as $hiddenValue) {
            $this->assertStringNotContainsString($hiddenValue, $pdfView);
        }
    }

    private function createAdmin(): User
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }

    private function assetData(): array
    {
        return [
            'asset_code' => 'ICTO-001',
            'entity_name' => 'ICT Office',
            'fund_cluster' => 'General Fund',
            'par_no' => 'PAR-001',
            'po_no' => str_repeat('PO details ', 39) . 'PO details',
            'name' => 'Laptop Pro',
            'brand' => 'Example Brand',
            'model' => 'Model X',
            'serial_number' => 'SN-001',
            'category' => 'Computer',
            'location' => 'Room 101',
            'quantity' => 2,
            'unit' => 'unit',
            'description' => 'Development laptop',
            'property_no' => 'PROP-001',
            'date_acquired' => '2026-04-10',
            'purchase_date' => '2026-04-15',
            'amount' => 1500,
            'cost' => 1250.50,
            'supplier' => 'Example Supplier',
            'unit_status' => 'Active',
            'status' => 'In Service',
            'assigned_to' => 'Alex User',
            'purpose' => 'Development work',
            'approved_for_issuance' => 'Approved',
            'received_from' => 'Property Office',
            'received_by' => 'Alex User',
            'date_counted' => '2026-05-01',
            'notes' => 'Equipment Notes',
        ];
    }
}
