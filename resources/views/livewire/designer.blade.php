<div x-data>
    <div>
        @include('steps.step1')
    </div>

    <div>
        @include('steps.step2')
    </div>

    <div>
        @include('steps.step3')
    </div>

    <div wire:loading.delay.long style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999999;">
        <div style="position: absolute; top: 50%; left: 50%; margin-left: -8px; margin-top: -8px;">
            <div>
                <div style="border-top-color:transparent"
                     class="w-16 h-16 border-4 border-red-500 border-solid rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
</div>
