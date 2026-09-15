@php
    $canGenerate = $this->canGenerate();
    $canToggle = $this->canToggle();
    $canDestroy = $this->canDestroy();
    $panelId = filament()->getCurrentOrDefaultPanel()->getId();
@endphp
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
    @foreach($records as $item)
        @php
            $urlParams = ['module' => $item->module_name];
            if ((bool) filament()->hasTenancy()) {
               $urlParams['tenant'] = filament()->getTenant();
            }
            $package = $item->github
                ? str($item->github)->remove('https://github.com/')->remove('https://www.github.com/')->toString()
                : null;
            $titles = json_decode((string) $item['name'], true) ?: [];
            $descriptions = json_decode((string) $item['description'], true) ?: [];
            $title = is_array($titles) ? ($titles[app()->getLocale()] ?? collect($titles)->first()) : $titles;
            $description = is_array($descriptions) ? ($descriptions[app()->getLocale()] ?? collect($descriptions)->first()) : $descriptions;
            $actionArguments = ['module' => $item->module_name, 'providers' => $item->providers];
        @endphp

        <div class="bg-white border border-gray-100 dark:border-gray-700 overflow-hidden dark:bg-gray-800 rounded-lg flex flex-col shadow-sm">

            @if($item['placeholder'] && $item['placeholder'] !== 'placeholder.webp')
                <div class="h-40 overflow-hidden">
                    <img class="bg-cover bg-center" src="{{ $item['placeholder'] }}" alt="{{ $title }}" />
                </div>
            @else
                <div class="overflow-hidden flex flex-col rounded-t-lg justify-center items-center h-full py-4" style="background-color: {{ $item['color'] }};">
                    @if($item['icon'])
                        <x-filament::icon :icon="$item['icon']" class="text-white w-12 h-16" />
                    @endif
                </div>
            @endif
            <div class="flex justify-between gap-4 px-4 my-2">
                <div class="w-full">
                    <h1 class="font-bold">{{ $title }}</h1>
                </div>
                @if($package)
                    <div>
                        <img class="w-32" src="https://poser.pugx.org/{{ $package }}/version.svg" alt="Latest Stable Version">
                    </div>
                @endif
            </div>
            <div class="h-30 px-4">
                <p class="text-gray-600 dark:text-gray-300 text-sm h-30 truncate">
                    {{ $description }}
                </p>
                @if($package)
                    <div class="flex justify-start gap-2 mt-3">
                        <div>
                            <img class="h-5" src="https://poser.pugx.org/{{ $package }}/d/total.svg" alt="Downloads">
                        </div>
                        <div>
                            <img class="h-5" src="https://img.shields.io/github/stars/{{ $package }}?style=flat" alt="GitHub Repo stars">
                        </div>
                    </div>
                @endif
            </div>
            <div class="flex justify-between gap-1 my-4 px-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                <div class="flex justify-start w-full gap-2">
                    @if($item['type'] !== 'lib')
                        @if($canGenerate && ! str(module_path($item['module_name']))->contains('vendor'))
                            <x-filament::icon-button
                                icon="heroicon-s-cog"
                                :tooltip="trans('filament-plugins::messages.plugins.actions.generate')"
                                tag="a"
                                :href="route('filament.' . $panelId . '.resources.tables.index', $urlParams)"
                            />
                        @endif
                        @if($canToggle)
                            @if($item->active)
                                {{ ($this->disableAction)($actionArguments) }}
                            @else
                                {{ ($this->activeAction)($actionArguments) }}
                            @endif
                        @endif
                        @if($canDestroy)
                            {{ ($this->deleteAction)($actionArguments) }}
                        @endif
                    @endif
                </div>
                <div class="w-full flex justify-end gap-4">
                    @if($item->github)
                        <x-filament::icon-button
                            icon="heroicon-s-globe-asia-australia"
                            :tooltip="trans('filament-plugins::messages.plugins.actions.github')"
                            :href="$item->github"
                            target="_blank"
                            tag="a"
                        />
                    @endif
                    @if($item->docs)
                        <x-filament::icon-button
                            icon="heroicon-s-document-text"
                            :tooltip="trans('filament-plugins::messages.plugins.actions.docs')"
                            :href="$item->docs"
                            target="_blank"
                            tag="a"
                        />
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
