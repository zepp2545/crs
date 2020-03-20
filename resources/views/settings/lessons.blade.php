@extends('layouts.app')

@section('title')
  Lessons Setting
@endsection

@section('content')
<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><a class="{{session('lessonGroup')?'nav-link' :'nav-link active'}}" href="#lessonContent" data-toggle="tab">Lesson</a></li>
  <li class="nav-item"><a class="{{session('lessonGroup')?'nav-link active' : 'nav-link'}}" href="#lessonGroupContent" data-toggle="tab">Lesson Group</a></li>
</ul>

<div class="tab-content">
  @include('settings.lessonsSetting')
  @include('settings.lessonGroupSetting')
</div>
  
@endsection



@section('script')

<script>

$(document).ready(function(){

//show dialog when deleting lesson group.
$('.lesson_group_delete_btn').click(function(){

    if(!confirm('Are you sure you want to delete this lesson group? If you delete this lesson, this will cause some problems in lists.')){
        return false;
    }

});

//show dialog when deleting lesson.
$('.lesson_delete_btn').click(function(){

    if(!confirm('Are you sure you want to delete this lesson? If you delete this lesson, this will cause some problems in lists.')){
        return false;
    }

});




});

</script>

@endsection