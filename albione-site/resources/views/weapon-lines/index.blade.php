@php($title = 'Ветки оружий')
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">Арсенал</div>
    <h1>Ветки оружий</h1>
    <p>Albion-ветки с общими умениями Q/W и пассивками. Атмосфера тёмного кодекса перенесена на весь сайт.</p>
</div>

<section class="card-grid">
    @foreach($lines as $line)
        <article class="card">
            <div class="subtle-title">{{ $line->weapons->count() }} оружия</div>
            <h2><a href="{{ route('weapon-lines.show', $line->slug) }}" style="color:inherit; text-decoration:none;">{{ $line->name }}</a></h2>
            <p>{{ \Illuminate\Support\Str::limit($line->description, 160) }}</p>
            <div class="tags">
                @foreach($line->weapons as $weapon)
                    <span class="chip">{{ $weapon->name }}</span>
                @endforeach
            </div>
            @if(isset($quickBranches[$line->name]))
                <div class="panel" style="margin-top:12px; padding:12px 14px;">
                    <div class="subtle-title">Основные ветки</div>
                    <p class="muted" style="margin-top:6px;">{{ $quickBranches[$line->name] }}</p>
                </div>
            @endif
        </article>
    @endforeach
</section>
@endsection
