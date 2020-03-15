<div class="{{session('lessonGroup')?'tab-pane':'tab-pane active'}}" id="lesson">
    @include('partials.alerts.success')
    @include('partials.alerts.error')
    
    <h2>Lessons Setting</h2>

    <ul class="mt-4">
    <li><span class="danger">新しい講座を作成する場合は必ずAdd Lessonから作成してください。（Editから既存の講座を変更してしまうと生徒情報のレッスンも変更されてしまいます。Editは講座名の変更、レッスン日、レッスン時間、定員の変更のみに使用してください。）</span></li>
    </ul>

    <div class="row d-flex justify-content-end">
    <button class="btn btn-primary btn-sm mr-3" data-toggle="modal" data-target="#add">Add Lesson</button>
    </div>

    <!-- The Modal -->
    <div class="modal" id="add">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add Lesson</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <form action="{{route('settings.lessons.store')}}" method="post">
        @csrf
        <div class="modal-body">
            <ul>
            <option class="mb-2">数字、記号は半角で入力してください。</option>
    　　　　　　<option class="mb-2">Timeは必ずhh:mm-hh:mmのフォーマットで入力してくだい。</option>
            </ul>
        　
            <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" id="name" placeholder="Please type a name of the lesson">

            </div>

            <div class="form-group">
            <label for="day">day</label>
            <select class="form-control" name="day" id="day">
            <option selected disabled>Please select Lesson day</option>
            @foreach(config('const.days') as $key=>$value)

                <option value="{{$key}}">{{$value}}</option>

            @endforeach
            </select>
            
            </div>

            <div class="form-group">
            <label for="time">Time</label>
            <input class="form-control" type="text" name="time" id="time" placeholder="hh::mm-HH::MM">
            </div>

            <div class="form-group">
            <label for="capacity">Capacity</label>
            <input class="form-control" type="text" name="capacity" id="capacity" placeholder="半角で入力してください。">
            </div>
            
            
            
            
            
        
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button class="btn btn-info" type="submit">Create</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>

        </div>
    </div>
    </div>

    <ul class="list-group mt-4">

    @foreach($lessons as $lesson)
    
        <li class="list-group-item d-flex justify-content-between">
        {{$lesson->name}}
        <div class="buttons">

            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal{{$lesson->id}}">Edit</button>
            <form action="{{route('settings.lessons.delete',['id'=>$lesson->id])}}" method="post" class="d-inline-block">
            @csrf 
            @method('delete')
            <button class="btn btn-danger" type="submit">Delete</button>
            </form>
            
                <!-- The Modal -->
                <div class="modal" id="myModal{{$lesson->id}}">
                    <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                        <h4 class="modal-title">{{$lesson->name}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <form action="{{route('settings.lessons.update',['id'=>$lesson->id])}}" method="post">
                        @method('put')
                        @csrf
                        <div class="modal-body">
                        <ul>
                            <option class="mb-2">数字、記号は半角で入力してください。</option>
    　　　　　　　　　　　　　<option class="mb-2">Timeは必ずhh:mm-hh:mmのフォーマットで入力してくだい。</option>
                        </ul>
                        　
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{$lesson->name}}">

                        </div>

                        <div class="form-group">
                            <label for="day">day</label>
                            <select class="form-control" name="day" id="day">
                            @foreach(config('const.days') as $key=>$value)

                            <option value="{{$key}}" {{$key===$lesson->day ? 'selected' : ''}}>{{$value}}</option>

                            @endforeach
                            </select>
                            
                        </div>

                        <div class="form-group">
                            <label for="time">Time</label>
                            <input class="form-control" type="text" name="time" id="time" value="{{$lesson->time}}">
                        </div>

                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <input class="form-control" type="text" name="capacity" id="capacity" placeholder="半角で入力してください。" value="{{$lesson->capacity}}">
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
        
        </li>

    @endforeach


    </ul>
</div>