/**
 * Created by partho on 3/20/18.
 */

var rawX = [];
var rawY = [];
var instantaneousTime = [];

function grabPoints(event) {
    rawX.push(event.clientX);
    rawY.push(event.clientY);
    instantaneousTime.push(new Date().getTime());

    return [{'rawX' : rawX, 'rawY' : rawY, 'instantaneousTime' : instantaneousTime}];
}

function distanceB2in(x1, y1, x2, y2){
    var x = x1 - x2;
    x = Math.pow(x, 2);
    var y = y1 - y2;
    y = Math.pow(y, 2);

    return Math.sqrt(x+y);
}


function excludePointsCloserThan(minDistance){
    var x = []; var y = []; var time = [];

    for(var i=0; i < rawX.length; i++){
        var d = distanceB2in(rawX[i] ,rawY[i], rawX[i+1], rawY[i+1]);
        if(d > minDistance){
            //console.log(instantaneousTime[i] + "=> "+rawX[i]+","+rawY[i]+" and "+rawX[i+1]+","+rawY[i+1]+" = "+distanceB2in(rawX[i] ,rawY[i], rawX[i+1], rawY[i+1]));
            x.push(rawX[i]);
            y.push(rawY[i]);
            time.push(instantaneousTime[i]);
        }else{
            //console.log(rawX[i]+","+rawY[i]+" and "+rawX[i+1]+","+rawY[i+1]+" = "+distanceB2in(rawX[i] ,rawY[i], rawX[i+1], rawY[i+1])+" skipped for too close");
        }
    }
    return [{'x' : x, 'y' : y, 'time' : time}];
}