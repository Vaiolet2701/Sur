@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Управление записями на курсы</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <!-- Блок новых заявок -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="h5 mb-0">Новые заявки (ожидают рассмотрения)</h2>
        </div>
        <div class="card-body">
            @if($pendingEnrollments->isEmpty())
                <div class="alert alert-info">Нет новых заявок</div>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пользователь</th>
                            <th>Курс</th>
                            <th>Цена</th>
                            <th>Телефон</th>
                            <th>Возраст</th>
                            <th>Сообщение</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingEnrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->pivot_id }}</td>
                            <td>{{ $enrollment->name }} ({{ $enrollment->email }})</td>
                            <td>{{ $enrollment->title }}</td>
                            <td>{{ number_format($enrollment->price, 2) }} руб.</td>
                            <td>{{ $enrollment->phone }}</td>
                            <td>{{ $enrollment->age }}</td>
                            <td>{{ $enrollment->message ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d.m.Y H:i') }}</td>
                            <td>
                                <!-- Форма для принятия -->
                                <form action="{{ route('admin.enrollments.approve', $enrollment->pivot_id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Вы уверены?')">Принять</button>
                                </form>

                                <!-- Кнопка для открытия модального окна -->
                                <button class="btn btn-danger btn-sm" 
                                        onclick="loadRejectForm('{{ $enrollment->pivot_id }}')">
                                    Отклонить
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Все заявки -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h2 class="h5 mb-0">Все заявки</h2>
        </div>
        <div class="card-body">
            @if($allEnrollments->isEmpty())
                <div class="alert alert-info">Нет заявок</div>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пользователь</th>
                            <th>Курс</th>
                            <th>Цена</th>
                            <th>Статус</th>
                            <th>Телефон</th>
                            <th>Возраст</th>
                            <th>Дата</th>
                            <th>Причина отказа</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allEnrollments as $enrollment)
                        <tr class="@if($enrollment->status == 'rejected') table-danger @elseif($enrollment->status == 'in_progress') table-success @endif">
                            <td>{{ $enrollment->pivot_id }}</td>
                            <td>{{ $enrollment->name }} ({{ $enrollment->email }})</td>
                            <td>{{ $enrollment->title }}</td>
                            <td>{{ number_format($enrollment->price, 2) }} руб.</td>
                            <td>
                                @if($enrollment->status == 'pending')
                                    <span class="badge badge-warning">Ожидает</span>
                                @elseif($enrollment->status == 'in_progress')
                                    <span class="badge badge-success">Принята</span>
                                @elseif($enrollment->status == 'rejected')
                                    <span class="badge badge-danger">Отказано</span>
                                @else
                                    <span class="badge badge-secondary">Завершена</span>
                                @endif
                            </td>
                            <td>{{ $enrollment->phone }}</td>
                            <td>{{ $enrollment->age }}</td>
                            <td>{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d.m.Y H:i') }}</td>
                            <td>{{ $enrollment->rejection_reason ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- Единое модальное окно для отклонения -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="rejectModalContent"></div> <!-- Сюда будет загружена форма -->
    </div>
</div>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 5px;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover {
        color: black;
    }
</style>

@endsection

@push('scripts')
<script>
// Функция для загрузки формы отклонения через AJAX
function loadRejectForm(enrollmentId) {
    const url = `/admin/enrollments/${enrollmentId}/reject-form`;
    const modalContent = document.getElementById('rejectModalContent');
    // Показываем загрузку
    modalContent.innerHTML = '<div class="text-center p-4">Загрузка формы...</div>';
    document.getElementById('rejectModal').style.display = 'block';
    
    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Ошибка сервера: ${response.status}`);
        }
        return response.text();
    })
    .then(data => {
        modalContent.innerHTML = data;
        initRejectForm();
    })
    .catch(error => {
        console.error('Ошибка:', error);
        modalContent.innerHTML = `
            <div class="alert alert-danger">
                <h4>Ошибка!</h4>
                <p>${error.message}</p>
                <button onclick="closeModal()" class="btn btn-secondary">Закрыть</button>
            </div>
        `;
    });
}

function initRejectForm() {
    const form = document.getElementById('rejectForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        submitButton.disabled = true;
        submitButton.textContent = 'Отправка...';
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                window.location.reload();
            } else {
                alert(data.message || 'Произошла ошибка');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при отправке формы');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
    });
}

// Закрытие модального окна
function closeModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

// Закрытие при клике вне модального окна
window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
@endpush