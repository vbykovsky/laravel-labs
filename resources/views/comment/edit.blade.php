@extends('layout')
@section('content')

<form action="/comment/{{$comment->id}}" method="post">
  @csrf
  @METHOD("PUT")
  <div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{$comment->title}}">
  </div>
  <div class="form-group">
    <label for="text">Text</label>
    <textarea name="desc" id="text" cols="30" rows="3" class="form-control">{{$comment->desc}}</textarea>
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection