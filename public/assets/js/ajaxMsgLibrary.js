/**
 * Created by partho on 8/6/17.
 */


class Library{
    rgb2hex(rgb){
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        function hex(x) {
            return ("0"+parseInt(x).toString(16)).slice(-2);
        }
        return "#"+hex(rgb[1]) +hex(rgb[2]) + hex(rgb[3]);
    }
}


class Wait4Response{

    eventFired(domReference, tmpText){
        this.dom = domReference;
        this.domBaseText = domReference.text();
        this.baseText = this.domBaseText;
        this.domBaseColor = domReference.css('color');

        domReference.text(tmpText);
        domReference.css("color", "#00d");
    }

    succeed(domReference, successMsg, fadeAfter){
        var myLibrary = new Library();
        domReference.after("<span style='color: #00ad00'>"+successMsg+"</span>");
        this.dom.text(this.baseText);
        //this.dom.css("color", myLibrary.rgb2hex(this.domBaseColor));
        this.dom.css("color", "#000");
        setTimeout(function () {
            domReference.next().fadeToggle(1500);
        },fadeAfter);
    }

    failed(domReference, successMsg, fadeAfter){
        var myLibrary = new Library();
        domReference.after("<span style='color: #f00'>"+successMsg+"</span>");
        this.dom.text(this.baseText);
        //this.dom.css("color", myLibrary.rgb2hex(this.domBaseColor));
        this.dom.css("color", "#000");
        setTimeout(function () {
            domReference.next().fadeToggle(1500);
        },fadeAfter);
    }
}