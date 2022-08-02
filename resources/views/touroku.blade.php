
@include('layouts.input_screen')
@include('layouts.input_screen',['name' => $UserName])
<h2  class="kakeibo_h1">家計簿データ登録</h1>
<div class="input_screen">
<ul class="">
  <form method="post"  name="sousin"   action="{{ url('data_register_mane') }}">
  {{ csrf_field() }}
    <p>
      <input class="col-sm-2 col-form-label input_border" type="text" name="product_name" placeholder="内容"  maxlength="10" value="" onchange="ischeck3(this)" required>
    </p>
    
    
    <p id="input_form1" class="sisyutu_syunyu">
         <input class="col-sm-2 col-form-label input_border" id="syusi" type="tel" name="amount1"  placeholder="支出" value="0"required onchange="ischeck1(this)" >
    </p>

    <p id="input_form2" class="sisyutu_syunyu">
         <input class="col-sm-2 col-form-label input_border" id="syunyu" type="tel" name="amount2" placeholder="収入" value="0" onchange="ischeck2(this)" required>
    </p>

    <p class="kirikae_bu">
    <label> <input class="/col-sm-2 col-form-label" type ="radio" name="syusi"   value=""  onclick="entryChange1();" checked="checked" >支出</label>
    <label> <input class="/col-sm-2 col-form-label" type ="radio" name="syusi"    value="" onclick="entryChange1();" >収入</label>
    </p>

    <p class="hiduke">
      <input class="col-sm-2 col-form-label input_border" type ="datetime-local" step="1" name="Registration_date" placeholder="日付" value=""   required></textarea>
    </p>
    <p>
      <input class="btn btn-secondary touroku" type="submit" value="登録" onclick="ischeck4();">
  </p>
  </ul>

</form> 
<div>


