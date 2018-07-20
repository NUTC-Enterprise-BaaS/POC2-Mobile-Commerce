<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ginker刮刮樂報表</title>
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    <!-- <nav class="navbar navbar-default" role="navigation" style="background-color: rgb(43,94,9)">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#" style="color: white"><i class="fa fa-bell" aria-hidden="true"></i>&nbsp;Ginker推播</a>
        </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          @if (!Auth::guest())
                        <li class="dropdown" style="color: white">
                            <a href="{{ url('/logout') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} 登出 <span class="caret"></span>
                            </a>
                        </li>
                    @endif
          <li><a href="http://demo.gobuyapp.com/" style="color: white">Joomla</a></li>
        </ul>
      </div>
     </div>
    </nav> -->
    <!-- <div style="padding-left:80px;width:30%">

    </div> -->
    <nav class="navbar navbar-light" style="background-color: #e3f2fd;">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="http://106.184.6.69:8080/gobuynotification">
              &nbsp;Ginker推播
          </a>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="http://ginkerapp.com/administrator/index.php?option=com_hikamarket&ctrl=vendor"><span class="fa fa-cart-plus fa-fw" aria-hidden="true"></span>&nbsp;多店商城</a></li>
          <li><a href="http://ginkerapp.com/administrator/index.php?option=com_socialads"><span class="fa fa-bullhorn fa-fw" aria-hidden="true"></span>&nbsp;社群廣告</a></li>
          <li><a href="http://ginkerapp.com/administrator/index.php?option=com_jbusinessdirectory"><span class="fa fa-cog fa-fw" aria-hidden="true"></span>&nbsp;特約/優惠管理</a></li>
          <li><a href="http://ginkerapp.com/administrator/index.php?option=com_hikashop"><span class="fa fa-cog fa-fw" aria-hidden="true"></span>&nbsp;購物管理管理中心</a></li>
          <li><a href="http://ginkerapp.com/administrator/index.php?option=com_jbusinessdirectory&view=users"><span class="fa fa-users fa-fw" aria-hidden="true"></span>&nbsp;會員管理</a></li>
          <li><a href="http://ginkerapp.com/administrator/index.php?option=com_fss"><span class="fa fa-users fa-fw" aria-hidden="true"></span>&nbsp;客服中心</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          @if (!Auth::guest())
                        <li class="dropdown" style="color: white">
                            <a href="{{ url('/logout') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} 登出 <span class="caret"></span>
                            </a>
                        </li>
                    @endif
          <li><a href="http://ginkerapp.com/">Joomla</a></li>
        </ul>
      </div>
    </nav>
      <div class="container">
        <div class="panel panel-default">
          <div class="panel-heading">刮刮樂報表</div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default">
        <div class="panel-body">
          <div>
            <table class="table table-hover">
              <thead>
                  <th class="table-th" style="width: 100px">會員編號</th>
                  <th class="table-th">會員信箱</th>
                  <th class="table-th">會員電話</th>
                  <th class="table-th">中獎狀態</th>
                  <th class="table-th">中獎點數</th>
                  <th class="table-th">卡片狀態</th>
                  <th class="table-th">時間</th>
              </thead>
              <tbody>
              @foreach ($datas as $key => $data)
                <tr>
                    <td>{{ $data->user_id }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ $data->phone }}</td>
                    <td>
                      @if ($data->money == 0)
                        {{ '未中獎' }}
                      @else
                        {{ '恭喜中獎' }}
                      @endif
                    </td>
                    <td>{{ $data->money }}</td>
                    <td>
                      @if ($data->state == 0)
                        {{ '未刮取' }}
                      @else
                        {{ '已刮取' }}
                      @endif
                    </td>
                    <td>{{ $data->created_at }}</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</body>
<script src="jquery-2.2.1.min.js"></script>
<script>
setTimeout(function() {
            login();
           },
        200);
        setInterval(function() {
            login();
    },3000000);

var token = '';

function login(form){
  var data = {
    'account': 'vip1020',
    'password': '123456'
  };
  $.ajax({
    'type':'POST',
    'url':"http://106.184.6.69:8080/api/v1/login",
    'data': data,
    success:function(data){
      token = data.message.token;
    },
    error:function(){
      alert('登入失敗');
      form.account.value = '';
      form.password.value = '';
    }
  });
}

