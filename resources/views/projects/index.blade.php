@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>案件一覧</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">新規</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>タイトル</th>
            <th>クライアント</th>
            <th>単価</th>
            <th>期間</th>
            <th>ステータス</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->title }}</td>
                <td>{{ $project->client_name }}</td>
                <td>{{ number_format($project->unit_price) }}</td>
                <td>{{ optional($project->start_date)->format('Y-m-d') }} ~ {{ optional($project->end_date)->format('Y-m-d') }}</td>
                <td>{{ $project->status }}</td>
                <td class="text-end">
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">編集</a>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline-block" onsubmit="return confirm('削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">削除</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">案件がありません。</td></tr>
        @endforelse
    </tbody>
</table>

{{ $projects->links() }}

@endsection
