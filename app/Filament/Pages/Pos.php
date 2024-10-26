<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Pos extends Page
{
    protected static ?string $navigationIcon = 'gmdi-point-of-sale-o';
    protected static string $view = 'filament.pages.pos';
    protected static ?string $title = null;
    public array  $cart = [];
    public float $subtotal = 0;
    public float $total = 0;
    public ?Collection $products;


    public function mount(): void
    {
        $this->products = Product::all();
    }

    public function addToCart($productId): void
    {
        $productKey = $this->products->search(fn($item) => $item['id'] === $productId);
        if ($productKey === false) {
            return; // Exit if product is not found
        }

        $product = $this->products[$productKey];

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }

        if ($product['quantity'] > 0) {
            $product['quantity']--;
            $this->products->put($productKey, $product); // Update the product in collection
        }

        $this->calculateTotals();
    }

    public function increaseQuantity($productId)
    {
        $this->cart[$productId]['quantity']++;
        $this->calculateTotals();
    }

    public function decreaseQuantity($productId)
    {
        if ($this->cart[$productId]['quantity'] > 1) {
            $this->cart[$productId]['quantity']--;
        } else {
            unset($this->cart[$productId]);
        }
        $this->calculateTotals();
    }

    private function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $this->total = $this->subtotal;
    }

    public function checkout()
    {

        $invoice = Invoice::create([
            'code' => strtoupper(Str::random()),
            'price' => $this->total,
        ]);

        $itmes = collect($this->cart);
        foreach ($itmes as $invoItem) {
            InvoiceItems::create([
                'invoice_id' => $invoice->id,
                'product_id' => $invoItem['id'],
                'quantity' => $invoItem['quantity'],
            ]);
        }


        $this->cart = [];

        $this->calculateTotals();

        Notification::make()
            ->title('Checkout Successful')
            ->success()
            ->send();
    }
}
