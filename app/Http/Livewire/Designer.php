<?php

namespace App\Http\Livewire;

use App\Models\Design;
use App\Models\User;
use App\Services\CatalogService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Barryvdh\DomPDF\Facade as PDF;
use Ramsey\Uuid\Uuid;

class Designer extends Component
{
    public ?int $userId = null;
    public ?int $categoryId = 1;
    public ?int $subcategoryId = null;
    public ?int $productId = null;

    public bool $completed = false;
    public ?int $designId = null;

    protected $listeners = [
        'frontendReady' => 'loadPlt',
        'print' => 'sendToPrint',
    ];

    protected $queryString = [
        'subcategoryId',
        'productId',
    ];

    public function mount($userId)
    {
        $this->userId = $userId;
    }

    public function render()
    {
        return view('livewire.designer');
    }

    public function updatedProductId()
    {
        if ($this->productId) {
            $this->loadPlt();
        }
    }

    public function goHome()
    {
        $this->subcategoryId = null;
        $this->productId = null;
        $this->completed = false;
    }

    // STEP 1

    public function getProductCategories(): array
    {
        return collect((new CatalogService())->getCategories())
            ->sortBy('title')
            ->all();
    }

    public function getProductSubcategories(): array
    {
        if (!$this->categoryId) return [];

        return collect((new CatalogService())->getSubcategories($this->categoryId))
            ->sortBy('title')
            ->all();
    }

    public function getProducts(): array
    {
        if (!$this->subcategoryId) return [];

        return collect((new CatalogService())->getProducts($this->subcategoryId))
            ->sortBy('title')
            ->all();
    }

    // STEP 2

    public function loadPlt()
    {
        if ($this->productId) {
            $user = auth()->user();

            $plt = (new CatalogService())->getPltFile($this->productId);
            $this->emit('pltFileReady', $plt, $user->print_top_offset, $user->print_horizontal_deviation);
        }
    }

    public function sendToPrint($image)
    {
        PDF::setOptions(['dpi' => 72]);

        // Convert paper size in CM to pixels
        $cmInPixels = 72 / 2.54; // 1 cm in pixels (72 dpi)
        $width = 11.9 * $cmInPixels;
        $height = 18.5 * $cmInPixels;

        $uuid = Uuid::uuid4();

        // PDF file
        $pdf = PDF::loadView('pdf.print', compact('image'))
            ->setPaper([0, 0, $width, $height])
            ->output();

        $filePath = 'designs/' . $uuid . '.pdf';
        Storage::put($filePath, $pdf);

        // PNG Thumbnail
        $thumbnail = base64_decode(Str::after($image, ','));

        $thumbnailPath = 'designs/' . $uuid . '.png';
        Storage::put($thumbnailPath, $thumbnail);

        $design = new Design();
        $design->user()->associate($this->userId);
        $design->file = $filePath;
        $design->thumbnail = $thumbnailPath;
        $design->save();

        $this->completed = true;
        $this->designId = $design->id;
    }

    public function getEmojis(): array
    {
        return array_map(function ($emojiFilePath) {
            return secure_url('emojis/' . basename($emojiFilePath));
        }, glob(public_path('emojis') . '/*'));
    }

    public function getFontFamilies(): array
    {
        return [
            'Bangers' => [
                'size' => 16,
            ],
            'Licorice' => [
                'size' => 20,
            ],
            'Pushster' => [
                'size' => 16,
            ],
            'Roboto' => [
                'size' => 16,
            ],
            'Roboto Slab' => [
                'size' => 16,
            ],
            'Russo One' => [
                'size' => 16,
            ],
            'Vujahday Script' => [
                'size' => 18,
            ],
        ];
    }

    public function getTextColors(): array
    {
        return [
            'Negro' => 'black',
            'Rojo' => '#dc2626',
            'Naranja' => '#ea580c',
            'Amarillo' => '#facc15',
            'Verde' => '#3f6212',
            'Cian' => '#06b6d4',
            'Celeste' => '#60a5fa',
            'Azul' => '#1e40af',
            'Fucsia' => '#d946ef',
            'Rosa' => '#fda4af',
            'Blanco' => 'white',
        ];
    }

    // STEP 3
}
