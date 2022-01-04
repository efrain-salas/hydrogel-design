<div x-data="{ q1: '', q2: '' }">
    <div x-show="!$wire.subcategoryId" class="p-6">
        <div class="text-2xl text-center">Elige la marca de tu dispositivo</div>

        <div class="mt-4">
            <input x-model="q1" type="text" class="w-full px-3 py-2 rounded border border-gray-400 text-2xl" placeholder="Buscar..." />
        </div>

        <div class="mt-4">
            <div class="grid grid-cols-3 gap-4">
                @foreach ($this->getProductSubcategories() as $subcat)
                    <div
                        wire:click="$set('subcategoryId', {{ $subcat['id'] }})"
                        x-show="'{{ $subcat['title'] }}'.toLowerCase().includes(q1)"
                        class="flex flex-col justify-center"
                    >
                        <div>
                            <img src="{{ $subcat['main_image_url'] }}" class="w-full rounded" />
                        </div>
                        <div class="mt-1 text-center font-bold">{{ $subcat['title'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div x-show="$wire.subcategoryId && !$wire.productId" class="p-6">
        <div class="text-2xl text-center">Elige el modelo</div>

        <div class="mt-4">
            <input x-model="q2" type="text" class="w-full px-3 py-2 rounded border border-gray-400 text-2xl" placeholder="Buscar..." />
        </div>

        <div class="mt-4">
            <div class="grid grid-cols-3 gap-4">
                @foreach ($this->getProducts() as $product)
                    <div
                        wire:click="$set('productId', {{ $product['id'] }})"
                        x-show="q2.toLowerCase().split(' ').filter((word) => '{{ $product['title'] }}'.toLowerCase().includes(word.toLowerCase())).length == q2.toLowerCase().split(' ').length"
                        class="flex flex-col justify-center"
                    >
                        <div>
                            <img src="{{ $product['image2_url'] }}" class="w-full rounded" />
                        </div>
                        <div class="mt-1 text-center font-bold">{{ $product['title'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
