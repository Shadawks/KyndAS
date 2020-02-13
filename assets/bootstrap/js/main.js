function login() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var Httpreq = new XMLHttpRequest(); 
    Httpreq.open("POST","http://localhost/as/api?do=login",false);
    Httpreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    Httpreq.send("username="+username+"&password="+password);
    var resp = Httpreq.responseText;
    var data = JSON.parse(resp);
    if (data["message"] != "succès") {
        document.getElementById("display").innerHTML= '<div class="alert alert-danger"><strong>Erreur:</strong> '+data["message"]+'</div>';
    } else {
        window.location.replace("http://localhost/as/confirm");
    }
}

function register() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var email = document.getElementById("email").value;
    var confirm_password = document.getElementById("confirm_password").value;
    var Httpreq = new XMLHttpRequest(); 
    Httpreq.open("POST","http://localhost/as/api?do=register",false);
    Httpreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    Httpreq.send("username="+username+"&email="+email+"&password="+password+"&confirm_password="+confirm_password);
    var resp = Httpreq.responseText;
    var data = JSON.parse(resp);
    if (data["message"] != "inscription effectué avec succès.") {
        document.getElementById("display").innerHTML= '<div class="alert alert-danger"><strong>Erreur: </strong> '+data["message"]+'</div>';
    } else {
        window.location.replace("http://localhost/as/login");
    }
}

function checkCode() {
    var code = document.getElementById("code").value;
    var Httpreq = new XMLHttpRequest(); 
    Httpreq.open("POST","http://localhost/as/api?do=checkCode",false);
    Httpreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    Httpreq.send("code="+code);
    var resp = Httpreq.responseText;
    var data = JSON.parse(resp);
    if (data["message"] != "succès") {
        document.getElementById("display").innerHTML= '<div class="alert alert-danger"><strong>Erreur: </strong> '+data["message"]+'</div>';
    } else {
        window.location.replace("http://localhost/as/");
    }
}