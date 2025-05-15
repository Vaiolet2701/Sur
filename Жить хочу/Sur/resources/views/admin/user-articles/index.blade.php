@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Статьи пользователей на проверку</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Название</th>
                        <th>Автор</th>
                        <th>Контент</th>
                        <th>Изображение</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->user->name }}</td>
                            <td>{{ Str::limit($article->content, 100) }}</td>
                            <td>
                                @if($article->image_path)
                                    <img src="{{ asset($article->image_path) }}" alt="Изображение статьи" class="img-thumbnail" width="100">
                                @else
                                    Нет изображения
                                @endif
                            </td>
                            <td class="d-flex">
                                <!-- Форма для одобрения -->
                                <form action="{{ route('admin.user-articles.approve', $article->id) }}" method="POST" class="mr-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Одобрить
                                    </button>
                                </form>
                                
                                <!-- Форма для удаления -->
                                <form action="{{ route('admin.user-articles.destroy', $article->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">
                                        <i class="fas fa-trash"></i> Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Нет статей для модерации</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .img-thumbnail {
            max-height: 100px;
            object-fit: cover;
        }
        .d-flex {
            display: flex;
        }
        .mr-2 {
            margin-right: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script>
        // Подтверждение удаления
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[method="DELETE"]');
            
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Вы уверены, что хотите удалить эту статью?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush