@php($title = 'Управление оружием')
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Управление оружием</h1>
            <p class="text-sm text-slate-600">Создавайте и редактируйте оружие, назначайте ветки и навыки.</p>
        </div>
        <a href="{{ route('admin.weapons.create') }}" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            + Добавить оружие
        </a>
    </div>

    <form method="GET" class="admin-search-form" action="{{ route('admin.weapons.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Поиск по названию или slug" class="admin-search-input" />
        <button type="submit" class="admin-search-button">Найти</button>
        @if(request('q'))
            <a href="{{ route('admin.weapons.index') }}" class="admin-clear-link">Сбросить</a>
        @endif
    </form>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <colgroup>
                <col class="admin-colgroup-col-28">
                <col class="admin-colgroup-col-20">
                <col class="admin-colgroup-col-18">
                <col class="admin-colgroup-col-14">
                <col class="admin-colgroup-col-20">
            </colgroup>
            <thead class="admin-thead">
                <tr class="admin-th-row">
                    <th class="admin-th">Название</th>
                    <th class="admin-th">Ветка</th>
                    <th class="admin-th">Slug</th>
                    <th class="admin-th">Обновлено</th>
                    <th class="admin-th" style="text-align: right;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($weapons as $weapon)
                    <tr class="admin-tr">
                        <td class="admin-td-ellipsis" style="font-weight: 600;">
                            <a href="{{ route('weapons.show', $weapon->slug) }}" class="admin-link">{{ $weapon->name }}</a>
                        </td>
                        <td class="admin-td-ellipsis">
                            @if($weapon->weaponLine)
                                <span class="admin-pill admin-pill-light-blue">{{ $weapon->weaponLine->name }}</span>
                            @else
                                <span style="color: #9ca3af; font-style: italic;">Не назначена</span>
                            @endif
                        </td>
                        <td class="admin-td-ellipsis">{{ $weapon->slug }}</td>
                        <td class="admin-td" style="color: #6b7280;">{{ $weapon->updated_at->format('d.m.Y') }}</td>
                        <td class="admin-td" style="text-align: right;">
                            <div class="admin-actions">
                                <a href="{{ route('admin.weapons.edit', $weapon->id) }}" class="admin-action-link">Редактировать</a>
                                <span class="admin-muted">·</span>
                                <a href="{{ route('weapons.show', $weapon->slug) }}" class="admin-muted">Просмотр</a>
                                <span class="admin-muted">·</span>
                                <form method="POST" action="{{ route('admin.weapons.destroy', $weapon->id) }}" style="display:inline;" onsubmit="return confirm('Вы уверены?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-danger-btn">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="admin-td" style="text-align: center; color: #4b5563; padding: 32px 32px;">
                            <p style="margin-bottom: 8px;">Оружие ещё не добавлено.</p>
                            <a href="{{ route('admin.weapons.create') }}" class="admin-action-link">Добавить первое оружие</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $weapons->links() }}
    </div>
@endsection
