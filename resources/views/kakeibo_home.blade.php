@extends('layouts.base')

@section('title','家計簿アプリ')

@section('h1','家計簿')



@section('データ一覧')

<header class="header_main"><div class="header_menu"><a class="tukibetu" href="http://127.0.0.1:8000/tarukame_totalling" >月別データ</a>
    <div class="logo"> <a href=""><img class="logo_pic" src="{{ asset('img/mark_yen_okaikei.png') }}" alt="ホームロゴ"></a></div>
    <a class="nyuryoku" href="{{url('/register_member',)}}" >入力画面</a><a class="nenbetu" href="http://127.0.0.1:8000/tarukame_nenbetu">年別データ</a><div class="user_name">こんにちは&nbsp&nbsp{{$UserName}}さん</div></div>
    <div class="home_name">家計簿タルカメ</div>
    <form action="{{ route('logout') }}" method="post">
  @csrf
  <input type="submit" class="logout_bu" value="ログアウト">
</form>
    </header>


    
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
  <script src="./js/chartjs-plugin-labels.js"></script>
<div class="hiduke">{{$seireki}}年{{$tuki}}月</div>


    


    @if($recodes==false)

         <tr>
         
          <div class="Master_0"> <td>データが登録されてません</td></div>
         <?php
   $counter=null;
   $henkou="mypie_zero";
   $yazirusimigi_henkou="yazirusimigi_zero";
   $yazirusihidari_henkou="yazirusihidari_zero";
   $karenda="karenda_zero";
   $kensaku="kensaku_zero";
?>
         </tr>
    @endif
    @if($recodes==true)
    <?php
           $counter=0;
           $henkou="myPie";
           $yazirusimigi_henkou="yazirusimigi";
           $yazirusihidari_henkou="yazirusihidari";
           $karenda="karenda";
           $kensaku="kensaku";
   ?>





   <div class={{$henkou}}><canvas id="myPieChart1" width="3800" height="1080" class="mypie2"></canvas> </div>
   <table class="table table-striped">
   <div class="itiran"> <th>件数</th><th>内容</th> <th>支出</th><th>収入</th><th>登録日時</th> </div>
   
    @foreach ($recodes as $syuturyoku)
    @if($loop->index=$counter++)

    @endif
    @foreach ($sisyutusum as $sisyutgoukei)
    @foreach ($syunyusum as $syunyugoukei)
    @method('delete')
        
    {{ csrf_field() }}
    
   
    <?php
    
     $sisyutu_goukeigaku=$total_sisyutu_name;

    ?>


    
    <tr>
    
    <td>{{$counter}}</td><td>{{ $syuturyoku->payment_name }}<td>{{number_format( $syuturyoku->spending )}}円<td>{{ number_format($syuturyoku->income) }}円<td>{{ $syuturyoku->created_at}}</td><td><a href="{{url('edit_screen_kakeibo',['id'=>$syuturyoku->id,'naiyou'=>$syuturyoku->payment_name,'sisyutu'=>$syuturyoku->spending,'syunyuu'=>$syuturyoku->income,'nitizi'=>$syuturyoku->created_at])}}">編集</a>|<a href="{{url('delete_kakeibo',['id'=>$syuturyoku->id])}}">消去</a></td>
    
   </tr>
   
   @endforeach
   @endforeach
   @endforeach
   
   
  <div class="Balance_column"> </div>
  
  <div class="syusi_waku"> <div class="sisyutu_goukei">支出合計</div><div class={{$total_sisyutu_name}}>{{number_format($sisyutgoukei->total_spending)}}円</div></div>
   <div class="syunyu_goukei">収入合計</div><div class={{$total_syunyu_name}}>{{number_format($syunyugoukei->total_income)}}円</div>

   <div class="syusi">収支</div><div class={{$syusi_name}}>{{number_format($syunyugoukei->total_income-$sisyutgoukei->total_spending)}}円</div>
   
  </table>
  
   @endif
   
   
  
   
   


   @if($recodes==true)
    <?php
   $total_sisyutu=$sisyutgoukei->total_spending;
   $sisyutu_json = json_encode($total_sisyutu);

   $total_syunyu=$syunyugoukei->total_income;
   $syunyu_json = json_encode($total_syunyu);
   ?>
@endif

@if($recodes==false)

<?php
   $total_sisyutu=0;
   $sisyutu_json = json_encode($total_sisyutu);

   $total_syunyu=0;
   $syunyu_json = json_encode($total_syunyu);
   ?>


@endif








@if($recodes==false)

<?php
   $total_sisyutu=0;
   $sisyutu_json = json_encode($total_sisyutu);

   $total_syunyu=0;
   $syunyu_json = json_encode($total_syunyu);
   ?>


@endif


  <script>

var sisyutu = JSON.parse('<?php echo $sisyutu_json; ?>');
var syunyu = JSON.parse('<?php echo $syunyu_json; ?>');
var syusi =  30
var ctx = document.getElementById("myPieChart1");
var context = ctx.getContext('2d');
context.fillRect(200,40,50,100);
var myPieChart = new Chart(ctx, {
  

type: 'pie',
data: {
  labels: ["支出","収入"],
  datasets: [{
      backgroundColor: [
          "#BB5179",
          "#3C00FF"
      ],
      data: [sisyutu, syunyu]
  }]
},
options: {
  title: {
    display: true,
    text: '収入と支出の割合'
  },
  plugins: {
        labels: {
          render: 'percentage',
          fontColor: 'white',
          fontSize: 20
        }
      }
    }
});
</script>
   </table>
   @endsection
  
   </body>
   </form>