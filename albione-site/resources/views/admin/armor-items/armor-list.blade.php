@php($title = 'Управление броней')
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-tables.css') }}">
@endpush

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Управление броней</h1>
            <p class="text-sm text-slate-600">Создавайте и редактируйте предметы брони (ткань/кожа/пластины).</p>
        </div>
        <a href="{{ route('admin.armor-items.create') }}" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            + Добавить броню
        </a>
    </div>

    <div class="admin-scope">
    <form method="GET" class="admin-search-form" action="{{ route('admin.armor-items.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Поиск по названию или slug" class="admin-search-input" />
        <button type="submit" class="admin-search-button">Найти</button>
        @if(request('q'))
            <a href="{{ route('admin.armor-items.index') }}" class="admin-clear-link">Сбросить</a>
        @endif
    </form>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <colgroup>
                <col class="admin-colgroup-col-28">
                <col class="admin-colgroup-col-18">
                <col class="admin-colgroup-col-14">
                <col class="admin-colgroup-col-16">
                <col class="admin-colgroup-col-12">
                <col class="admin-colgroup-col-12">
            </colgroup>
            <thead class="admin-thead">
                <tr class="admin-th-row">
                    <th class="admin-th">Название</th>
                    <th class="admin-th">Материал</th>
                    <th class="admin-th">Слот</th>
                    <th class="admin-th">Slug</th>
                    <th class="admin-th">Обновлено</th>
                    <th class="admin-th" style="text-align: right;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="admin-tr">
                        <td class="admin-td-ellipsis" style="font-weight: 600;">
                            <a href="{{ route('armor.show', $item->slug) }}" class="admin-link">{{ $item->name }}</a>
                        </td>
                        <td class="admin-td">
                            <span class="admin-pill admin-pill-purple">{{ \App\Models\ArmorItem::materialLabel($item->material) }}</span>
                        </td>
                        <td class="admin-td">
                            <span class="admin-pill admin-pill-green">{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }}</span>
                        </td>
                        <td class="admin-td-ellipsis">{{ $item->slug }}</td>
                        <td class="admin-td" style="color: #6b7280;">{{ $item->updated_at->format('d.m.Y') }}</td>
                        <td class="admin-td" style="text-align: right;">
                            <div class="admin-actions admin-actions-column">
                                <a href="{{ route('admin.armor-items.edit', $item->id) }}" class="admin-action-link">Редактировать</a>
                                <span class="admin-muted"></span>
                                <a href="{{ route('armor.show', $item->slug) }}" class="admin-muted">Просмотр</a>
                                <span class="admin-muted"></span>
                                <form method="POST" action="{{ route('admin.armor-items.destroy', $item->id) }}" style="display:inline;" onsubmit="return confirm('Вы уверены?')">
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
                            <p style="margin-bottom: 8px;">Броня ещё не добавлена.</p>
                            <a href="{{ route('admin.armor-items.create') }}" class="admin-action-link">Добавить первую броню</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
@endsection
