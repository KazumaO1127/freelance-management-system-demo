@extends('layouts.app')

@section('content')
<h1>案件作成</h1>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf
    @include('projects._form')
    <div class="d-flex gap-2">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">戻る</a>
        <button class="btn btn-primary">作成</button>
    </div>
</form>

@endsection
