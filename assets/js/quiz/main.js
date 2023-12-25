// function random_bg_color(className) {
//     var x = Math.floor(Math.random() * 256);
//     var y = Math.floor(Math.random() * 256);
//     var z = Math.floor(Math.random() * 256);
//     var bgColor = "rgb(" + x + "," + y + "," + z + ")";

//     $(className).css("backgroundColor", bgColor);      
// }

// numOfCards = $(".card-quiz");

// $(".card-quiz").each(function() {
//     random_bg_color(this);
// });

$("img").on("dragstart", function(){
    return false;
})