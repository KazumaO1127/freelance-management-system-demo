<div class="mb-3">
    <label class="form-label">タイトル</label>
    <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">クライアント</label>
    <input type="text" name="client_name" value="{{ old('client_name', $project->client_name ?? '') }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">単価</label>
    <input type="number" name="unit_price" value="{{ old('unit_price', $project->unit_price ?? 0) }}" class="form-control">
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label">開始日</label>
    <input type="date" name="start_date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d') ?? '') }}" class="form-control">
  </div>
  <div class="col-md-6 mb-3">
    <label class="form-label">終了日</label>
    <input type="date" name="end_date" value="{{ old('end_date', optional($project->end_date)->format('Y-m-d') ?? '') }}" class="form-control">
  </div>
</div>

<div class="mb-3">
    <label class="form-label">ステータス</label>
    <select name="status" class="form-select">
        @php
            $statuses = ['contact','negotiation','contracted','working','completed'];
        @endphp
        @foreach($statuses as $s)
            <option value="{{ $s }}" @if(old('status', $project->status ?? '') === $s) selected @endif>{{ $s }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">メモ</label>
    <textarea name="memo" class="form-control" rows="4">{{ old('memo', $project->memo ?? '') }}</textarea>
</div>
