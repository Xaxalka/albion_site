@php($title = __('ui.nav.weapon_lines'))
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">{{ __('ui.catalog.label') }}</div>
    <h1>{{ __('ui.nav.weapon_lines') }}</h1>
    <p>{{ app()->getLocale() === 'ru' ? 'Ветки Albion с общими умениями Q/W и пассивками.' : 'Albion weapon branches with shared Q/W abilities and passives.' }}</p>
</div>

<section class="card-grid">
    @foreach($lines as $line)
        <article class="card">
            <div class="subtle-title">{{ $line->weapons->count() }} {{ app()->getLocale() === 'ru' ? 'оружия' : 'weapons' }}</div>
            <h2><a href="{{ route('weapon-lines.show', $line->slug) }}" style="color:inherit; text-decoration:none;">{{ $line->display_name }}</a></h2>
            <p>{{ \Illuminate\Support\Str::limit($line->display_description, 160) }}</p>
            <div class="tags">
                @foreach($line->weapons as $weapon)
                    <span class="chip">{{ $weapon->display_name }}</span>
                @endforeach
            </div>
            @if(isset($quickBranches[$line->slug]))
                <div class="panel" style="margin-top:12px; padding:12px 14px;">
                    <div class="subtle-title">{{ app()->getLocale() === 'ru' ? 'Основные ветки' : 'Core groups' }}</div>
                    <p class="muted" style="margin-top:6px;">{{ $quickBranches[$line->slug] }}</p>
                </div>
            @endif
        </article>
    @endforeach
</section>
@endsection
