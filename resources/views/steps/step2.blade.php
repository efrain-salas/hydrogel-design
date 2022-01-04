<div x-data="{ showEmojis: false }" x-show="$wire.productId">
    <div id="toolbar" class="px-2 pt-4 pb-1 select-none">
        <div id="bt-add-image" class="relative inline-block bg-red-700 hover:bg-red-600 px-2 py-0 rounded-lg font-regular text-white text-lg cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline align-middle h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="align-middle" style="font-size: 15px;">Foto</span>
            <input onchange="addUserBackgroundImage()" type="file" accept="image/png, image/jpeg" style="opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden" />
        </div>
        <div id="bt-show-emojis" @click="showEmojis = !showEmojis" class="inline-block bg-red-700 hover:bg-red-600 px-2 py-0 rounded-lg font-regular text-white text-lg cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline align-middle h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="align-middle" style="font-size: 15px;">Emojis</span>
        </div>
        <div id="bt-add-text" onclick="addText()" class="inline-block bg-red-700 hover:bg-red-600 px-2 py-0 rounded-lg font-regular text-white text-lg cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline align-middle h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="align-middle" style="font-size: 15px;">Texto</span>
        </div>
        <div id="bt-delete" onclick="deleteSelected()" class="hidden inline-block bg-red-700 hover:bg-red-600 px-2 py-0 rounded-lg font-regular text-white text-lg cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline align-middle h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span class="align-middle" style="font-size: 15px;">Borrar</span>
        </div>
    </div>
    <div class="mt-1 px-2 select-none" style="height: 30px;">
        <div id="select-font-families" class="hidden inline-block">
            @include('partials.font-families')
        </div>
        <div id="select-font-sizes" class="hidden inline-block">
            @include('partials.font-sizes')
        </div>
        <div id="select-text-colors" class="hidden inline-block">
            @include('partials.text-colors')
        </div>
        <div id="select-text-alignment" class="hidden inline-block align-middle">
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <button onclick="changeSelectedTextAlignment('left')" type="button" style="padding: 6px 6px;" :class="$store.global.textAlign == 'left' ? 'bg-gray-200' : 'bg-white'" class="text-sm font-medium text-gray-900 rounded-l-lg border border-gray-200 hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-red-700 focus:text-red-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-red-500 dark:focus:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M14 15H4c-.55 0-1 .45-1 1s.45 1 1 1h10c.55 0 1-.45 1-1s-.45-1-1-1zm0-8H4c-.55 0-1 .45-1 1s.45 1 1 1h10c.55 0 1-.45 1-1s-.45-1-1-1zM4 13h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zm0 8h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zM3 4c0 .55.45 1 1 1h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1z"/></svg>
                </button>
                <button onclick="changeSelectedTextAlignment('center')" type="button" style="padding: 6px 6px;" :class="$store.global.textAlign == 'center' ? 'bg-gray-200' : 'bg-white'" class="text-sm font-medium text-gray-900 border-t border-b border-gray-200 hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-red-700 focus:text-red-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-red-500 dark:focus:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M7 16c0 .55.45 1 1 1h8c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1zm-3 5h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zm0-8h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zm3-5c0 .55.45 1 1 1h8c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1zM3 4c0 .55.45 1 1 1h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1z"/></svg>
                </button>
                <button onclick="changeSelectedTextAlignment('right')" type="button" style="padding: 6px 6px;" :class="$store.global.textAlign == 'right' ? 'bg-gray-200' : 'bg-white'" class="text-sm font-medium text-gray-900 rounded-r-md border border-gray-200 hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-red-700 focus:text-red-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-red-500 dark:focus:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M4 21h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zm6-4h10c.55 0 1-.45 1-1s-.45-1-1-1H10c-.55 0-1 .45-1 1s.45 1 1 1zm-6-4h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1s.45 1 1 1zm6-4h10c.55 0 1-.45 1-1s-.45-1-1-1H10c-.55 0-1 .45-1 1s.45 1 1 1zM3 4c0 .55.45 1 1 1h16c.55 0 1-.45 1-1s-.45-1-1-1H4c-.55 0-1 .45-1 1z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div wire:ignore class="p-2">
        <!-- bg-white border border-gray-500 -->
        <div id="stage" class=""></div>
    </div>

    <div class="px-2 pt-1 select-none">
        <div id="bt-print" onclick="printStage()" class="inline-block bg-red-700 hover:bg-red-600 px-2 py-0 rounded-lg font-regular text-white text-lg cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline align-middle h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <span class="align-middle" style="font-size: 15px;">Mandar a imprimir</span>
        </div>
    </div>

    <div x-show="showEmojis" id="emoji-selector" style="display: none;">
        @include('partials.emojis')
    </div>
</div>
