/**
 * Created by pavel on 30-Jan-16.
 */
var a = prompt("Digits", "Enter first Digits");
var b = prompt("Digits", "Enter second Digits");
var c = prompt("Digits", "Enter third Digits");
if (a != null && b != null && c != null) {
    var digitsArray = new Array(a, b, c);
    var max = Math.max.apply(Math, digitsArray);
    alert(max);
}



