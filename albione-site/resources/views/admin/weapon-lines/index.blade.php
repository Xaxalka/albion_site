@php($title = 'Управление ветками')
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Управление ветками оружия</h1>
            <p class="text-sm text-slate-600">Создавайте, редактируйте и управляйте ветками оружия, добавляйте общие навыки.</p>
        </div>
        <a href="{{ route('admin.weapon-lines.create') }}" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            + Добавить ветку
        </a>
    </div>

    <form method="GET" class="admin-search-form" action="{{ route('admin.weapon-lines.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Поиск по названию или slug" class="admin-search-input" />
        <button type="submit" class="admin-search-button">Найти</button>
        @if(request('q'))
            <a href="{{ route('admin.weapon-lines.index') }}" class="admin-clear-link">Сбросить</a>
        @endif
    </form>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <colgroup>
                <col class="admin-colgroup-col-30">
                <col class="admin-colgroup-col-20">
                <col class="admin-colgroup-col-12">
                <col class="admin-colgroup-col-12">
                <col class="admin-colgroup-col-12">
                <col class="admin-colgroup-col-14">
            </colgroup>
            <thead class="admin-thead">
                <tr class="admin-th-row">
                    <th class="admin-th">Название</th>
                    <th class="admin-th">Slug</th>
                    <th class="admin-th" style="text-align: center;">Оружие</th>
                    <th class="admin-th" style="text-align: center;">Навыки</th>
                    <th class="admin-th">Обновлено</th>
                    <th class="admin-th" style="text-align: right;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lines as $line)
                    <tr class="admin-tr">
                        <td class="admin-td-ellipsis" style="font-weight: 600;">
                            <a href="{{ route('weapon-lines.show', $line->slug) }}" class="admin-link">{{ $line->name }}</a>
                        </td>
                        <td class="admin-td-ellipsis">{{ $line->slug }}</td>
                        <td class="admin-td" style="text-align: center;">
                            <span class="admin-badge-blue">{{ $line->weapons_count ?? 0 }}</span>
                        </td>
                        <td class="admin-td" style="text-align: center;">
                            <span class="admin-badge-green">{{ $line->line_skills_count ?? 0 }}</span>
                        </td>
                        <td class="admin-td" style="color: #6b7280;">{{ $line->updated_at->format('d.m.Y') }}</td>
                        <td class="admin-td" style="text-align: right;">
                            <div class="admin-actions">
                                <a href="{{ route('admin.weapon-lines.edit', $line->id) }}" class="admin-action-link">Редактировать</a>
                                <span class="admin-muted">·</span>
                                <a href="{{ route('weapon-lines.show', $line->slug) }}" class="admin-muted">Просмотр</a>
                                <span class="admin-muted">·</span>
                                <form method="POST" action="{{ route('admin.weapon-lines.destroy', $line->id) }}" style="display:inline;" onsubmit="return confirm('Вы уверены?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-danger-btn">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="admin-td" style="text-align: center; color: #4b5563; padding: 32px 32px;">
                            <p style="margin-bottom: 8px;">Ветки ещё не созданы.</p>
                            <a href="{{ route('admin.weapon-lines.create') }}" class="admin-action-link">Создать первую ветку</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $lines->links() }}
    </div>
@endsection
