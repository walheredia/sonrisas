@extends('layout')

@section('content')
    <div class="content">
        <header>
            <h1 class="title">Manage Users</h1>

            <ul class="list">
                <li><a href="{{ url('users/add') }}">Add User</a></li>
            </ul>
        </header>

        @if (count($users) > 0)
            <ul class="grid">
                @foreach ($users as $user)
                <li>
                    <div class="content">
                        <div class="content-section two-items">{{ $user->name }}</div>

                        <div class="content-section two-items">{{ $user->email }}</div>
                    </div>

                    <div class="actions">
                        @if ($user->id != Auth::user()->id)
                        <form id="delete-media-form-{{ $user->id }}" method="POST" action="{{ url('users/delete', [$user->id]) }}">
                            {!! csrf_field() !!}

                            <input class="delete-btn btn-link" data-id="{{ $user->id }}" type="submit" value="Delete" />
                        </form>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
            {!! $users->render() !!}
        @else
            <p>Still it has not created any user</p>
        @endif
    </div>

    <script type="text/javascript">
        (function() {
            var buttons = document.getElementsByClassName('delete-btn');

            var i = 0;
            for (; i < buttons.length; i++) {
                buttons[i].addEventListener('click', function(event) {
                    event.preventDefault();
                    var id = event.target.dataset.id;

                    alert(
                        'Delete user',
                        false, 
                        'Are you sure you want to delete the user?',
                        {
                            Accept: function() {
                                document.getElementById('delete-media-form-' + id).submit();
                                closeFancyAlert();
                            },
                            Cancel: function() {
                                closeFancyAlert();
                            },
                        }
                    );
                });
            }
        })();
    </script>
@endsection