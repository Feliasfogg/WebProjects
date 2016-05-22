/**
 * Created by pavel on 27-Feb-16.
 */
var str="loooooool";
//str=prompt("Enter the string");
str = str.toLowerCase();
var cnt=0;
var ch='';

for(var i=0; i<str.length; ++i){
    var count = getCharCount(str[i], str);
    if(count>cnt) {
        cnt = count;
        ch = str[i];
        }
    }


function getCharCount(char, string){
    var count=0;
    for(var i=0; i<str.length; ++i){
        if(str[i]==char) count++;
    }
    return count;
}
console.log(cnt, ch);



