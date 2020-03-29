<div class="float-right">
        <!-- Button to Open the Modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Add Lesson
    </button>

    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Add Lesson</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <form action="{{route('students.student_lesson_store',['id'=>$student->id])}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="lesson">Lesson<span class="badge badge-danger ml-2">Required</span></label>
                    <select name="lesson" id="lesson" class="form-control">
                    @foreach($lessons as $lesson)
                        <option value="{{$lesson->id}}" {{$lesson->id==old('lesson') ? 'selected' : ''}}>{{$lesson->name}}</option>
                    @endforeach
                    </select>
                    
                </div>
                <div class="form-group">
                    <label for="busUse">Bus Use<span class="badge badge-danger ml-2">Required</span></label>
                    <select class="form-control" name="busUse" id="busUse">
                    <option selected disabled>Please select</option>
                    @foreach(Config::get('const.bususes') as $key => $value)

                        <option value="{{$key}}" {{$key==old('busUse') ? 'selected' : ''}}>{{$value}}</option>


                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                <label for="pickup">Pick Up</label>
                <select class="form-control" name="pickup" id="pickup">
                    <option selected value="0">---</option>
                    @foreach($places as $place)

                        <option value="{{$place['id']}}" {{$place['id']==old('pickup') ? 'selected' : ''}}>{{$place['name']}}</option>

                    @endforeach
                </select>
                </div>
                <div class="form-group">
                <label for="pickupDetails">Pick Up Details</label>
                <input type="text" id="pickupDetails" name="pickupDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{old('pickupDetails')}}">
                </div>
                <div class="form-group">
                <label for="send">Send</label>
                <select class="form-control" name="send" id="send">
                    <option selected value="0">---</option>
                    @foreach($places as $place)
                
                        <option value="{{$place['id']}}" {{$place['id']==old('pickup') ? 'selected' : ''}}>{{$place['name']}}</option>
                    @endforeach
                </select>
                </div>
                <div class="form-group">
                <label for="sendDetails">Send Details</label>
                <input type="text" id="sendDetails" name="sendDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{old('sendDetails')}}">
                </div>
                <div class="form-group">
                <label for="status">Status<span class="badge badge-danger ml-2">Required</span></label>
                <select name="status" id="status" class="form-control">
                    @foreach(config('const.statuses') as $key=>$value)
                    @if($key==7 || $key===8 || $key===9)
                        @if($key==8)
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
                <label for="start_date">Lesson start date</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{old('start_date')}}">
                </div>

                <div class="form-group">
                <label for="quit_date">Lesson quit date</label>
                <input type="date" id="quit_date" name="quit_date" class="form-control" value="{{old('quit_date')}}">
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
</div>  