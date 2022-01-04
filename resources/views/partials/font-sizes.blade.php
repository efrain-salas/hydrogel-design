<!-- This example requires Tailwind CSS v2.0+ -->
<div x-data="{ open: false }" class="relative inline-block text-left">
    <div>
        <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="menu-button" aria-expanded="true" aria-haspopup="true">
            Tamaño
            <!-- Heroicon name: solid/chevron-down -->
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!--
      Dropdown menu, show/hide based on menu state.

      Entering: "transition ease-out duration-100"
        From: "transform opacity-0 scale-95"
        To: "transform opacity-100 scale-100"
      Leaving: "transition ease-in duration-75"
        From: "transform opacity-100 scale-100"
        To: "transform opacity-0 scale-95"
    -->
    <div x-show="open" class="z-10 origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <div class="py-1" role="none">
            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
            @foreach ([16, 18, 20, 22, 24, 28, 34, 40] as $size)
                <div onclick="changeSelectedTextFontSize({{ $size }})" @click="open = false" :class="$store.global.fontSize == {{ $size }} ? 'bg-gray-200 text-gray-900' : 'text-gray-700'" class="text-gray-700 hover:bg-gray-100 hover:text-gray-900 block px-4 py-2 text-sm cursor-pointer" role="menuitem">
                    {{ $size }}
                </div>
            @endforeach
        </div>
    </div>
</div>
