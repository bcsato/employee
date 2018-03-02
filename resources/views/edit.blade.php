<!DOCTYPE html>
<html lang="en">
<head>
  <title>Create Employee</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="{{URL::to('/resources/assets/css/style.css')}}">
</head>
<body>
    <div class="container">
      <h2 style="text-align: center;">Create Employee</h2>
      <div class="col-sm-10 col-sm-offset-2">

        @if(Session::has('success'))
            <div class="alert alert-success">
              <strong>{!! Session::get('success') !!}</strong>
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-danger">
              <strong>{!! Session::get('error') !!}</strong>
            </div>
        @endif
      </div>

        @if (count($errors) > 0)
          <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
                  <span>{{ $error }}</span><br>
              @endforeach
          </div>
        @endif

        {!! Form::open(array('url'=>'employee/store','method'=>'POST', 'files'=>true)) !!}
        <div class="form-group">
          <?php echo Form::label('name', 'Name:* ', ['class' => 'control-label col-sm-2','for'=>'name']);  ?>
          <div class="col-sm-10 txt_space">
            {{ Form::text('name', '', array('class' => 'form-control','placeholder'=>'Enter name','id'=>'name')) }}
          </div>
        </div>

        <div class="form-group">
          <?php echo Form::label('email', 'Email:* ', ['class' => 'control-label col-sm-2','for'=>'email']);  ?>
          <div class="col-sm-10 txt_space">
            {{ Form::text('email', '', array('class' => 'form-control','placeholder'=>'Enter email','id'=>'email')) }}
          </div>
        </div>

                <div class="form-group">
          <?php echo Form::label('address', 'Address:* ', ['class' => 'control-label col-sm-2','for'=>'address']);  ?>
          <div class="col-sm-10 txt_space">
                {{ Form::textarea('address', null, ['placeholder'=>'Enter address','id'=>'address','class'=>'form-control']) }}
          </div>
        </div>

        <div class="form-group">
          <?php echo Form::label('image', 'Image:* ', ['class' => 'control-label col-sm-2','for'=>'image']);  ?>
          <div class="col-sm-10 txt_space">
                {!! Form::file('emp_image') !!}
          </div>
        </div>
      

        <div class="form-group">
          <?php echo Form::label('gender', 'Gender:* ', ['class' => 'control-label col-sm-2','for'=>'gender']);  ?>
          <div class="col-sm-10 txt_space">
            <div id="emp_gender">
                {{ Form::radio('gender', '0','', ["id"=>"male"]) }} Male
                {{ Form::radio('gender', '1 ','', ["id"=>"female"]) }} Female
            </div>
          </div>
        </div>
      </div>

    <div class="form-group">        
      <div class="col-sm-offset-5 col-sm-10">
        {!! Form::submit('Submit', array('class'=>'btn btn-primary')) !!}
        <a href="{{URL::to('/employee')}}" class="btn btn-danger">Cancle</a>
      </div>
    </div>
    {!! Form::close() !!}
</div>
</body>
</html>