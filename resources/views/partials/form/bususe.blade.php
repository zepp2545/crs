<?php

$bususes=[
  1=>'利用しない',
  2=>'往復',
  3=>'行きのみ',
  4=>'帰りのみ'
];

?>


<div class="form-group">
  <label for="busUse">Bus Use<span class="badge badge-danger ml-2">Required</span></label>
  <select class="form-control" name="busUse" id="busUse">
    <option selected disabled>Please select</option>
    @foreach($bususes as $key => $value)
      @if(isset($student))
        <option value="{{$key}}" {{$key==$student->bus ? 'selected' : ''}}>{{$value}}</option>
      @else
        <option value="{{$key}}" {{$key==old('busUse') ? 'selected' : ''}}>{{$value}}</option>
      @endif

    @endforeach
  </select>
</div>