function store(){
  var select1 = document.getElementById("select1").value;
  var select2 = document.getElementById("select2").value;
  var select3 = document.getElementById("select3");

  if (select1 == 'b' || select1 == 'c' || select1 == 'd') {
    document.getElementById("messagediv").style.visibility = "visible";
    document.getElementById("select2").style.visibility = "visible";
    document.getElementById("select3").style.visibility = "visible";
    if (select1 == 'b') {
    document.getElementById("startinput").style.visibility = "hidden";
    document.getElementById("endinput").style.visibility = "hidden";
    document.getElementById("pointinput").style.visibility = "hidden";
    document.getElementById("awardinput").style.visibility = "hidden";

    $.ajax({
      'type':'GET',
      'url':"http://106.184.6.69:8080/api/v1/store/allPreShop",
      'dataType':'json',
      success:function(data){
        var len =  data.stores.length;
        for(var i=0;i<len;i++ ){
          select3.options[i+1] = new Option(data.stores[i].name, data.stores[i].id);
        };
      }
    });
    }
    if (select1 == 'c') {
      document.getElementById("messagediv").style.visibility = "visible";
      document.getElementById("select2").style.visibility = "visible";
      document.getElementById("select3").style.visibility = "visible";
      document.getElementById("startinput").style.visibility = "hidden";
      document.getElementById("endinput").style.visibility = "hidden";
      document.getElementById("pointinput").style.visibility = "hidden";
      document.getElementById("awardinput").style.visibility = "hidden";

      $.ajax({
            'type':'GET',
            'url':"http://106.184.6.69:8080/api/v1/store/allSpeShop",
            'dataType':'json',
            success:function(data){
              var len =  data.stores.length;
              for(var i=0;i<len;i++ ){
                select3.options[i+1] = new Option(data.stores[i].name, data.stores[i].id);
              };
            }
      });
    }
    if (select1 == 'd') {
      document.getElementById("select3").style.visibility = "visible";
      document.getElementById("select2").style.visibility = "hidden";
      document.getElementById("messagediv").style.visibility = "hidden";
      $('#select3').empty();
      document.getElementById("select3").setAttribute("onchange", "lucky()");
      document.getElementById("select3").options[0] = new Option('請選擇', '#');
      document.getElementById("select3").options[1] = new Option('刮刮樂', 'lucky');
    }
  } else {
    document.getElementById("messagediv").style.visibility = "hidden";
    document.getElementById("select3").style.visibility = "hidden";
  }
}

function lucky() {
  if (document.getElementById("select3").value == 'lucky') {
    $('#startinput').empty();
    $('#endinput').empty();
    $('#pointinput').empty();
    $('#awardinput').empty();
    document.getElementById("startinput").style.visibility = "visible";
    $('#startinput').append('<label>輸入接收者點數範圍(最小)</label><input type="text" id="start" class="form-control" placeholder="最小點數" required="required"></input>');
    document.getElementById("endinput").style.visibility = "visible";
    $('#endinput').append('<label>輸入接收者點數範圍(最大)</label><input type="text" id="end" class="form-control" placeholder="最大點數" required="required"></input>');
    document.getElementById("pointinput").style.visibility = "visible";
    $('#pointinput').append('<label>輸入刮刮樂中獎點數</label><input type="text" id="point" class="form-control" placeholder="點數" required="required"></input>');
    document.getElementById("awardinput").style.visibility = "visible";
    $('#awardinput').append('<label>中獎人數</label><input type="text" id="award" class="form-control" placeholder="中獎人數" required="required"></input>');
  }
}

function setAllSelected() {
  var select1 = document.getElementById("select1").value;
  var select2 = document.getElementById("select2").value;
  var select3 = document.getElementById("select3").value;
  var message = document.getElementById("message").value;

  var data = {
                'id': select3,
                'token': 123456789,
                'message': message
  };
  if (select3 == '#') {
    select1 = '#';
    select3 = '#';
    alert('未選擇');
  }
  if (select1 == 'd') {
    if ($('#start').val()=='' || $('#end').val()=='' || $('#point').val()=='' || $('#award').val()=='') {
      alert('不能空白');
    } else {
      $.ajax({
        'url':"http://106.184.6.69:8080/api/v1/push/lucky",
        'type':'POST',
        data: {
          start: $('#start').val(),
          end: $('#end').val(),
          point: $('#point').val(),
          award_num: $('#award').val()
        },
        'headers': {'Authorization': 'Bearer '+token, 'Accept': 'application/json'},
        success:function(){
          alert("即時已送出");
          document.getElementById("start").value = '';
          document.getElementById("end").value = '';
          document.getElementById("point").value = '';
        }
      });
    }
  }
  if( select1 == 'b' || select1 == 'c' ) {
    $.ajax({
      'type':'POST',
      'url':"http://106.184.6.69:8080/api/v1/store/push/send",
      'data': data,
      'headers': {'Authorization': 'Bearer '+token, 'Accept': 'application/json'},
      success:function(){
        alert("即時推播已送出");
      }
    });
  }

}
</script>
</html>
