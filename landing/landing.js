function getName(){
    let person = prompt ("Enter your Name", "User"); 
    return person;
}

document.getElementById("username").innerHTML = getName();

