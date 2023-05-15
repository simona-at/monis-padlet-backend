<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Padlet von Simona</title>
    </head>
    <body>
        <ul>
            <h1>{{$padlet->title}}</h1>
            @foreach($padlet->users as $user)
                <li><h3>User: {{$user->first_name}} {{$user->last_name}}</h3>Rolle: {{$user->pivot->user_role}}</li>
            @endforeach
        </ul>
    </body>
</html>
