<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @vite('resources/js/app.js')

</head>

<body>
    <div class="container mt-5 p-5 bg-light rounded shadow shadow-lg bg-body rounded bg-white ">
        <div class="row">
            <div class="col-4">
                <h5>Users</h5>
                <ul class="list-group list-group-flush" id="users-list">
                    @foreach ($users as $user)
                    <li class="list-group-item">
                        <a href="/messageTo/{{ $user->id }}">
                            {{ $user->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-8">
                <h5 class="mb-3 text-center fw-bold text-primary ">Your Name : {{ auth()->user()->name }}</h5>
                <h5 class="mb-3 text-center fw-bold text-primary  ">Chat with {{ $chattinguser->name }}</h5>

                <form action="{{ route('message.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <input type="text" class="form-control" name="text" id="message">
                        <input type="hidden" name="chat_channel_id" value="{{ $chatChannel->id }}">
                        <input type="file" name="file_path" class="form-control mt-3">
                    </div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>

                <ul class="list-group list-group-flush" id="messages-container">
                    @forelse ($messages as $message)
                    <li class="list-group-item">
                        {{ $message->text }}
                        @if ($message->file_path)
                        <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank">Download File</a>
                        @endif
                    </li>
                    @empty
                    <li class="list-group-item text-muted">No messages yet.</li>
                    @endforelse
                </ul>

            </div>
        </div>
    </div>
    <script>
        const chatChannel_id = @json($chatChannel->id);
        // console.log(chatChannel_id);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>