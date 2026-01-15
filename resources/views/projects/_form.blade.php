<div class="mb-3">
    <label class="form-label">タイトル</label>
    <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" class="form-control">
    @error('title')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">クライアント</label>
    <input type="text" name="client_name" value="{{ old('client_name', $project->client_name ?? '') }}"
        class="form-control">
    @error('client_name')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">単価</label>
    <input type="number" name="unit_price" value="{{ old('unit_price', $project->unit_price ?? 0) }}"
        class="form-control">
    @error('unit_price')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">開始日</label>
        <input type="date" name="start_date"
            value="{{ old('start_date', optional($project->start_date)->format('Y-m-d') ?? '') }}" class="form-control">
        @error('start_date')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">終了日</label>
        <input type="date" name="end_date"
            value="{{ old('end_date', optional($project->end_date)->format('Y-m-d') ?? '') }}" class="form-control">
        @error('end_date')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">ステータス</label>
    <select name="status" class="form-select">
        @foreach ($statuses as $value => $label)
            <option value="{{ $value }}" @if (old('status', $project->status ?? '') === $value) selected @endif>{{ $label }}
            </option>
        @endforeach
    </select>
    @error('status')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">メモ</label>
    <textarea name="memo" class="form-control" rows="4">{{ old('memo', $project->memo ?? '') }}</textarea>
    @error('memo')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>
