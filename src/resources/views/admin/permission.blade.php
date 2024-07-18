<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    @routes
    @livewireStyles
    {{-- @powerGridStyles  --}}    

</head>
<body>


    <div class="container mt-3">
        <div class="row">

        <h3>Assigning Permission and Auditing</h3>
        </div>


        <div class="row">
            <div class="col-12  mt-3">

            {{-- @include('flash::message') --}}
                <!-- @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif -->

                @if(flash()->message)
                    <div>
                        {{ flash()->message }}
                    </div>
                @endif

            <form method="POST" action="{{ route('grant.permission') }}">
                @csrf

                <label for="role">Role:</label>
                <select name="role" class="form-control mb-3">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>

                <label for="permission">Permission:</label>
                <select name="permission" class="form-control mb-3">
                    @foreach($permissions as $permission)
                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                    @endforeach
                </select>

                <button class="btn btn-primary" type="submit">Grant Permission</button>
            </form>


            <div class="form-group mt-4 mb-4">
                <div class="captcha">
                    <span>{!! captcha_img() !!}</span>
                    {{--<span class="input-group-addon">{{ HTML::image(Captcha::img()) }}</span> --}}
    
                    <button type="button" class="btn btn-danger" class="reload" id="reload">
                        &#x21bb;
                    </button>
                </div>
            </div>
            <div class="form-group mb-4">
                <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
            </div>


            <div class="container mt-5 mb-5">
                <div class="row">
                    <div class="col-12">
                        <livewire:user/>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                
            @foreach($authors as $author)
                <tr>
                    <td>{{ $author->first_name }}</td>
                    <td>{{ $author->books_count }}</td>
                    <!-- use this instead of below for better performance(N+1 query package) -->
                   {{-- <td>{{ $author->books()->count() }}</td> --}}
                </tr>
            @endforeach
            </div>


            </div>
        </div>
    </div>
    @livewireScripts
    @powerGridScripts

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


    <script>
        //  access the exposed routes in JavaScript
        const usersRoute = route('browser.shot'); 
        console.log(usersRoute);

        $.ajax({
                type: 'GET',
                url: usersRoute,
                success: function (data) {
                    console.log(data.users);                
                }
            });
    </script>


    <script type="text/javascript">
        $('#reload').click(function () {
            $.ajax({
                type: 'GET',
                url: 'refresh_captcha',
                success: function (data) {

                    $(".captcha span").html( data.captcha); // Update the image source
                }
            });
        });
    </script>


</body>
</html>