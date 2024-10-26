<x-filament-panels::page>
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-2/3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transition-colors">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Available Items</h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                     x-data="{ playSound() { new Audio('/Barcode scanner beep sound (sound effect).mp3').play(); } }"
                >
                    @foreach($products as $product)
                        <div
                            class="group bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-xl p-4 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 cursor-pointer"
                            x-on:click="playSound()"
                        >
                            <div class="aspect-w-1 aspect-h-1 mb-3">
                                <div
                                    class="h-32 bg-gray-200 dark:bg-gray-600 rounded-lg group-hover:bg-gray-300 dark:group-hover:bg-gray-500 transition-colors">
                                    <img src="{{asset($product->image)}}" alt="img" class="w-full h-full"></div>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                            <p class="text-blue-600 dark:text-blue-400 font-bold">
                                ${{ number_format($product->price, 2) }}</p>
                            <div class="my-1">
                                <span class="text-sm text-black dark:text-white">Quantity</span>
                                <span class="text-sm text-black dark:text-white">{{$product->stock_quantity}}</span>
                            </div>

                            <div class="mt-2">
                               <x-filament::button
                                   wire:click="addToCart({{ $product->id }})"
                               >
                                  Click to Add
                               </x-filament::button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="lg:w-1/3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-4 transition-colors">
                <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Cart
                    <span class="text-sm bg-blue-500 text-white px-2 py-1 rounded-full">{{ count($cart) }}</span>
                </h2>

                <div class="space-y-4 mb-4 max-h-96 overflow-y-auto custom-scrollbar">
                    @if(empty($cart))
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-500" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Your cart is empty
                        </div>
                    @endif

                    @foreach($cart as $item)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item['name'] }}</h3>
                                <p class="text-blue-600 dark:text-blue-400">${{ number_format($item['price'], 2) }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button wire:click="decreaseQuantity({{ $item['id'] }})"
                                        class="bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white p-2 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M20 12H4"/>
                                    </svg>
                                </button>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item['quantity'] }}</span>
                                <button wire:click="increaseQuantity({{ $item['id'] }})"
                                        class="bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white p-2 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="border-t dark:border-gray-600 pt-4 space-y-3">
                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-gray-900 dark:text-white">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button wire:click="checkout"
                        class="w-full mt-6 bg-blue-600 dark:bg-blue-500 text-white py-4 rounded-xl hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors flex items-center justify-center gap-2"
                        wire:loading.attr="disabled"
                        wire:target="checkout">
                        <span wire:loading.remove wire:target="checkout">
                            <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Complete Purchase
                        </span>
                    <span wire:loading wire:target="checkout" class="flex items-center gap-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
