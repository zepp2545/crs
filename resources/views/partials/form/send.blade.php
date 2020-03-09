<div class="form-group">
  <label for="send">Send</label>
  <select class="form-control" name="send" id="send">
    <option selected disabled>Please select</option>
    @foreach($places as $place)
      @if(isset($student))
          <option value="{{$place['id']}}" {{$place['id']==$student->send_id ? 'selected' : ''}}>{{$place['name']}}</option>
      @else
          <option value="{{$place['id']}}" {{$place['id']==old('send') ? 'selected' : ''}}>{{$place['name']}}</option>
      @endif
    @endforeach
  </select>
</div>
<div class="form-group">
  <label for="sendDetails">Send Details</label>
  <input type="text" id="sendDetails" name="sendDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{isset($student) ? $student->send_details : old('pickDetails')}}">
</div>
