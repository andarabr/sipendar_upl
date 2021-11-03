$('#mastercustomer-table').dataTable( {
  "autoWidth": true
} );

function test(){
  var ktpVal = document.getElementById("fktpid");
  var bdVal = document.getElementById("fbdid");
  var bpVal = document.getElementById("fbpid");

  var ktpSend = document.getElementById("fktpsend");
  var bdSend = document.getElementById("fbdsend");
  var bpSend = document.getElementById("fbpsend");


  ktpSend.value = ktpVal.value;
  bdSend.value = bdVal.value;
  bpSend.value = bpVal.value;

//   alert(ktpSend.value + bdSend.value + bpSend.value);
}

function test2(){
    var ktpVal = document.getElementById("fktpid");
    var bdVal = document.getElementById("fbdid");
    var bpVal = document.getElementById("fbpid");

    var ktpSendw = document.getElementById("fktpsendw");
    var bdSendw = document.getElementById("fbdsendw");
    var bpSendw = document.getElementById("fbpsendw");


    ktpSendw.value = ktpVal.value;
    bdSendw.value = bdVal.value;
    bpSendw.value = bpVal.value;

    // alert(ktpSendw.value + bdSendw.value + bpSendw.value);
  }

