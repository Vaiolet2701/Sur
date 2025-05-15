@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Управление пользователями</h1>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Дата регистрации</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($user->isBanned())
                            <span class="badge bg-danger">Заблокирован</span>
                        @else
                            <span class="badge bg-success">Активен</span>
                        @endif
                    </td>
                    <td>
                        @if(!$user->isBanned())
                        <a href="{{ route('admin.bans.create', ['user' => $user->id]) }}" class="btn btn-danger">
                            Заблокировать
                        </a>
                        @else
                        <form action="{{ route('admin.bans.destroy', $user->activeBan()) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-success">
                                Разблокировать
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $users->links() }}
</div>
@endsection