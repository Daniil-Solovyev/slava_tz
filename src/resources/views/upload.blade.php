<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файла</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Загрузка файла</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('progress_url'))
        <div class="alert alert-success">
            {{ session('progress_url') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <h4 class="alert-heading">Ошибки:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Выберите файл (xlsx, xls)</label>
            <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls" required>
            <div class="form-text">
                Поддерживаются только файлы в формате Excel (.xlsx, .xls).
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Загрузить</button>
    </form>
</div>
</body>
</html>