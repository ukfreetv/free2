var AI = new function () {
    "use strict";
    this.strDIVName = "divAJAXmaterial";
    this.strTAGname = "data4";
    this.timerMain = null;


    // this.timerMainTwo = null;

    this.paperclickAI = function (e) {
        return "/" === e.substring(0, 1) ? window.location = e : this.materialAJAX(e), !1;
    };
    this.ordinal_suffix_of = function (i) {
        var j = i % 10,
            k = i % 100;
        if (j == 1 && k != 11) {
            return i + "st";
        }
        if (j == 2 && k != 12) {
            return i + "nd";
        }
        if (j == 3 && k != 13) {
            return i + "rd";
        }
        return i + "th";
    };
    this.fixTheFooter = function () {
        var element = document.getElementById("footer");
        var rect = element.getBoundingClientRect();
        var heightoffooter = parseInt(rect.bottom - rect.top);
        var w = window,
            d = document,
            e = d.documentElement,
            g = d.getElementsByTagName("body")[0],
            heightofscreen = parseInt(w.innerHeight || e.clientHeight || g.clientHeight);
        // c = heightofscreen - rect.top;
        var mytop = parseInt(rect.top);
        // console.log("top" + mytop + " view height " + heightofscreen + " footer heioght " + heightoffooter);
        var c = heightofscreen - (heightoffooter + mytop);
        c = parseInt(c);
        if (rect.top + heightoffooter < heightofscreen) {
            // console.log("move down a bit please? " + c);
            element.style.marginTop = c + "px";
        }
        else {
            // console.log("you can leave it be " + c);
            //
            if (c < -heightofscreen) {
                element.style.marginTop = "0";
                //
                // element.style.marginTop= element.style.marginTop+c;
            }
            else {
                var mt = parseInt(element.style.marginTop) + c;
                mt = parseInt(mt);
                if (mt < 0) {
                    mt = 0;
                }
                // console.log(mt);
                element.style.marginTop = mt + "px";
            }
        }
    };
    this.alertContentsForMaerial = function (e) {
        if (4 === e.readyState) {
            var t = document.getElementById(this.strDIVName);
            if (200 === e.status) {


                var i = e.responseXML;
                var a;


                console.log(i);

                if(i ===null )
                {
                return;
                }

                    var n = i.getElementsByTagName(this.strTAGname);
                    a = n.item(0).textContent;





                if ("undefined" === a || null === a || decodeURI(a) === "undefined") {
                    var r = n.item(0);
                    // noinspection Annotator
                    a = r.firstChild.data;
                }
                t.innerHTML = decodeURI(a);
                var n2 = i.getElementsByTagName('action'), a2 = n2.item(0).textContent;
                if ("undefined" === a2 || null === a2 || decodeURI(a2) === "undefined") {
                    // alert("no action");
                }
                else {
                    var n3 = i.getElementsByTagName('waittime'), a3 = n3.item(0).textContent;
                    if ("undefined" === a3 || null === a3 || decodeURI(a3) === "undefined") {
                        // alert("no timer");
                    }
                    else {
                        if (a3 == 0.1) {
                            setTimeout(function () {
                                window.location = a2
                            }, n3 * 1000);
                        } else {
                            if (a3 > 1) {


                                if (null !== AI.timerMaintwo) {
                                    clearTimeout(AI.timerMaintwo);
                                }
                                AI.timerMaintwo = setTimeout(function () {
                                    AI.makeAJAXRequest(a2);
                                }, a3 * 1000);
                            }
                        }
                    }
                }
            } else location.reload(); //t.innerHTML = decodeURI("There was a problem with the request, " + e.status + ".");
        }
        this.fixTheFooter();
    };
    this.makeAJAXRequest = function (url) {
        var httpRequest;
        if (window.XMLHttpRequest) {
            httpRequest = new XMLHttpRequest();
            if (httpRequest.overrideMimeType) {
                httpRequest.overrideMimeType("text/xml");
            }
        } else if (window.ActiveXObject) {
            try {
                httpRequest = new window.ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    httpRequest = new window.ActiveXObject("Microsoft.XMLHTTP");
                } catch (ignore) {
                }
            }
        }
        if (!httpRequest) {
            //console.log("Giving up :( Cannot create an XMLHTTP instance");
            return false;
        }
        httpRequest.onreadystatechange = function () {
            AI.alertContentsForMaerial(httpRequest);
        };
        httpRequest.open("POST", url, true);
        httpRequest.send("");
    };
    this.padumber = function (n) {
        if (n < 10) {
            return "0" + n;
        } else {
            return n;
        }
    };
    this.startTime = function () {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        var day = today.getDate();
        var monthIndex = today.getMonth();
        var year = today.getFullYear();
        var dow = today.getDay();
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        if (null !== document.getElementById("dashright")) {
            document.getElementById("dashright").innerHTML =
                days[dow] + " " + AI.ordinal_suffix_of(day) + " " + monthNames[monthIndex] + " " + year + ", " +
                AI.padumber(h) + ":" + AI.padumber(m) + ":" + AI.padumber(s);
        }
        setTimeout(AI.startTime, 500);
        // console.log(t);
    };
    setTimeout(this.startTime, 500);

    this.materialAJAX = function (strAction) {
        // var strAction = "read/339049";
        var strRequest = "/ajax/" + strAction;
        if (strAction === "" || strAction === undefined) {
            this.timerMain = setTimeout(function () {
                AI.paperclickAI(strAction);
            }, 29989);
        } else {
            clearTimeout(AI.timerMain);
        }
        this.makeAJAXRequest(strRequest);
        return false;
    };
};
