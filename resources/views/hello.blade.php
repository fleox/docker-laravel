@extends('base')

@section('body')
    <h1>Hello world {{ $name }}</h1>

    @foreach ($posts as $post)
        <h1>{{ $post->title }}</h1>
        <h2>{{ $post->category->name }}</h2>
    @endforeach
@endsection