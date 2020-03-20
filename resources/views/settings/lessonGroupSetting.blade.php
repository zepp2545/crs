<div class="{{session('lessonGroup')?'tab-pane active':'tab-pane'}}" id="lessonGroupContent">
    @include('partials.alerts.success')
    @include('partials.alerts.error')

    <h2>Lesson Group Setting</h2>

    <ul class="mt-4">
    <li>Lesson GroupはWaiting ListのLessonに表示されます。</li>
    </ul>

    <div class="row d-flex justify-content-end">
    <button class="btn btn-primary btn-sm mr-3" data-toggle="modal" data-target="#addGroup">Add Lesson Group</button>
    </div>

    <!-- The Modal -->
    <div class="modal" id="addGroup">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add Lesson Grounp</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <form action="{{route('lessonGroup.store')}}" method="post">
        @csrf
        <div class="modal-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" placeholder="Please type a name of the lesson group">
            </div>
            <div class="form-group">
                <label for="kana">Kana Name</label>
                <input class="form-control" type="text" name="kana" id="kana" placeholder="Please type a kana name of the lesson group">
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

    @foreach($lessonGroups as $lesson)
    
        <li class="list-group-item d-flex justify-content-between">
        {{$lesson->name}}
        <div class="buttons">

            <button class="btn btn-primary" data-toggle="modal" data-target="#lessonGroup{{$lesson->id}}">Edit</button>
            <form action="{{route('lessonGroup.destroy',['id'=>$lesson->id])}}" method="post" class="d-inline-block">
            @csrf 
            @method('delete')
            <button class="btn btn-danger lesson_group_delete_btn" type="submit">Delete</button>
            </form>
            
                <!-- The Modal -->
                <div class="modal" id="lessonGroup{{$lesson->id}}">
                    <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                        <h4 class="modal-title">{{$lesson->name}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <form action="{{route('lessonGroup.update',['id'=>$lesson->id])}}" method="post">
                        @method('put')
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{$lesson->name}}">

                            </div>
                            <div class="form-group">
                                <label for="kana">Kana Name</label>
                                <input class="form-control" type="text" name="kana" id="kana" value="{{$lesson->kana}}" placeholder="Please type a kana name of the lesson group">
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