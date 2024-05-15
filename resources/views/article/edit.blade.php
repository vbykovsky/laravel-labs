@extends('layout')
@section('content')


  @if($errors->any())
  <div class="alert-danger">
    <ul>
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul>
  </div>
  @endif


<form action="/article/{{$article->id}}" method="post">
  @METHOD('PUT')
  @csrf
  <div class="form-group">
    <label for="date">Date</label>
    <input type="date" class="form-control" id="date" name="date" value="{{$article->date}}">
  </div>
  <div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{$article->title}}">
  </div>
  <div class="form-group">
    <label for="short_desc">Shortdesc</label>
    <input type="text" class="form-control" id="short_desc" name="short_desc" value="{{$article->short_desc}}">
  </div>
  <div class="form-group">
    <label for="text">Text</label>
    <textarea name="text" id="text" cols="30" rows="10" class="form-control">{{$article->text}}</textarea>
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection