<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Swipe;
use Livewire\Attributes\On;

class SwipingPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';
    
    protected static string $view = 'filament.pages.swiping-page';
    
    protected static ?string $title = 'Swipe Products';
    
    protected static ?int $navigationSort = 1;

    public $currentProduct = null;
    public $productsStack = [];
    public $noMoreProducts = false;
    public $selectedCategory = null;

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $userId = auth()->id();
        
        // Get products that user hasn't swiped yet
        $products = Product::active()
            ->where('user_id', '!=', $userId)
            ->whereNotIn('id', function($query) use ($userId) {
                $query->select('product_id')
                    ->from('swipes')
                    ->where('user_id', $userId);
            })
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->with(['category', 'user'])
            ->inRandomOrder()
            ->limit(10)
            ->get();

        if ($products->isEmpty()) {
            $this->noMoreProducts = true;
            $this->currentProduct = null;
        } else {
            $this->productsStack = $products->toArray();
            $this->currentProduct = array_shift($this->productsStack);
            $this->noMoreProducts = false;
        }
    }

    public function swipeLeft()
    {
        if (!$this->currentProduct) return;

        Swipe::create([
            'user_id' => auth()->id(),
            'product_id' => $this->currentProduct['id'],
            'direction' => 'left',
        ]);

        $this->nextProduct();
    }

    public function swipeRight()
    {
        if (!$this->currentProduct) return;

        $swipe = Swipe::create([
            'user_id' => auth()->id(),
            'product_id' => $this->currentProduct['id'],
            'direction' => 'right',
        ]);

        // Create match
        $product = Product::find($this->currentProduct['id']);
        Swipe::createMatch(auth()->id(), $product);

        $this->dispatch('show-match-notification');
        $this->nextProduct();
    }

    public function nextProduct()
    {
        if (empty($this->productsStack)) {
            $this->noMoreProducts = true;
            $this->currentProduct = null;
        } else {
            $this->currentProduct = array_shift($this->productsStack);
        }

        // Load more products if stack is running low
        if (count($this->productsStack) < 3) {
            $this->loadProducts();
        }
    }

    public function updatedSelectedCategory()
    {
        $this->loadProducts();
    }

    public function resetFilters()
    {
        $this->selectedCategory = null;
        $this->loadProducts();
    }
}