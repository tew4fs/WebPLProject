function ret(){
    if (localStorage.getItem("gpa") !== null){
        document.getElementById("gparesult").innerHTML = localStorage.getItem("gpa");
    }

}

function gpaObject() {
    var i = document.getElementById("first").value;
    var ii = document.getElementById("second").value;
    var iii = document.getElementById("third").value;
    var iv = document.getElementById("four").value;
    var v = document.getElementById("five").value;
    var vi = document.getElementById("six").value;
    var vii = document.getElementById("seven").value;
    var viii = document.getElementById("eight").value;

    this.semestergpas = [];
    this.semestergpas.push(i, ii, iii, iv, v, vi, vii, viii);
    this.courseNum = 0;
    this.cumulativeGPA = 0;
    let temp = 0
    for (var i = 0; i < 8; i++) {
        if (this.semestergpas[i] !== "") {
            this.courseNum+=1;
            this.cumulativeGPA+=parseInt(this.semestergpas[i]);
        }
    }
    this.cumulativeGPA /= this.courseNum;
    console.log(this.cumulativeGPA);
}

function gpaResultCalc(){
    var newgpaObj = new gpaObject();
    var gpa = newgpaObj.cumulativeGPA;
    var courses = newgpaObj.courseNum;
    localStorage.setItem("gpaObj", JSON.stringify(newgpaObj));
    localStorage.setItem("gpa", gpa); 
    localStorage.setItem("courses", courses); 
}