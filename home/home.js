function selectColor(className){
    var val = $("#"+className+"-color").val()+"50";
    console.log(val);
    $("."+className+"-header").css("background-color", val);  
}

var loadColors = (classes) => {
    for(var i=0; i<classes.length; i++){
        var className = classes[i]["name"];
        className = className.replace(/ /g, '');
        var color = localStorage.getItem(className);
        $("."+className+"-header").css("background-color", color+"50"); 
        $("#"+className+"-color").val(color); 
    }
}

var saveColors = (classes) => {
    localStorage.clear();
    for(var i=0; i<classes.length; i++){
        var className = classes[i]["name"];
        className = className.replace(/ /g, '');
        var color = $("#"+className+"-color").val();
        localStorage.setItem(className, color);
    }
}