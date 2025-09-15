<?php

use Livewire\Volt\Component;

new class extends Component {
    public $e;
    public function mount($id)
    {
        $this->e = \App\Models\EditorJs::query()->where('id', $id)->firstOrFail();
//        dd(json_decode($this->article->content,true));
    }
}; ?>

<div>
    <div id="article-content"
         class="min-w-7xl shadow-2xl px-8 py-5 r">

        @php
            $content = json_decode($e->content,true);
        @endphp

        @foreach($content['blocks'] as $block)
            @switch($block['type'])
                {{--Title--}}
                @case('title')
                    @php
                        $color = '';
                        if (!empty($block['data']['color'])) {
                            $color = match ($block['data']['color']) {
                                'Blue' => 'text-blue-600',
                                'Red' => 'text-red-600',
                                'Purple' => 'text-purple-600',
                                'Pink' => 'text-pink-600',
                                'Orange' => 'text-orange-600',
                                'Black' => 'text-black-600',
                                'Yellow' => 'text-yellow-600',
                                'Green' => 'text-green-600',
                            };
                        }
                           $alignment = '';
                        if (!empty($block['data']['alignText'])) {
                            $alignment = match (strtolower($block['data']['alignText'])) {
                                'text-align-left' => 'text-left',
                                'text-align-center' => 'text-center',
                                'text-align-right' => 'text-right',
                                default => ''
                            };
                        }
                    @endphp
                    <p class="{{ ($block['data']['titleType'] ?? '') . ' ' . $alignment . ' ' . $color }} ">
                        {!! $block['data']['text'] !!}
                    </p>
                    @break
                    {{--Columns--}}
                @case('columns')
                    @php
                        $columns = $block['data']['cols'];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-{{ count($columns) }} gap-6 my-6">
                        @foreach($columns as $col)
                            <div class="p-4 bg-base-200 rounded-box  space-y-3">
                                @foreach($col['blocks'] as $innerBlock)
                                    @if($innerBlock['type'] === 'paragraph')
                                        <p class="text-base leading-relaxed ">
                                            {!! $innerBlock['data']['text'] !!}
                                        </p>
                                    @endif
                                    @if($innerBlock['type'] === 'delimiter')
                                        <div class="divider"></div>
                                    @endif
                                    @if($innerBlock['type'] === 'code')
                                        @php
                                            $codeType = strtolower($innerBlock['data']['mode'] ?? 'code');
                                            $codeContent = $innerBlock['data']['code'] ?? '';
                                            $availableLanguages = ['php', 'javascript', 'cpp', 'python', 'cs', 'go', 'markdown'];
                                            $langClass = in_array($codeType, $availableLanguages) ? $codeType : 'code';
                                            $displayLabels = [
                                                'php' => 'PHP',
                                                'javascript' => 'JavaScript',
                                                'cpp' => 'C++',
                                                'python' => 'Python',
                                                'cs' => 'C#',
                                                'go' => 'Go',
                                                'markdown' => 'Markdown',
                                            ];

                                            $langLabel = $displayLabels[$codeType] ?? ucfirst($codeType);
                                        @endphp

                                        <div class="mockup-code  w-full rounded bg-base-200">
        <pre data-prefix="{{ $langLabel }}" class="flex">
            <code class="language-{{ $langClass }}">{!! e($codeContent) !!}</code>
        </pre>
                                        </div>
                                    @endif
                                    @if($innerBlock['type'] === 'embed')
                                        <div class="divider"></div>
                                    @endif

                                    @if ($innerBlock['type'] === 'image')
                                        @php
                                            $url = $innerBlock['data']['url'] ?? '';
                                            $isBase64 = str_starts_with($url, 'data:image');
                                        @endphp

                                        @if (!empty($url))
                                            <img
                                                src="{{ $isBase64 ? $url : asset($url) }}"
                                                @if(!empty($innerBlock['data']['withBorder'])) style="border:1px solid #ccc;"
                                                @endif
                                                @if(!empty($innerBlock['data']['withBackground'])) class="bg-gray-100 p-2"
                                                @endif
                                                @if(!empty($innerBlock['data']['stretched'])) style="width: 100%;"
                                                @endif
                                                alt="{{ $innerBlock['data']['caption'] ?? 'تصویر' }}">
                                            @if ($innerBlock['data']['caption'])
                                                <p class="mt-2 text-sm text-gray-500 text-center">{!! $innerBlock['data']['caption']  !!} </p>
                                            @endif
                                        @endif
                                    @endif

                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    @break
                    {{--DrawingTool--}}
                @case('drawingTool')
                    @php
                        $canvasData = json_decode($block['data']['canvasJson'], true);
                        $canvasImages = $block['data']['canvasImages'] ?? [];
                    @endphp

                    <div class="m-2 relative border overflow-hidden rounded bg-gray-400 "
                         style="height: {{ $block['data']['canvasHeight'] }}px;">
                        @foreach ($canvasData['children'] as $child)
                            @if ($child['className'] === 'Text')
                                @php
                                    $attrs = $child['attrs'];
                                    $styles = [
                                        'font-size' => $attrs['fontSize'] . 'px',
                                        'font-family' => $attrs['fontFamily'],
                                        'color' => $attrs['fill'],
                                        'position' => 'absolute',
                                        'top' => $attrs['y'] . 'px',
                                        'left' => $attrs['x'] . 'px',
                                        'width' => $attrs['width'] . 'px',
                                        'line-height' => $attrs['lineHeight'],
                                        'cursor' => isset($attrs['link']) ? 'pointer' : 'default',
                                        'font-weight' => ($attrs['fontStyle'] ?? '') === 'bold' ? 'bold' : 'normal',
                                        'text-decoration' => $attrs['textDecoration'] ?? 'none',
                                        'text-align' => $attrs['align'] ?? 'left',
                                    ];
                                    $styleString = '';
                                    foreach ($styles as $key => $value) {
                                        $styleString .= $key . ': ' . $value . '; ';
                                    }
                                @endphp

                                @if (!empty($attrs['link']))
                                    <a href="{{ $attrs['link'] }}" target="_blank" style="{{ $styleString }}">
                                        {!! nl2br(e($attrs['text'])) !!}
                                    </a>
                                @else
                                    <p style="{{ $styleString }}">
                                        {!! nl2br(e($attrs['text'])) !!}
                                    </p>
                                @endif

                            @elseif ($child['className'] === 'Image')
                                @php

                                    $imageSrc = '';
                                    foreach ($canvasImages as $img) {
                                        if ($img['id'] === $child['attrs']['id']) {
                                            $imageSrc = $img['src'];
                                            break;
                                        }
                                    }
                                @endphp

                                @if ($imageSrc)
                                    <img
                                        src="{{ $imageSrc }}"
                                        alt="Image"
                                        style="
                        position: absolute;
                        top: {{ $child['attrs']['y'] ?? 0 }}px;
                        left: {{ $child['attrs']['x'] ?? 0 }}px;
                        width: {{ $child['attrs']['width'] ?? 'auto' }}px;
                        height: {{ $child['attrs']['height'] ?? 'auto' }}px;
                        cursor: move;
                        user-select: none;
                    "
                                        draggable="false"
                                    />
                                @endif

                            @elseif ($child['className'] === 'Transformer')
                                {{-- Transformers عموماً برای ویرایش در Konva استفاده می‌شوند و برای نمایش در HTML نیازی نیست چیزی نمایش داده شود --}}
                            @endif
                        @endforeach
                    </div>


                    @break
                    {{--LinkTool--}}
                @case('linkTool')
                    @php
                        $linkData = $block['data'] ?? null;
                    @endphp

                    @if ($linkData && isset($linkData['link'], $linkData['meta']))
                        <a href="{{ $linkData['link'] }}" target="_blank" rel="noopener noreferrer"
                           class="no-underline m-2">
                            <div
                                class="card card-side bg-base-100 shadow-md hover:shadow-xl transition-shadow ">
                                @if (!empty($linkData['meta']['image']['url']))
                                    <figure class="min-w-[150px] max-w-[200px]">
                                        <img
                                            src="{{ $linkData['meta']['image']['url'] }}"
                                            alt="{{ $linkData['meta']['title'] ?? 'Link preview' }}"
                                            class="object-cover w-full h-full rounded-l-lg"
                                        />
                                    </figure>
                                @endif
                                <div class="card-body p-4">
                                    <h2 class="card-title text-primary text-lg leading-tight">
                                        {{ $linkData['meta']['title'] }}
                                    </h2>
                                    <p class="text-sm text-base-content/70">
                                        {{ $linkData['meta']['description'] }}
                                    </p>
                                    <p class="text-xs text-neutral-500 mt-2">
                                        {{ parse_url($linkData['link'], PHP_URL_HOST) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endif

                    @break
                    {{--Attaches--}}
                @case('attaches')
                    @php
                        $file = $block['data']['file'];
                        $title = $block['data']['title'];
                    @endphp

                    <div
                        class="flex items-center gap-4 p-4 m-5 border border-b-2 border-base-300 border-b-white rounded-xl bg-base-300 shadow-sm  ">
                        <div class="flex items-center justify-center w-12 h-12 bg-primary/10 text-primary rounded-lg">
                            <x-sui-paperclip class="w-6"/>
                        </div>
                        <div class="flex flex-col grow">
                            <div class="text-sm font-medium text-base-content">{{ $title }} </div>

                        </div>
                        <a href="{{ $file['url'] }}" download
                           class="btn btn-sm btn-outline btn-primary rounded-lg text-xs">
                            <x-sui-download-alt class="w-6"/>
                        </a>
                    </div>

                    @break
                    {{--Paragraph--}}
                @case('paragraph')
                    @php
                        $rawHtml = $block['data']['text'];

                        if (str_contains($rawHtml, '<a')) {
                            $highlightedText = preg_replace(
                                '/<a([^>]*)>(.*?)<\/a>/i',
                                '<a$1 style="color:#7fd1df;  text-decoration: underline; padding: 2px 4px; border-radius: 2px;">$2</a>',
                                $rawHtml
                            );
                        } else {
                            $highlightedText = $rawHtml;
                        }
                    @endphp

                    @if (trim(strip_tags($highlightedText)) !== '')
                        <p class="text-base leading-relaxed text-base-content">
                            {!! $highlightedText !!}
                        </p>
                    @endif
                    @break
                    {{--List--}}
                @case('list')
                    @php
                        $list = $block['data'];
                        $style = $list['style'] ?? 'unordered';
                        $items = $list['items'] ?? [];
                    @endphp

                    @if (!empty($items))
                        @if ($style === 'ordered')
                            <ol class="list-decimal ps-5 space-y-2 marker:text-primary">
                                @foreach ($items as $item)
                                    <li>{!! $item['content'] !!}</li>
                                @endforeach
                            </ol>
                        @elseif($style === 'checklist')
                            <ul class="space-y-2">
                                @foreach ($items as $item)
                                    <li class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            class="checkbox checkbox-sm checkbox-primary"
                                            disabled
                                            @if(!empty($item['meta']['checked'])) checked @endif
                                        >
                                        <span>{!! $item['content'] !!}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <ul class="list-disc ps-5 space-y-2 marker:text-primary">
                                @foreach ($items as $item)
                                    <li>{!! $item['content'] !!}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endif


                    @break
                    {{--ImageTool--}}
                @case('imageTool')
                    @if (!empty($block['data']['file']['url']))
                        <img
                            src="{{ asset($block['data']['file']['url']) }}"
                            alt="{{ $block['data']['caption'] ?? 'تصویر' }}"
                            @if($block['data']['withBorder']) style="border:1px solid #ccc;" @endif
                            @if($block['data']['withBackground']) class="bg-gray-100 p-2" @endif
                            @if($block['data']['stretched']) style="width: 100%;" @endif
                        >

                    @endif

                    @break
                    {{--Image--}}
                @case('image')
                    @if (!empty($block['data']['url']))
                        <img
                            src="{{ asset($block['data']['url']) }}"
                            alt="{{ $block['data']['caption'] ?? 'تصویر' }}"
                            @if($block['data']['withBorder']) style="border:1px solid #ccc;" @endif
                            @if($block['data']['withBackground']) class="bg-gray-100 p-2" @endif
                            @if($block['data']['stretched']) style="width: 100%;" @endif>
                        @if ($block['data']['caption'])
                            <p class="mt-2 text-sm text-gray-500 text-center">{!! $block['data']['caption'] !!} </p>
                        @endif
                    @endif

                    @break
                    {{--Code--}}
                @case('code')
                    @php
                        $codeType = strtolower($block['data']['mode'] ?? 'code');
                        $codeContent = $block['data']['code'] ?? '';
                        $availableLanguages = ['php', 'javascript', 'cpp', 'python', 'cs', 'go', 'markdown'];
                        $langClass = in_array($codeType, $availableLanguages) ? $codeType : 'code';
                        $displayLabels = [
                            'php' => 'PHP',
                            'javascript' => 'JavaScript',
                            'cpp' => 'C++',
                            'python' => 'Python',
                            'cs' => 'C#',
                            'go' => 'Go',
                            'markdown' => 'Markdown',
                        ];

                        $langLabel = $displayLabels[$codeType] ?? ucfirst($codeType);
                    @endphp

                    <div class="mockup-code w-full rounded bg-base-200">
        <pre data-prefix="{{ $langLabel }}" class="flex">
            <code class="language-{{ $langClass }}">{!! e($codeContent) !!}</code>
        </pre>
                    </div>
                    @break
                    {{--Table--}}
                @case('table')
                    @php
                        $rows = $block['data']['content'] ?? [];
                        $hasHeadings = $block['data']['withHeadings'] ?? false;
                    @endphp

                    <div class="overflow-x-auto my-4">
                        <table class="table w-full" dir="ltr">
                            @if($hasHeadings && count($rows) > 0)
                                <thead>
                                <tr>
                                    @foreach($rows[0] as $cell)
                                        <th class="bg-base-200 font-bold text-sm">{{ $cell }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                @php unset($rows[0]); @endphp
                            @endif

                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td class="text-sm">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @break
                    {{--Quote--}}
                @case('quote')
                    @php
                        $text = $block['data']['text'] ?? '';
                        $caption = $block['data']['caption'] ?? '';
                        $align = $block['data']['alignment'];
                      $align = $align === 'left' ? 'right' : $align;
                    @endphp

                    <blockquote class="border-r-4 pr-4 my-6 text-{{ $align }} border-primary">
                        <p class="text-lg font-medium ">{!! $text !!}</p>
                        @if($caption)
                            <footer class="mt-2 text-sm "> — {{ $caption }}</footer>
                        @endif
                    </blockquote>
                    @break
                    {{--Delimiter--}}
                @case('delimiter')
                    <div class="divider"></div>
                    @break
                    {{--Embed--}}
                @case('embed')
                    @php
                        $src = $block['data']['embed'] ?? '';
                        $caption = $block['data']['caption'] ?? '';

                        // اضافه کردن parent برای Twitch
                        if (str_contains($src, 'twitch.tv')) {
                            $src .= (str_contains($src, '?') ? '&' : '?') . 'parent=' . request()->getHost();
                        }
                    @endphp

                    <div class="my-6 flex flex-col items-center">
                        <div class="w-full aspect-video max-w-3xl">
                            <iframe
                                class="w-full h-full rounded"
                                src="{{ $src }}"
                                allowfullscreen
                                loading="lazy"
                                frameborder="0">
                            </iframe>
                        </div>

                        @if ($caption)
                            <p class="mt-2 text-sm text-gray-500 text-center">{{ $caption }}</p>
                        @endif
                    </div>
                    @break

                @default

                    @break

            @endswitch

        @endforeach

    </div>
</div>
