@extends('layouts.app')

@section('content')
<h1>案件編集</h1>

<form action="{{ route('projects.update', $project) }}" method="POST">
    @csrf
    @method('PUT')
    @include('projects._form')
    <div class="d-flex gap-2">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">戻る</a>
        <button class="btn btn-primary">更新</button>
    </div>
</form>

@endsection
