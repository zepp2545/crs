@extends('layouts.app')

@section('title')
 {{isset($student) ? 'Edit Student' : 'Register Student'}}
@endsection

@section('content')
<h2>Edit {{$student->jaName}}'s Information</h2>
     
     @include('partials.alerts.error')
     @include('partials.alerts.success')
 
     <div class="card  mt-4 mx-auto">
       <div class="card-header">
         <h3>{{isset($student) ? 'Basic Info' : 'Register Student'}}</h3>
         <ul>
          <li>Basic Infoは生徒情報の変更ができます。</li>
          <li>生徒の受講講座の変更はLesson Infoから行ってください。</li>  
         </ul>
         @if(url()->previous('/trials')===url('/trials'))
          <div div class="alert alert-danger">体験から継続した場合は「受講講座」と「Lesson Start Day」を必ず設定してください。これがLesson Listに反映されます。</div>
         @endif  
       </div>
       <div class="card-body card-default">
         <form action="{{isset($student) ? route('students.update',$student->id) : route('students.store')}}" method="post">
           @csrf
           @if(isset($student))
             @method('PUT')
           @endif

            <div class="form-group">
              <label for="grade">Grade<span class="badge badge-danger ml-2">Required</span></label>
              <select class="form-control" name="grade" id="grade">
                <option disabled selected>Please Select</option>
                @foreach(Config::get('const.grades') as $grade)
                @if(isset($student))
                  <option value="{{$grade}}" {{$grade==$student->grade ? 'selected' : ''}}>{{$grade}}</option>
                @else
                  <option value="{{$grade}}" {{$grade==old('grade') ? 'selected' : ''}}>{{$grade}}</option>
                @endif
                @endforeach
              </select>
            </div>
           <div class="form-group">
              <label for="jaName">氏名 <span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="jaName" id="jaName" class="form-control" value="{{isset($student) ? $student->jaName : old('jaName')}}">
           </div>
           <div class="form-group">
              <label for="kanaName">かな<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="kanaName" id="kanaName" class="form-control" value="{{isset($student) ? $student->kanaName : old('kanaName')}}">
           </div>
           <div class="form-group">
              <label for="enName">Name<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="enName" id="enName" class="form-control" value="{{isset($student) ? $student->enName : old('enName')}}">
           </div>
           <div class="form-group">
              <label for="tel1">Tel 1<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="tel1" id="tel1" class="form-control" value="{{isset($student) ? $student->tel1 : old('tel1')}}">
           </div>
           <div class="form-group">
              <label for="tel2">Tel 2</label>
              <input type="text" name="tel2" id="tel2" class="form-control" value="{{isset($student) ? $student->tel2 : old('tel2')}}">
           </div>
           <div class="form-group">
              <label for="email1">Email 1<span class="badge badge-danger ml-2">Required</span></label>
              <input type="email" name="email1" id="email1" class="form-control" value="{{isset($student) ? $student->email1 : old('email1')}}">
           </div>
           <div class="form-group">
              <label for="email2">Email 2</label>
              <input type="email" name="email2" id="email2" class="form-control" value="{{isset($student) ? $student->email2 : old('email2')}}">
           </div>
           <div class="form-group">
              <label for="address">Address <span class="badge badge-danger ml-2">Required</span>@include('partials.add_place')</label>
              <select class="form-control" name="address" id="address">
                <option selected disabled>Please select</option>
                @foreach($places as $place)
                  @if(isset($student))
                      <option value="{{$place['id']}}" {{$place['id']==$student->address_id ? 'selected' : ''}}>{{$place['name']}}</option>
                  @else
                      <option value="{{$place['id']}}" {{$place['id']==old('address') ? 'selected' : ''}}>{{$place['name']}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="addressDetails">Address Details</label>
              <input type="text" id="addressDetails" name="addDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{isset($student) ? $student->addDetails : old('addDetails')}}">
            </div>
           <div class="form-group">
             <label for="province">Province</label>
             <input type="text" name="province" id="province" class="form-control" placeholder="" value="{{isset($student) ? $student->province : old('province')}}">
           </div>
           <div class="form-group">
             <label for="Note">Note</label>
             <input type="text" name="note" id="note" class="form-control" placeholder="Note" value="{{isset($student) ? $student->note : old('note')}}">
           </div>
           <button type="submit" name="button" class="btn btn-info">Save</button>
         </form>

          <!-- The Modal for add place -->
          <div class="modal" id="add">
            <div class="modal-dialog">
              <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Add Place</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                  <div class="modal-body">
                      <div class='alert alert-success' style="display:none">Place added successfully.</div>
                      <div class="form-group">
                      <label for="name">Name</label>
                      <input class="form-control" type="text" name="name" id="place_name" placeholder="Please type a name of the condo or mubaan">

                      </div>
                      
                      
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                      <button class="btn btn-info" id="place_create" type="button">Create</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

              </div>
            </div>
          </div>
       </div>
       </div>

       <div class="card my-4 mx-auto lesson_info">
         <div class="card-header">
           <h3 class="d-inline-block">Lesson Info</h3>
           @include('partials.addLesson')
         </div>
         <div class="card-body">
         <ul class="list-group list-group-flush">
            @foreach($student->active_lessons as $active_lesson)
              <li class="list-group-item">
                @if($active_lesson->status===9)
                 {{optional($active_lesson->lesson)->name}} <span class="text-danger">(休会中)</span>
                @else
                 {{optional($active_lesson->lesson)->name}}
                @endif
                
                <div class="float-right">
                  <!-- Button to Open the Modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal{{$active_lesson->id}}">
                  Edit
                </button>

                <!-- The Modal -->
                <div class="modal" id="myModal{{$active_lesson->id}}">
                  <div class="modal-dialog">
                    <div class="modal-content">

                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h4 class="modal-title">{{optional($active_lesson->lesson)->name}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>

                      <!-- Modal body -->
                      <form action="{{route('students.lesson_update',['id'=>$active_lesson->id])}}" method="post">
                      @method('put')
                      @csrf
                      <div class="modal-body">
                          <div class="form-group">
                             <label for="lesson{{$active_lesson->id}}">Lesson<span class="badge badge-danger ml-2">Required</span></label>
                             <select name="lesson" id="lesson{{$active_lesson->id}}" class="form-control">
                                @foreach($lessons as $lesson)
                                  @if(isset($student))
                                    <option value="{{$lesson->id}}" {{$lesson->id==$active_lesson->lesson->id ? 'selected' : ''}}>{{$lesson->name}}</option>
                                  @else
                                    <option value="{{$lesson->id}}" {{$lesson->id==old('lesson') ? 'selected' : ''}}>{{$lesson->name}}</option>
                                  @endif
                                @endforeach
                             </select>
                             
                          </div>
                          <div class="form-group">
                              <label for="busUse{{$active_lesson->id}}">Bus Use<span class="badge badge-danger ml-2">Required</span></label>
                              <select class="form-control" name="busUse" id="busUse{{$active_lesson->id}}">
                                <option selected disabled>Please select</option>
                                @foreach(Config::get('const.bususes') as $key => $value)
                                  @if(isset($student))
                                    <option value="{{$key}}" {{$key==$active_lesson->bus ? 'selected' : ''}}>{{$value}}</option>
                                  @else
                                    <option value="{{$key}}" {{$key==old('busUse') ? 'selected' : ''}}>{{$value}}</option>
                                  @endif

                                @endforeach
                              </select>
                          </div>
                          <div class="form-group">
                            <label for="pickup{{$active_lesson->id}}">Pick Up</label>
                            <select class="form-control" name="pickup" id="pickup{{$active_lesson->id}}">
                              <option selected value="0">---</option>
                              @foreach($places as $place)
                                @if(isset($student))
                                    <option value="{{$place['id']}}" {{$place['id']==$active_lesson->pickup_id ? 'selected' : ''}}>{{$place['name']}}</option>
                                @else
                                    <option value="{{$place['id']}}" {{$place['id']==old('pickup') ? 'selected' : ''}}>{{$place['name']}}</option>
                                @endif
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="pickupDetails{{$active_lesson->id}}">Pick Up Details</label>
                            <input type="text" id="pickupDetails{{$active_lesson->id}}" name="pickupDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{isset($student) ? $active_lesson->pickup_details : old('pickupDetails')}}">
                          </div>
                          <div class="form-group">
                            <label for="send{{$active_lesson->id}}">Send</label>
                            <select class="form-control" name="send" id="send{{$active_lesson->id}}">
                              <option selected value="0">---</option>
                              @foreach($places as $place)
                                @if(isset($student))
                                    <option value="{{$place['id']}}" {{$place['id']==$active_lesson->pickup_id ? 'selected' : ''}}>{{$place['name']}}</option>
                                @else
                                    <option value="{{$place['id']}}" {{$place['id']==old('pickup') ? 'selected' : ''}}>{{$place['name']}}</option>
                                @endif
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="sendDetails{{$active_lesson->id}}">Send Details</label>
                            <input type="text" id="sendDetails{{$active_lesson->id}}" name="sendDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{isset($student) ? $active_lesson->send_details : old('sendDetails')}}">
                          </div>
                          <div class="form-group">
                            <label for="status{{$active_lesson->id}}">Status<span class="badge badge-danger ml-2">Required</span></label>
                            <select name="status" id="status{{$active_lesson->id}}" class="form-control">
                              @foreach(config('const.statuses') as $key=>$value)
                                @if($key==7 || $key===8 || $key===9)
                                  @if($key===$active_lesson->status)
                                    <option value="{{$key}}" selected>{{$value}}</option>
                                  @else
                                    <option value="{{$key}}">{{$value}}</option>
                                  @endif

                                @else
                                  @continue
                                @endif
                                
                              @endforeach
                               
                            </select>
                            
                          </div>
                          <div class="form-group">
                            <label for="start_date{{$active_lesson->id}}">Lesson start date</label>
                            <input type="date" id="start_date{{$active_lesson->id}}" name="start_date" class="form-control" value="{{isset($student) ? $active_lesson->start_date : old('start_date')}}">
                          </div>

                          <div class="form-group">
                            <label for="quit_date{{$active_lesson->id}}">Lesson quit date</label>
                            <input type="date" id="quit_date{{$active_lesson->id}}" name="quit_date" class="form-control" value="{{isset($student) ? $active_lesson->quit_date : old('quit_date')}}">
                          </div>
                          <div class="form-group">
                            <label for="bus_note{{$active_lesson->id}}">Note for bus</label>
                            <input type="text" id="bust_note{{$active_lesson->id}}" name="bus_note" class="form-control" value="{{isset($student) ? $active_lesson->note : old('bus_note')}}" placeholder="e.g. how many passengers">
                          </div>
                      
                      </div>

                      <!-- Modal footer -->
                      <div class="modal-footer">
                        <button class="btn btn-info" type="submit">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div>
                      </form>

                    </div>
                  </div>
                </div>
                  <form class="d-inline-block" method="post" action="{{route('students.lesson_delete',['id'=>$active_lesson->id])}}">
                    @csrf 
                    @method('delete')
                    <button class="btn btn-danger lesson_delete" type="submit">Delete</button>
                  </form>
                </div>                
              </li>
            @endforeach
         </ul>
            
         </div>
       
       </div>


@endsection


@section('script')

 <script>
   $(document).ready(function(){

    // add place ajax

    $('#place_create').click(function(){
      let place_name=$('#place_name').val();
      $.ajax({
          type:'POST',
          url:"{{route('settings/places/add_place_ajax')}}",
          data:{name:place_name,_token:"{{csrf_token()}}"}
        }).done(function(data){
          console.log(data);
          $('#place_name').val('');
          $('.modal-body .alert-success').show();
          setTimeout(function(){
              $('.alert-success').slideUp();
            }, 2000);
          $('#address').append("<option selected val="+data.id+">"+data.name+"</option>");
          $('.lesson_info .card-body').prepend("<div class='alert alert-danger'>それぞれのレッスン内のPick Up、Sendも変更してください。</div>")
          
        });
    });


    

   });
 
 </script>

@endsection


