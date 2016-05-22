/**
 * Created by pavel on 17-Feb-16.
 */
var arr = [1,5,3,10,4,12,9,74,5,0];

for(i=0; i<arr.length; ++i){
    for(j=i; j<arr.length; ++j){
        if(arr[j]<arr[i]){
            var temp = arr[i];
            arr[i]=arr[j];
            arr[j] = temp;
        }
    }
}

alert(arr);