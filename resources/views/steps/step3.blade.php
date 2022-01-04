<div x-show="$wire.completed" class="text-center">
    <div class="p-6">
        <div class="text-5xl">¡GRACIAS!</div>
        <div class="mt-2 text-2xl">Tu pedido se ha mandado a impresión.</div>

        <div class="mt-4">
            <div wire:click="goHome" class="inline-block bg-red-500 hover:bg-yellow-300 px-3 py-1 rounded-lg font-black text-bold text-xl cursor-pointer">
                <span class="align-middle" style="font-size: 15px;">Crear un nuevo diseño</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="inline align-middle h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
        </div>
    </div>
</div>
