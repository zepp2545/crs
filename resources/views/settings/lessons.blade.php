@extends('layouts.app')

@section('title')
  Lessons Setting
@endsection

@section('content')
<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><a class="{{session('lessonGroup')?'nav-link' :'nav-link active'}}" href="#lesson" data-toggle="tab">Lesson</a></li>
  <li class="nav-item"><a class="{{session('lessonGroup')?'nav-link active' : 'nav-link'}}" href="#lessonGroup" data-toggle="tab">Lesson Group</a></li>
</ul>

<div class="tab-content">
  @include('settings.lessonsSetting')
  @include('settings.lessonGroupSetting')
</div>
  
@endsection



@section('script')

<script>

 

</script>

@endsection