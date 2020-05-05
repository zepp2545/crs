@extends('layouts.app')

@section('content')
  @include('partials.alerts.success')
  
  <div class="searchContent py-2">
    <h2>Cancelation List</h2>
    <p>講座を辞めた生徒の情報がこちらに記載されます。Student Listに戻すには「Restore」をしてください。</p>
    <p>講座を辞めてから2年間データが保持されます。2年間経過すると自動的に削除されるので定期的にデータを取ってください。</p>
    <form action="{{route('students.quit.search')}}" method="post" id="name_search"> <!--Dot't forget adding double curly braces to display. -->
        @csrf
    
        <div class="form-group">
            <input type="text" name="searched_name" placeholder="Search a student with the name" id="nameSearchInput" aria-label="Search" class="form-control">
            <input type="hidden" name="type_of_list" value="quit">
        </div>

        <input type="hidden" name="submit">

    </form>
  </div>

 
  <div class="studentList">
      <table class="table table table-bordered">
        <thead class="thead-dark">
          <tr>
          　　 <th class="quitDay">Quit Day</th>
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
              <th class="bus">Bus Use</th>
              <th class="pickUp">Pick up</th>
              <th class="send">Send</th>
              <th class="note">Note</th>
              <th class='restore'>Restore</th>
          </tr>
        </thead>

        <tbody>

          @foreach($students as $student)
            <tr data-id="{{$student->id}}">
              <td class="quitDat">{{$student->deleted_at->format('Y-m-d')}}</td>
              <td class="grade">{{optional($student->student)->grade}}</td>
              <td class="jaName">{{optional($student->student)->jaName}}</td>
              <td class="kanaName">{{optional($student->student)->kanaName}}</td>
              <td class="enName">{{optional($student->student)->enName}}</td>
              <td class="lesson">{{optional($student->lesson)->name}}</td>
              <td class="tel1">{{optional($student->student)->tel1}}</td>
              <td class="tel2">{{optional($student->student)->tel2}}</td>
              <td class="email1 email">{{optional($student->student)->email1}}</td>
              <td class="email2 email">{{optional($student->student)->email2}}</td>
              <td class="province">{{optional($student->student)->province}}</td>
              <td class="address">{{optional($student->student->address)->name}}<br>{{optional($student->student)->addDetails}}</td>
              <td class="bus">

                @foreach($student->bususes as $key=>$value)
                  {{$key==$student->bus ? $value : ''}}
                @endforeach

              </td>
              <td class="pickUp">{{optional($student->pickup)->name}}<br>{{$student->pickup_details}}</td>
              <td class="send">{{optional($student->send)->name}}<br>{{$student->send_details}}</td>
              <td class="note">{{$student->note}}</td>
              </td>
              <td class="restore">
                <form action="{{route('students.restore',['id'=>$student->id])}}" method="post">
                  @csrf 
                  @method('put')
                  <button class="btn btn-primary text-white" type="submit">Restore</button>
                </form>
              </td>

            </tr>
          @endforeach

        </tbody>


      </table>

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
