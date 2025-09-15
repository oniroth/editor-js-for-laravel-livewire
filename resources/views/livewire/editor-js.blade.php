<?php

use Livewire\Volt\Component;

new class extends Component {
    protected $listeners = ['storePost'];
    public function save()
    {
        return $this->dispatch('save-all-content');
    }
    public function storePost($outputData)
    {
        \App\Models\EditorJs::query()->create([
            'content' => $outputData,
        ]);

        $json = json_decode($outputData);

        $paths = [];

        foreach ($json->blocks as $block) {
            if (isset($block->data->file->url)) {
                $paths[] = $block->data->file->url;
            } elseif (isset($block->data->url)) {
                $url = $block->data->url;

                if (Str::contains($url, '/storage/')) {
                    $relative = Str::after($url, '/storage');
                    $paths[] = '/storage' . $relative;
                }
            }
        }

        return $this->redirect('/',false);
    }
}; ?>

<div>
    <div class="navbar bg-base-100 shadow-sm rounded">
        <div class="navbar-start">

        </div>
        <div class="navbar-center hidden lg:flex">

        </div>
        <div class="navbar-end">
            <button class="btn btn-primary" wire:click="save" >ذخیره</button>
        </div>
    </div>
<div class="p-10">
    <div id="editorjs"></div>
</div>

</div>
