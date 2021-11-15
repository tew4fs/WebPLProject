function validate () {
    var email = document.getElementById("email").value;
    var un = document.getElementById("username").value;
    var pw = document.getElementById("password").value;
    
    if (email.length > 0 && un.length > 0  && pw.length > 8) {
        return true;
    }
    alert("Email, UserName and Password are required. Password with 8 or more characters, at least one letter and one number.");    
    return false;
}