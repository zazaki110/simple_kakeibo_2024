<?php

namespace App\Http\Controllers;

use App\Models\payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class kakeiboController extends Controller
{
    /**
     * コンストラクタ（ログイン認証）
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    //////////////////////////////////////家計簿システム//////////////////////////////////////////////////////////////////

    public function kakeibo_list() //月別TOP画面
    {
        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $dt->year;
        $tuki = $dt->month;
        $hiduke  = $dt->day;
        $UserName = \Auth::user()->name;

        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->get();
        } elseif (!$recodes) {
        }

        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(spending) AS total_spending')->get();
        //$sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->sum('spending');
        //dd($sisyutusum);
        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(income) AS total_income')->get();

        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 
        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得
        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る



        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }

        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////

        if ($keta3 == 0) //収支の桁数を調べる
        {
            $syusi_name = "";
        }
        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }




        return view('kakeibo_getubetu', compact('UserName', 'recodes', 'sisyutusum', 'syunyusum', 'seireki', 'hiduke', 'tuki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name'));
    }

    public function kakeibo_home() //ホーム画面で今月分のデータを取得する
    {


        $dt = new Carbon();
        $seireki = $dt->year;
        $tuki = $dt->month;
        $hiduke  = $dt->day;
        $user = \Auth::id();
        $UserName = \Auth::user()->name;
        //dd($UserName);
        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->exists();
        //dd($recodes);
        //$recodes = DB::table('payments')->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->get();
            // dd($recodes);
        } elseif (!$recodes) {
        }


        //$tesuto= payments::where('user_id',$user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->sum('spending');
        //dd($tesuto);

        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(spending) AS total_spending')->get();
        //$sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->sum('spending');
        //dd($sisyutusum);
        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(income) AS total_income')->get();


        //$nekokamone = (string) $sisyutusum;
        // $ketayo= strlen($nekokamone);

        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 
        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得
        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る


        //dd($keta3);
        //$syusi_sum = preg_replace('/[^0-9]/', '', $syunyusum);//収支値を文字列で取得
        // $keta2 =  mb_strlen($syunyu_sum);//文字列から数値だけ抜き取る
        //dd($keta);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }


        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////
        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }

        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////

        if ($keta3 == 0) //収支の桁数を調べる
        {
            $syusi_name = "";
        }
        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }





        return view('kakeibo_home', compact('UserName', 'recodes', 'sisyutusum', 'syunyusum', 'seireki', 'hiduke', 'tuki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name')); //
    }







    public function add_month($now_tuki, $now_seireki) ////次の月を取得
    {


        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $now_seireki;
        $tuki = $now_tuki + 1;
        $hiduke  = $dt->day;
        $year = 2022;
        $day = 12;
        $UserName = \Auth::user()->name;
        if ($tuki == 13) {
            $tuki = 1;
            $seireki = $seireki + 1;
        }

        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->get();
        } elseif (!$recodes) {
        }

        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(spending) AS total_spending')->get();

        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(income) AS total_income')->get();
        //dd($syunyusum);

        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////

        if ($keta3 == 0) //収支の桁数を調べる
        {
            $syusi_name = "";
        }
        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }




        return view('kakeibo_getubetu', compact('UserName', 'recodes', 'sisyutusum', 'syunyusum', 'seireki', 'hiduke', 'tuki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name')); //
    }



    public function return_month($now_tuki, $now_seireki) ////前の月を取得
    {
        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $now_seireki;
        $tuki = $now_tuki - 1;
        $hiduke  = $dt->day;
        $year = 2022;
        $day = 12;
        $UserName = \Auth::user()->name;
        if ($tuki == 0) {
            $tuki = 12;
            $seireki = $seireki - 1;
        }

        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->get();
        } elseif (!$recodes) {
        }


        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(spending) AS total_spending')->get();

        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(income) AS total_income')->get();

        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////

        if ($keta3 == 0) //収支の桁数を調べる
        {
            $syusi_name = "";
        }
        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }




        return view('kakeibo_getubetu', compact('UserName', 'recodes', 'sisyutusum', 'syunyusum', 'seireki', 'hiduke', 'tuki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name')); //
    }




    public function data_search(Request $request) //inputdataでデータの検索を行う

    {
        $user = \Auth::id();
        $dt = new Carbon();
        $seireki1 = $request->input('seireki');
        $frend = explode("-", $seireki1);
        $seireki = $frend[0];
        $tuki = $frend[1];
        $year = 2022;
        $day = 12;
        $hiduke  = $dt->day;
        $UserName = \Auth::user()->name;
        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->get();
        } elseif (!$recodes) {
        }
        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(spending) AS total_spending')->get();

        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(income) AS total_income')->get();
        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////


        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }




        return view('kakeibo_getubetu', compact('UserName', 'recodes', 'sisyutusum', 'syunyusum', 'seireki', 'hiduke', 'tuki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name')); //
    }



    ///////////////////////テスト用/////////////////////////////////////

    public function data_search_month_add(Request $request) //inputdataでデータの検索を行う

    {
        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $request->year; //西暦取得
        $tuki = $request->month;
        //dd($tuki);
        $tuki = $tuki + 1; //月を加算する
        //dd($tuki);
        $hiduke  = $dt->day;
        $UserName = \Auth::user()->name;

        if ($tuki == 13) { //月が13になったら1にして西暦を1加算する
            $tuki = 1;
            $seireki =$seireki+1;
        }

        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->get();
        } elseif (!$recodes) {
        }
        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(spending) AS total_spending')->get();

        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(income) AS total_income')->get();
        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////


        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }




        return view('kakeibo_getubetu', compact('UserName', 'recodes', 'sisyutusum', 'syunyusum', 'seireki', 'hiduke', 'tuki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name')); //
    }




    public function data_search_month_lastmonth(Request $request) //inputdataでデータの検索を行う

    {
        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $request->year; //西暦取得
        $tuki = $request->month;
        //dd($tuki);
        $tuki = $tuki - 1; //月を減算する
        //dd($tuki);
        $hiduke  = $dt->day;
        $UserName = \Auth::user()->name;

        if ($tuki == 0) { //月が0になったら12にして西暦を1減算する
            $tuki = 12;
            $seireki =$seireki-1;
        }

        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->get();
        } elseif (!$recodes) {
        }
        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(spending) AS total_spending')->get();

        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->whereMonth('created_at', $tuki)->selectRaw('SUM(income) AS total_income')->get();
        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////


        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }




        return view('kakeibo_getubetu', compact('UserName', 'recodes', 'sisyutusum', 'syunyusum', 'seireki', 'hiduke', 'tuki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name')); //
    }



///////////////////////テスト用////////////////////////////////////////////








    public function kakeibo_nenbetu() //年別でデータを取得
    {

        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $dt->year;
        $right_yazirusi_name = "right_yazirusi";
        $left_yazirusi_name = "left_yazirusi";
        $UserName = \Auth::user()->name;
        if ($seireki == 9999) {
            $right_yazirusi_name = "right_yazirusi0";
        } else
            if ($seireki == 0) {
            $left_yazirusi_name = "left_yazirusi0";
        }


        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->get();
        } elseif (!$recodes) {
        }

        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(spending) AS total_spending')->get();
        //dd($sisyutusum);
        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(income) AS total_income')->get();

        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////


        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }




        return view('kakeibo_nenbetu', compact('UserName', 'recodes', 'right_yazirusi_name', 'left_yazirusi_name', 'sisyutusum', 'syunyusum', 'seireki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name'));
    }



////////////////////テスト/////////////////////////////////


public function kakeibo_nenbetu_add(Request $request) //来年度取得
{

    $user = \Auth::id();
    $dt = new Carbon();
    $seireki = $request->year; //西暦取得
    $seireki =$seireki+1;
    //dd($seireki);
    $right_yazirusi_name = "right_yazirusi";
    $left_yazirusi_name = "left_yazirusi";
    $UserName = \Auth::user()->name;
    if ($seireki == 9999) {
        $right_yazirusi_name = "right_yazirusi0";
    } else
        if ($seireki == 0) {
        $left_yazirusi_name = "left_yazirusi0";
    }


    $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->exists();
    if ($recodes) {
        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->get();
    } elseif (!$recodes) {
    }

    $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(spending) AS total_spending')->get();
    //dd($sisyutusum);
    $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(income) AS total_income')->get();

    $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
    $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

    $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
    $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

    $num = $sisyutusum->sum('total_spending'); //支出合計値
    $num2 = $syunyusum->sum('total_income'); //収入合計値
    // dd($num2-$num); 

    $syusi_total = $num2 - $num; //収支

    $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

    $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
    //dd($keta3);

    if ($keta == 0) //支出値の桁数を調べる
    {
        $total_sisyutu_name = "";
    }
    if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
    {
        $total_sisyutu_name = "sisyutu_goukeigaku";
    }
    if ($keta == 3 || $keta == 4) {
        $total_sisyutu_name = "sisyutu_goukeigaku3";
    } elseif ($keta == 5 || $keta == 6) {
        $total_sisyutu_name = "sisyutu_goukeigaku5";
    } elseif ($keta == 7 || $keta == 8) {
        $total_sisyutu_name = "sisyutu_goukeigaku7";
    } elseif ($keta == 9 || $keta == 10) {
        $total_sisyutu_name = "sisyutu_goukeigaku9";
    } elseif ($keta == 11 || $keta == 12) {
        $total_sisyutu_name = "sisyutu_goukeigaku11";
    }

    /////////////////////////////////////////////////////////////////////////

    if ($keta2 == 0) //収入値の桁数を調べる
    {
        $total_syunyu_name = "";
    }
    if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
    {
        $total_syunyu_name = "syunyu_goukeigaku";
    }
    if ($keta2 == 3 || $keta2 == 4) {
        $total_syunyu_name = "syunyu_goukeigaku3";
    } elseif ($keta2 == 5 || $keta2 == 6) {
        $total_syunyu_name = "syunyu_goukeigaku5";
    } elseif ($keta2 == 7 || $keta2 == 8) {
        $total_syunyu_name = "syunyu_goukeigaku7";
    } elseif ($keta2 == 9 || $keta2 == 10) {
        $total_syunyu_name = "syunyu_goukeigaku9";
    } elseif ($keta2 == 11 || $keta2 == 12) {
        $total_syunyu_name = "syunyu_goukeigaku11";
    }


    //////////////////////////////////////////////////////////////////////////////


    if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
    {
        $syusi_name = "syusi_goukeigaku";
    }
    if ($keta3 == 3 || $keta3 == 4) {
        $syusi_name = "syusi_goukeigaku3";
    } elseif ($keta3 == 5 || $keta3 == 6) {
        $syusi_name = "syusi_goukeigaku5";
    } elseif ($keta3 == 7 || $keta3 == 8) {
        $syusi_name = "syusi_goukeigaku7";
    } elseif ($keta3 == 9 || $keta3 == 10) {
        $syusi_name = "syusi_goukeigaku9";
    } elseif ($keta3 == 11 || $keta3 == 12) {
        $syusi_name = "syusi_goukeigaku11";
    }




    return view('kakeibo_nenbetu', compact('UserName', 'recodes', 'right_yazirusi_name', 'left_yazirusi_name', 'sisyutusum', 'syunyusum', 'seireki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name'));
}



public function data_search_seireki_previous_year(Request $request) //前年度取得
{

    $user = \Auth::id();
    $dt = new Carbon();
    $seireki = $request->year; //西暦取得
    $seireki =$seireki-1;
    //dd($seireki);
    $right_yazirusi_name = "right_yazirusi";
    $left_yazirusi_name = "left_yazirusi";
    $UserName = \Auth::user()->name;
    if ($seireki == 9999) {
        $right_yazirusi_name = "right_yazirusi0";
    } else
        if ($seireki == 0) {
        $left_yazirusi_name = "left_yazirusi0";
    }


    $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->exists();
    if ($recodes) {
        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->get();
    } elseif (!$recodes) {
    }

    $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(spending) AS total_spending')->get();
    //dd($sisyutusum);
    $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(income) AS total_income')->get();

    $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
    $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

    $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
    $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

    $num = $sisyutusum->sum('total_spending'); //支出合計値
    $num2 = $syunyusum->sum('total_income'); //収入合計値
    // dd($num2-$num); 

    $syusi_total = $num2 - $num; //収支

    $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

    $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
    //dd($keta3);

    if ($keta == 0) //支出値の桁数を調べる
    {
        $total_sisyutu_name = "";
    }
    if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
    {
        $total_sisyutu_name = "sisyutu_goukeigaku";
    }
    if ($keta == 3 || $keta == 4) {
        $total_sisyutu_name = "sisyutu_goukeigaku3";
    } elseif ($keta == 5 || $keta == 6) {
        $total_sisyutu_name = "sisyutu_goukeigaku5";
    } elseif ($keta == 7 || $keta == 8) {
        $total_sisyutu_name = "sisyutu_goukeigaku7";
    } elseif ($keta == 9 || $keta == 10) {
        $total_sisyutu_name = "sisyutu_goukeigaku9";
    } elseif ($keta == 11 || $keta == 12) {
        $total_sisyutu_name = "sisyutu_goukeigaku11";
    }

    /////////////////////////////////////////////////////////////////////////

    if ($keta2 == 0) //収入値の桁数を調べる
    {
        $total_syunyu_name = "";
    }
    if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
    {
        $total_syunyu_name = "syunyu_goukeigaku";
    }
    if ($keta2 == 3 || $keta2 == 4) {
        $total_syunyu_name = "syunyu_goukeigaku3";
    } elseif ($keta2 == 5 || $keta2 == 6) {
        $total_syunyu_name = "syunyu_goukeigaku5";
    } elseif ($keta2 == 7 || $keta2 == 8) {
        $total_syunyu_name = "syunyu_goukeigaku7";
    } elseif ($keta2 == 9 || $keta2 == 10) {
        $total_syunyu_name = "syunyu_goukeigaku9";
    } elseif ($keta2 == 11 || $keta2 == 12) {
        $total_syunyu_name = "syunyu_goukeigaku11";
    }


    //////////////////////////////////////////////////////////////////////////////


    if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
    {
        $syusi_name = "syusi_goukeigaku";
    }
    if ($keta3 == 3 || $keta3 == 4) {
        $syusi_name = "syusi_goukeigaku3";
    } elseif ($keta3 == 5 || $keta3 == 6) {
        $syusi_name = "syusi_goukeigaku5";
    } elseif ($keta3 == 7 || $keta3 == 8) {
        $syusi_name = "syusi_goukeigaku7";
    } elseif ($keta3 == 9 || $keta3 == 10) {
        $syusi_name = "syusi_goukeigaku9";
    } elseif ($keta3 == 11 || $keta3 == 12) {
        $syusi_name = "syusi_goukeigaku11";
    }




    return view('kakeibo_nenbetu', compact('UserName', 'recodes', 'right_yazirusi_name', 'left_yazirusi_name', 'sisyutusum', 'syunyusum', 'seireki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name'));
}




//////////////////テスト///////////////////////////////////////////









    public function add_year($now_seireki) ////次の年を取得
    {


        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $now_seireki + 1;
        $right_yazirusi_name = "right_yazirusi";
        $left_yazirusi_name = "left_yazirusi";
        $UserName = \Auth::user()->name;
        if ($seireki == 9999) {
            $right_yazirusi_name = "right_yazirusi0";
        } else
            if ($seireki == 0) {
            $left_yazirusi_name = "left_yazirusi0";
        }

        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->get();
        } elseif (!$recodes) {
        }

        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(spending) AS total_spending')->get();

        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(income) AS total_income')->get();
        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////


        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }



        return view('kakeibo_nenbetu', compact('UserName', 'recodes', 'left_yazirusi_name', 'right_yazirusi_name', 'sisyutusum', 'syunyusum', 'seireki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name'));
    }



    public function return_year($now_seireki) ////前の年を取得
    {
        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $now_seireki - 1;
        $right_yazirusi_name = "right_yazirusi";
        $left_yazirusi_name = "left_yazirusi";
        $UserName = \Auth::user()->name;
        if ($seireki == 9999) {
            $right_yazirusi_name = "right_yazirusi0";
        } else
            if ($seireki == 0) {
            $left_yazirusi_name = "left_yazirusi0";
        }
        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->get();
        } elseif (!$recodes) {
        }


        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(spending) AS total_spending')->get();

        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(income) AS total_income')->get();
        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////


        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }



        return view('kakeibo_nenbetu', compact('UserName', 'recodes', 'right_yazirusi_name', 'left_yazirusi_name', 'sisyutusum', 'syunyusum', 'seireki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name'));
    }

    public function data_search_seireki(Request $request) //年別画面で西暦だけでデータを取得する
    {
        $user = \Auth::id();
        $dt = new Carbon();
        $seireki = $request->input('seireki');
        $right_yazirusi_name = "right_yazirusi";
        $left_yazirusi_name = "left_yazirusi";
        $UserName = \Auth::user()->name;
        if ($seireki == 9999) {
            $right_yazirusi_name = "right_yazirusi0";
        } else
            if ($seireki == 0) {
            $left_yazirusi_name = "left_yazirusi0";
        }

        $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->exists();
        if ($recodes) {
            $recodes = payments::where('user_id', $user)->whereYear('created_at', $seireki)->get();
        } elseif (!$recodes) {
        }
        $sisyutusum = payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(spending) AS total_spending')->get();

        $syunyusum  =   payments::where('user_id', $user)->whereYear('created_at', $seireki)->selectRaw('SUM(income) AS total_income')->get();
        $sisyutu_sum = preg_replace('/[^0-9]/', '', $sisyutusum); //支出合計値を文字列で取得
        $keta =  mb_strlen($sisyutu_sum); //文字列から数値だけ抜き取る

        $syunyu_sum = preg_replace('/[^0-9]/', '', $syunyusum); //収入合計値を文字列で取得
        $keta2 =  mb_strlen($syunyu_sum); //文字列から数値だけ抜き取る

        $num = $sisyutusum->sum('total_spending'); //支出合計値
        $num2 = $syunyusum->sum('total_income'); //収入合計値
        // dd($num2-$num); 

        $syusi_total = $num2 - $num; //収支

        $syusi = preg_replace('/[^0-9]/', '', $syusi_total); //収支値を文字列で取得

        $keta3 =  mb_strlen($syusi_total); //文字列から数値だけ抜き取る
        //dd($keta3);

        if ($keta == 0) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "";
        }
        if ($keta == 1 || $keta == 2) //支出値の桁数を調べる
        {
            $total_sisyutu_name = "sisyutu_goukeigaku";
        }
        if ($keta == 3 || $keta == 4) {
            $total_sisyutu_name = "sisyutu_goukeigaku3";
        } elseif ($keta == 5 || $keta == 6) {
            $total_sisyutu_name = "sisyutu_goukeigaku5";
        } elseif ($keta == 7 || $keta == 8) {
            $total_sisyutu_name = "sisyutu_goukeigaku7";
        } elseif ($keta == 9 || $keta == 10) {
            $total_sisyutu_name = "sisyutu_goukeigaku9";
        } elseif ($keta == 11 || $keta == 12) {
            $total_sisyutu_name = "sisyutu_goukeigaku11";
        }

        /////////////////////////////////////////////////////////////////////////

        if ($keta2 == 0) //収入値の桁数を調べる
        {
            $total_syunyu_name = "";
        }
        if ($keta2 == 1 || $keta2 == 2) //収入値の桁数を調べる
        {
            $total_syunyu_name = "syunyu_goukeigaku";
        }
        if ($keta2 == 3 || $keta2 == 4) {
            $total_syunyu_name = "syunyu_goukeigaku3";
        } elseif ($keta2 == 5 || $keta2 == 6) {
            $total_syunyu_name = "syunyu_goukeigaku5";
        } elseif ($keta2 == 7 || $keta2 == 8) {
            $total_syunyu_name = "syunyu_goukeigaku7";
        } elseif ($keta2 == 9 || $keta2 == 10) {
            $total_syunyu_name = "syunyu_goukeigaku9";
        } elseif ($keta2 == 11 || $keta2 == 12) {
            $total_syunyu_name = "syunyu_goukeigaku11";
        }


        //////////////////////////////////////////////////////////////////////////////


        if ($keta3 == 1 || $keta3 == 2) //収支の桁数を調べる
        {
            $syusi_name = "syusi_goukeigaku";
        }
        if ($keta3 == 3 || $keta3 == 4) {
            $syusi_name = "syusi_goukeigaku3";
        } elseif ($keta3 == 5 || $keta3 == 6) {
            $syusi_name = "syusi_goukeigaku5";
        } elseif ($keta3 == 7 || $keta3 == 8) {
            $syusi_name = "syusi_goukeigaku7";
        } elseif ($keta3 == 9 || $keta3 == 10) {
            $syusi_name = "syusi_goukeigaku9";
        } elseif ($keta3 == 11 || $keta3 == 12) {
            $syusi_name = "syusi_goukeigaku11";
        }



        return view('kakeibo_nenbetu', compact('UserName', 'recodes', 'right_yazirusi_name', 'left_yazirusi_name', 'sisyutusum', 'syunyusum', 'seireki', 'total_sisyutu_name', 'total_syunyu_name', 'syusi_name'));
    }

    public function touroku()
    {
        $UserName = \Auth::user()->name;
        // dd($UserName);
        return view('touroku', compact('UserName'));
    }

    public function register_mane(Request $request)
    {



        $pay = new payments();
        $user = \Auth::user();
        $pay->create([
            'payment_name' => $request->product_name, //内容
            'spending' => $request->amount1, //支出
            'income' => $request->amount2, //収入
            'user_id' => $user['id'], //ユーザ識別
            'date' => $request->Registration_date, //日付
            'created_at' => $request->Registration_date,
        ]);


        return redirect('tarukame_home');
    }

    public function edit_screen_kakeibo($id, $naiyou, $sisyutu, $syunyuu, $nitizi)

    {
        $UserName = \Auth::user()->name;
        $datawatas = [
            'id' => $id,
            'naiyou' => $naiyou,
            'sisyutu' => $sisyutu,
            'syunyuu' => $syunyuu,
            'nitizi' => $nitizi,
        ];

        return view('kakeibo_hensyuu',compact('UserName'),$datawatas);
    }






    public function edit_screen_post_kakeibo(Request $request, $id)

    {
        $sisyutu = 0;
        $syunyu = 0;

        if (isset($request->amount1)) {
            $sisyutu = $request->amount1;
        } else {
            $request->$sisyutu;
        }

        if (isset($request->amount2,)) {
            $syunyu = $request->amount2;
        } else {
            $request->$syunyu;
        }




        payments::where('id', $id)->update([


            'payment_name' => $request->product_name,
            'spending' => $sisyutu,
            'income' => $syunyu,
            'created_at' => $request->Registration_date,
        ]);


        return redirect('tarukame_home');
    }

    public function delete_kakeibo($id)

    {
        
        payments::where('id', $id)->delete();
        return redirect('tarukame_home');
        //$this->kakeibo_home();
        //return back();
        //url()->previous();//前のページに戻る
    }
}
