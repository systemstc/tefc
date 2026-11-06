@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @foreach($sections as $section)
        @include('cms.sections.' . $section->type, ['data' => $section->content, 'title' => $section->title])
    @endforeach
@endsection
