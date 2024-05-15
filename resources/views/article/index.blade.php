@extends('layout')
@section('content')
<table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Title</th>
      <th scope="col">ShortDesc</th>
      <th scope="col">Text</th>
      <th scope="col">Author</th>
    </tr>
  </thead>
  <tbody>
    @foreach($articles as $article)
    <tr>
      <th scope="row">{{$article->date}}</th>
      <td><a href="/article/{{$article->id}}">{{$article->title}}</a></td>
      <td>{{$article->short_desc}}</td>
      <td>{{$article->text}}</td>
      <td>{{$article->user->name}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
{{$articles->links()}}
@endsection