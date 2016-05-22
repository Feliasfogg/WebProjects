/**
 * Created by pavel on 12-Mar-16.
 */
window.onload = function () {
    document.getElementById('my').onclick = function () {
        alert(" GetElementById Alert");
        rez.innerHTML = "<p>Тип события " + event.type + "</p>" +
            "<p>window " + event.clientX + " " + event.clientY + "</p>" +
            "<p>parent " + event.x + " " + event.y + "</p>" +
            "<p>offset " + event.offsetX + " " + event.offsetY + "</p>" +
            "<p>keykode " + event.which + "</p>";
    }
    my.oncontextmenu = function () {
        alert("Contextmenu Alert");
    }


    var mass = document.getElementsByTagName('p');
    for (var i = 0; i < mass.length; ++i) {
        mass[i].onclick = testFunction;
        mass[i].addEventListener('keydown', testFunction2);
        //mass[i].addEventListener('mousemove', testFunction3);
        //mouseenter, mouseleave, contextmenu
    }

    function testFunction() {
        alert('testFunction onclick');

    }

    function testFunction2() {
        alert('testFunction2 keydown');
    }

    function testFunction3() {
        alert('testFunction3 mousemove');
    }
}
