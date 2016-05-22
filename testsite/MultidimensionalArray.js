/**
 * Created by pavel on 20-Feb-16.
 */

var count = parseInt(prompt("Enter count"));
var mas = [];
var massStrings = [];
var massColumns = [];
for (var i = 0; i < count; ++i) {
    mas[i] = [];
    var summ = 0;
    for (var j = 0; j < count; ++j) {
        var int = parseInt(prompt("Enter text"));
        mas[i][j] = int;
        summ += int;
    }
    massColumns[i] = summ;
}


for (var i = 0; i < count; ++i) {
    var summ = 0;
    for (var j = 0; j < count; ++j) {
        summ += mas[j][i];
    }
    massStrings[i] = summ;
}

console.log(mas);
console.log(massStrings);
console.log(massColumns);
