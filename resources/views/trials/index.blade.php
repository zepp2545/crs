@extends('layouts.app')

@section('content')
  @include('partials.alerts.success')
  <div class="searchContent trial pt-2 pb-3">
    <h2>Trial Student List</h2>
    <ul>
    <li>「Status」を「受講中」に更新することでStudent Listに反映されるため、常にアップデートしてください。</li>
    </ul>
    <form action="{{route('trials.search')}}" method="post" id="name_search"> <!--Dot't forget adding double curly braces to display. -->
        @csrf

        <div class="form-group">
            <input type="text" name="searched_name" placeholder="Search a student with the name" id="nameSearchInput" aria-label="Search" class="form-control">
            <input type="hidden" name="type_of_list" value="trials">
        </div>

        <input type="hidden" name="submit">

    </form>
  </div>

  <div class="listWrapper">
            白は「体験待ち」です。
            <span class="ml-4 continue d-inline-block"></span> 継続
            <span class="ml-4 cancel d-inline-block"></span> 継続しない</small>
            <span class="ml-4 consider d-inline-block"></span> 受講検討中
              <div class="trialList">
                  <table class="table table table-bordered">
                    <thead class="thead-dark">
                      <tr>
                          <th class='trialDate'>Trial Date</th>
                          <th class='grade'>Grade</th>
                          <th class='jaName'>名前</th>
                          <th class='kanaName'>かな</th>
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
                          <th class="status">Status</th>
                          <th class='delete'>Edit</th>
                      </tr>
                    </thead>

                    <tbody>

                      @foreach($students as $student)
                        <tr class="
                          @if($student->status>=7 && $student->status<=9)
                            continuation
                          @elseif($student->status==5)
                            cancelled
                          @elseif($student->status==6)
                            consider
                          @endif
                        " data-id="{{optional($student)->id}}">
                          <td class="trialDate">{{optional($student)->trial_date}}</td>
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

                            @foreach(config('const.bususes') as $key=>$value)
                              {{$key==$student->bus ? $value : ''}}
                            @endforeach

                          </td>
                          <td class="pickUp">{{optional($student->pickup)->name}}<br>{{optional($student)->pickup_details}}</td>
                          <td class="send">{{optional($student->send)->name}}<br>{{optional($student)->send_details}}</td>
                          <td class="note">{{optional($student)->note}}</td>
                          <td class="status">
                            <select name="status">
                              @foreach(config('const.statuses') as $key=>$value)
                               @if($key===4 || $key===5 || $key===6 || $key===7 || $key===8)
                                <option {{$key==$student->status ? 'selected' : ''}} value="{{$key}}">{{$value}}</option>
                               @else
                                @continue
                               @endif
                              @endforeach
                            </select>
                          </td>
                          <td><a class="btn btn-primary text-white" href="{{route('trials.edit',['trial'=>$student->id])}}">Edit</a></td>

                        </tr>
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
            window.location.href="{{url('/students')}}"+"/"+data.student_id+"/edit";
          }else if(status==5){
            $("[data-id="+student_lesson_id+"]").css('background-color','red');
          }else if(status==6){
            $("[data-id="+student_lesson_id+"]").css('background-color','yellow');
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
