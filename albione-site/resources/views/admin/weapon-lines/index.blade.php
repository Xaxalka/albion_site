@php($title = 'Панель веток')
@extends('layouts.app')

@section('content')
    <div class="section-header">
        <div class="eyebrow">Администрирование</div>
        <h1>Панель управления ветками</h1>
        <p>Только администраторы могут изменять ветки оружия, их слаг и описание. Отсюда удобно перейти к редактированию нужной ветки или открыть её на сайте.</p>
        <div class="tags" style="margin-top:12px;">
            <a class="btn" href="{{ route('admin.weapon-lines.create') }}">Добавить новую ветку</a>
        </div>
    </div>

    <section class="panel">
        <div class="subtle-title">Активные ветки</div>
        <p class="muted" style="margin-top:6px;">Ниже список всех веток, доступных для редактирования администраторам. Дата обновления, количество оружия и общих навыков помогают быстро найти нужную линию.</p>

        <div class="card-grid" style="margin-top:16px;">
            @forelse($lines as $line)
                <article class="card">
                    <div class="meta">{{ $line->slug }} · обновлено {{ $line->updated_at->format('d.m.Y') }}</div>
                    <h3 style="margin-top:8px;">{{ $line->name }}</h3>
                    <p class="muted" style="margin-top:8px;">{{ $line->description ?: 'Описание пока не заполнено.' }}</p>

                    <div class="tags" style="margin-top:10px;">
                        <span class="chip">Оружия: {{ $line->weapons_count }}</span>
                        <span class="chip">Общие навыки: {{ $line->line_skills_count }}</span>
                    </div>

                    <div class="tags" style="margin-top:12px;">
                        <a class="btn" href="{{ route('weapon-lines.show', $line->slug) }}">Открыть ветку</a>
                        <a class="btn" href="{{ route('admin.weapon-lines.edit', $line->id) }}">Редактировать</a>
                    </div>
                </article>
            @empty
                <p class="muted">Ветки ещё не созданы. Нажмите «Добавить новую ветку», чтобы начать.</p>
            @endforelse
        </div>

        <div style="margin-top:18px;">
            {{ $lines->links() }}
        </div>
    </section>
@endsection
