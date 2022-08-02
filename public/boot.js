
function entryChange1() {

    radio = document.getElementsByName('syusi')

    if (radio[0].checked) {

        //フォーム

        document.getElementById('input_form1').style.display = "";

        document.getElementById('input_form2').style.display = "none";

    } else if (radio[1].checked) {

        //フォーム

        document.getElementById('input_form1').style.display = "none";

        document.getElementById('input_form2').style.display = "";


    }

}


//オンロードさせ、リロード時に選択を保持
window.onload = entryChange1;


function ischeck5(obj) {
    var str =   document.getElementById('nenbetu_seireki').value;
    var sourcestr = obj.value;
    var a = sourcestr.slice(0, 2);
    if (!str||9999<str ||a == '00'||a == '01'||a == '02'||a == '03'||a == '04'||a == '05'||a == '06'||a == '07'||a == '08'||a == '09') { /* 0未満の値を入力された場合 */
    alert('0以上4桁以下で入力してください'); /* アラート表示 */
    obj.value = ""; /* テキストボックスを空にする */
    return false;
    }
    else
    if (str.match(/[^0-9]/g)) { /* 数値以外の文字列が含まれていた場合 */
        alert(str.match(/[^0-9]/g) + '\n\n数値以外が含まれています'); /* アラート表示 */
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
   
}





function ischeck4() {
    var str =   document.getElementById('syusi').value;
    var syunyu =   document.getElementById('syunyu').value;
    if (!str) { /* 0未満の値を入力された場合 */
    alert('支出額が空白です'); /* アラート表示 */
    obj.value = ""; /* テキストボックスを空にする */
    return false;
    }
    if (!syunyu) { /* 0未満の値を入力された場合 */
    alert('収入額が空白です'); /* アラート表示 */
    obj.value = ""; /* テキストボックスを空にする */
    return false;
    }
}






function ischeck1(obj) {
    var str = obj.value; /* 入力値 */

    var sourcestr = obj.value;
    var a = sourcestr.slice(0, 2);
    if (a == '00') {/*先頭が0だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '01') {/*01だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '02') {/*02だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '03') {/*03だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '04') {/*04だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '05') {/*05だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '06') {/*06だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '07') {/*07だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '08') {/*08だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '09') {/*09だった場合 */

        alert('支出額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (2000000000 <= str) { /*20億以上入力された場合 */
        alert('支出額を20億以下にしてください'); /* アラート表示 */
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (str < 0) { /* 0未満の値を入力された場合 */
        alert('支出額を0以上にしてください'); /* アラート表示 */
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    
    if (str.match(/[^0-9]/g)) { /* 数値以外の文字列が含まれていた場合 */
        alert(str.match(/[^0-9]/g) + '\n\n数値以外が含まれています'); /* アラート表示 */
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
};



function ischeck2(obj) {
    var str = obj.value; /* 入力値 */
    var sourcestr = obj.value;
    var a = sourcestr.slice(0, 2);

    if (a == '00') {/*先頭が0だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '01') {/*01だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '02') {/*02だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '03') {/*03だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '04') {/*04だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '05') {/*05だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '06') {/*06だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }

    if (a == '07') {/*07だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '08') {/*08だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (a == '09') {/*09だった場合 */

        alert('収入額を0以上にしてください');
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }


    if (2000000000 <= str) { /*20億以上入力された場合 */
        alert('収入額を20億以下にしてください'); /* アラート表示 */
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (str < 0) { /* 0未満の値を入力された場合 */
        alert('収入額を0以上にしてください'); /* アラート表示 */
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
    if (str.match(/[^0-9]/g)) { /* 数値以外の文字列が含まれていた場合 */
        alert(str.match(/[^0-9]/g) + '\n\n数値以外が含まれています'); /* アラート表示 */
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
};










function ischeck3(obj) {
    var str = obj.value; /* 入力値 */
    if (str.match(/\s+/g)) { /*20億以上入力された場合 */
        alert('空白を入れないでください'); /* アラート表示 */
        obj.value = ""; /* テキストボックスを空にする */
        return false;
    }
};





const textbox = document.getElementById("syusi");
const value = textbox.value;
function check() {
    if (2000000000 <= value) {
        alert("20000000000未満で入力してください");
        return false;
    }
    else {
        (value < 0)
        alert("0以上で入力してください");
        return false;
    }
};































function check1() {
    if (document.sousin.title.value == "" || document.sousin.body.value == "" || document.sousin.email.value == "") {
        alert("入力欄が未入力です");
        return false;
    }
};

function check2() {
    if (document.sousin.name.value == "" || document.sousin.phoen.value == "" || document.sousin.email.value == "") {
        alert("入力欄が未入力です");
        return false;
    }
};

function check3() {

    henkou = window.confirm("本当に消去しますか？");

    if (henkou == true) {
        return true;

    }
    else {

        return false;
    }
};