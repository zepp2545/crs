@extends('layouts.app')


@section('content')
@include('partials.alerts.success')
  <h2>Student List</h2>
  <div class="searchContent my-4">
    <form action="{{route('students.search')}}" method="post" id="name_search"> <!--Dot't forget adding double curly braces to display. -->
        @csrf

        <div class="form-group">
            <input type="text" name="searched_name" placeholder="Search a student with the name" id="nameSearchInput" aria-label="Search" class="form-control">
            <input type="hidden" name="type_of_list" value="students">
        </div>

        <input type="hidden" name="submit">

    </form>
  </div>

  <div class="customerList">
              <div class="studentList">
                  <table class="table table table-bordered">
                    <thead class="thead-dark">
                      <tr>
                          <th class="number">No.</th>
                          <th class='grade'>Grade</th>
                          <th class='jaName'>名前</th>
                          <th class='kanaName'>カナ</th>
                          <th class="enName">Name</th>
                          <th class='lesson'>Lesson</th>
                          <th class="tel1">tel1</th>
                          <th class="tel2">tel2</th>
                          <th class='email email'>Email</th>
                          <th class='email2 email'>Email2</th>
                          <th class='province'>Province</th>
                          <th class="adress">Address</th>
                          <th class="note">Note</th>
                          <th class='edit'>Edit</th>
                      </tr>
                    </thead>

                    <tbody>
                       <?php $i=1; ?>
                      @foreach($students as $student)
                        <tr data-id="{{$student->id}}">
                          <td class="number">{{$i}}</td>
                          <td class="grade">{{$student->grade}}</td>
                          <td class="jaName">{{$student->jaName}}</td>
                          <td class="kanaName">{{$student->kanaName}}</td>
                          <td class="enName">{{$student->enName}}</td>
                          <td class="lesson">
                          
                          @foreach($student->active_lessons as $student_lesson)
                             {{optional($student_lesson->lesson)->name}}
                             @foreach(Config::get('const.bususes') as $key=>$value)
                              {{$key==optional($student_lesson)->bus ? '【'.$value.'】' : ''}}
                             @endforeach
                             <br>
                          @endforeach
                          
                          
                          </td>
                          <td class="tel1">{{$student->tel1}}</td>
                          <td class="tel2">{{$student->tel2}}</td>
                          <td class="email1 email">{{$student->email1}}</td>
                          <td class="email2 email">{{$student->email2}}</td>
                          <td class="province">{{$student->province}}</td>
                          <td class="address">{{optional($student->address)->name}}<br>{{$student->addDetails}}</td>
                          <td class="note">{{$student->note}}</td>
                          <td class="edit"><a class="btn btn-primary text-white" href="{{route('students.edit',['id'=> $student->id])}}">Edit</a></td>
                        </tr>
                        <?php $i++ ?>
                      @endforeach

                    </tbody>


                  </table>

              </div>

          </div>



@endsection

@section('script')
<script>

  $(document).ready(function(){
    var pre_status;

    $('.status select').click(function(){
      pre_status=$(this).val();
    });

    $('.status select').change(function(){
      if(confirm('Are you sure you want to change the status of this student?')){
        var student_lesson_id=$(this).parents('tr').data('id');
        var status=$(this).children('option:selected').val();


        $.ajax({
          type:'POST',
          url: "{{route('trials.status')}}",
          data:{id:student_lesson_id,status:status,_token:'{{csrf_token()}}'}
        }).done(function(data){
          if(status>=7 && status<=9){
            $("[data-id="+student_lesson_id+"]").css('background-color','#ddd');
          }else if(status==5){
            $("[data-id="+student_lesson_id+"]").css('background-color','red');
          }else{
            $("[data-id="+student_lesson_id+"]").css('background-color','white');
          }
        });

      }else{
        $(this).val(pre_status);
        return;
      }


    });

  });

</script>
@endsection
