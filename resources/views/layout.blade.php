<!DOCTYPE html>
<html>
<head>
</head>
<body>    
    @if(session('success'))
        <div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</body>
</html>
