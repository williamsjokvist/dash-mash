window.addEventListener('load', function () {
    window.applicationCache.addEventListener('updateready', function () {
        if (window.applicationCache.status === window.applicationCache.UPDATEREADY) {
            if (confirm("The site version has been updated. Reload !!")) {
                window.location.reload();
            }
        } else {
        }
    }, false);
}, false);
(function () {
    var MayStore = false;
    var tmpStorage = localStorage.getItem("MayStoreData");
    if (tmpStorage === "true" || tmpStorage !== null) {
        MayStore = true;
    }
    if (MayStore === false) {
        CreateDialog();
    }
    var cookieBox = document.querySelector(".cookie_alert");
    if (cookieBox) {
        var cookieAccept = document.querySelector("dialog a[type='accept']");
        cookieAccept.addEventListener("click", iniCookie);
    }

    function CreateDialog() {
        var el = document.createElement("dialog");
        el.innerHTML = "<span><h6>Cookie Disclaimer</h6>By browsing the site, you consent to our use of cookies.</span><a type='accept' href='javascript:void(0)' class='fat'>Got it!</a><a href='//en.wikipedia.org/wiki/HTTP_cookie' class='fat'>Learn More!</a>";
        el.setAttribute("open", "");
        el.classList.add("cookie_alert");
        document.querySelector("body>header").appendChild(el);
    }

    function iniCookie() {
        cookieBox.removeAttribute("open");
        cookieBox.setAttribute("closed", "");
        localStorage.setItem("MayStoreData", "true");
        MayStore = true;
    }
}());

(function CharCount() {
    var textarea = document.querySelector("#topic-reply textarea"),
            charfeed = document.querySelector("#topic-reply fieldset>div>span");
    if (textarea) {
        window.addEventListener("load", function () {
            var maxLength = textarea.getAttribute("maxlength");
            charfeed.innerHTML = "<mark>" + maxLength + "</mark>" + ' characters remaining';
            textarea.addEventListener("keyup", function () {
                var textLength = this.value.length,
                        textRemain = maxLength - textLength;
                charfeed.innerHTML = "<mark>" + textRemain + "</mark>" + ' characters remaining';
            });
        });
    }
}());
(function AnimShow() {
    var toggler = document.querySelectorAll("a[type='toggler']"),
            box = document.querySelectorAll("[data-toggled]");
    for (let i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function () {
            if (box[i].hasAttribute("data-toggled") && box[i].getAttribute("data-toggled") === "false") {
                box[i].setAttribute("data-toggled", "true");
            } else if (box[i].hasAttribute("data-toggled") && box[i].getAttribute("data-toggled") === "true") {
                box[i].setAttribute("data-toggled", "false");
            } else if (box[i].hasAttribute("data-toggled") && box[i].getAttribute("data-toggled") === "") {
                box[i].setAttribute("data-toggled", "true");
            }
        });
    }
}());

function MoveTop(duration) {
    var cosParameter = window.scrollY / 2,
            scrollCount = 0,
            oldTimestamp = performance.now();

    function Step(newTimestamp) {
        scrollCount += Math.PI / (duration / (newTimestamp - oldTimestamp));
        if (scrollCount >= Math.PI) {
            window.scrollTo(0, 0);
        }
        if (window.scrollY === 0) {
            return;
        }
        window.scrollTo(0, Math.round(cosParameter + cosParameter * Math.cos(scrollCount)));
        oldTimestamp = newTimestamp;
        window.requestAnimationFrame(Step);
    }
    window.requestAnimationFrame(Step);
}
var buttonMoveTop = document.getElementById("auto-up");
if (buttonMoveTop) {
    buttonMoveTop.addEventListener("click", function () {
        MoveTop(500);
    });
    window.addEventListener("scroll", function () {
        if (window.scrollY !== 0) {
            buttonMoveTop.classList.add("show");
        } else {
            buttonMoveTop.classList.remove("show");
        }
    });
}
var desktop = window.matchMedia("(min-width: 900px)");
var mobile = window.matchMedia("(max-width: 900px)");
var nav = document.querySelector("nav>ul");
var navContainer = document.getElementsByTagName("notification-bar")[0];
var html = document.querySelectorAll("html"),
        htmlm = document.querySelector("html");
mobile.addListener(WidthChangeMo);
desktop.addListener(WidthChange);

function WidthChangeMo(mobile) {
    if (mobile.matches) {
        if (!nav.classList.contains("hamburger-menu")) {
            nav.classList.add("hamburger-menu");
        }
    }
}
WidthChange(desktop);

function WidthChange(desktop) {
    if (desktop.matches && window.location.pathname !== "/profile.php" && window.location.pathname !== "/profile/edit.php") {
        function Scrolling() {
            if (window.scrollY >= 340) {
                navContainer.classList.add("scrollfix");
                nav.classList.add("hamburger-menu");
            } else if (window.scrollY <= 278) {
                navContainer.classList.remove("scrollfix");
                nav.classList.remove("hamburger-menu");
            }
        }
        html = Array.prototype.slice.call(html);
        html.forEach(function () {
            window.addEventListener("scroll", Scrolling);
        });
    } else {
        navContainer.classList.add("scrollfix");
        nav.classList.add("hamburger-menu");
        WidthChangeMo(mobile);
    }
}
document.getElementById("nav-button").addEventListener("click", function () {
    this.classList.toggle("active");
    if (!nav.classList.contains("nav-open")) {
        nav.classList.add("nav-open");
        htmlm.classList.add("nav-open");
    } else {
        nav.classList.remove("nav-open");
        htmlm.classList.remove("nav-open");
    }
});
var bg = document.querySelectorAll("[data-bgmove]");
for (var e = 0; e < bg.length; e++) {
    bg[e].addEventListener("mousemove", function (event) {
        var strength = 3,
                width = strength / this.clientHeight,
                height = strength / this.clientWidth;
        var x = width * 10 * event.clientX * -1 - 25;
        var y = height * 10 * event.clientY * -1 - 50;
        this.style.backgroundPositionX = x + "px";
        this.style.backgroundPositionY = y + "px";
    });
}


var reports = document.querySelectorAll(".notifications ol>li");
for (let j = 0; j < reports.length; j++) {
    reports[j].addEventListener("click", function () {
        reports[j].classList.toggle("toggled");
    });
}
var text = document.getElementsByTagName("textarea")[0];
if (text) {
    text = text.value.replace(/\r?\n/g, '<br>');
}

let exitRegForm = document.querySelector(".form-wrapper .exit");
if (exitRegForm) {
    exitRegForm.addEventListener("click", function () {
        document.querySelector(".form-wrapper").setAttribute("data-toggled", "false");
    });
}

let exitSub = document.querySelectorAll(".subsection .exit");
if (exitSub) {
    for (let mj = 0; mj < exitSub.length; mj++) {
        exitSub[mj].addEventListener("click", function () {
            let sub = document.querySelectorAll(".subsection");
            for (let mm = 0; mm < sub.length; mm++) {
                sub[mm].setAttribute("data-toggled", "false");
            }
        });
    }
}/*CUSTOM ELES CLASS*/

class HTMLPlayer extends HTMLElement {
    constructor() {
        super();
    }
}

class NotificationBar extends HTMLElement {
    constructor() {
        super();
    }
}

class ControlPanel extends HTMLElement {
    constructor() {
        super();
    }
}

class ChatWindow extends HTMLElement {
    constructor() {
        super();
    }
}

class HTMLPlayerTime extends HTMLElement {
    constructor() {
        super();
    }
}

class ControlStrip extends HTMLElement {
    constructor() {
        super();
    }
}

class ControlRange extends HTMLElement {
    constructor() {
        super();
    }
}

class RangeFill extends HTMLElement {
    constructor() {
        super();
    }
}

class ProfileBanner extends HTMLElement {
    constructor() {
        super();
    }
}

class BannerImage extends HTMLElement {
    constructor() {
        super();
        /* Kör med css bg istället.
         var shadow = this.attachShadow({mode: 'open'}),
         imgUrl = this.getAttribute('img'),
         img = document.createElement('img');
         img.src = imgUrl;
         shadow.appendChild(img);*/
    }
}

class ProfileBar extends HTMLElement {
    constructor() {
        super();
    }
}

class UserInfo extends HTMLElement {
    constructor() {
        super();
        let mode = this.getAttribute('mode');

        if (mode === "open") {
            this.style.height = "auto";
        } else if (mode === "close") {
            this.style.height = "0";
        }
    }
}

// DEFINE  PLAYER ELES
window.customElements.define('html5-player', HTMLPlayer);
window.customElements.define('control-strip', ControlStrip);
window.customElements.define('control-range', ControlRange);
window.customElements.define('range-fill', RangeFill);
window.customElements.define('player-time', HTMLPlayerTime);
window.customElements.whenDefined('control-strip').then(() => {
    console.log("Custom elements have been defined");
});

/*DEFINE CUSTOM ELES*/
window.customElements.define('banner-image', BannerImage);
window.customElements.define('profile-banner', ProfileBanner);
window.customElements.define('profile-bar', ProfileBar);
window.customElements.define('user-info', UserInfo);
window.customElements.define('chat-window', ChatWindow);
window.customElements.define('control-panel', ControlPanel);
window.customElements.define('notification-bar', NotificationBar);

let infoToggler = document.getElementById("info-toggler");
if (infoToggler) {
    let userInfo = document.getElementsByTagName("user-info")[0];
    infoToggler.addEventListener("click", function () {
        infoToggler.classList.toggle("toggled");

        if (userInfo.getAttribute("mode") === "open")
            userInfo.setAttribute("mode", "close");
        else
            userInfo.setAttribute("mode", "open");
    });
}

/*/////////////////////////////////////////////////// VIDEO/AUDIO PLAYER ///////////////////////////////////////////// */
var vid = document.querySelectorAll("html5-player>*:not(control-strip");

/*SET TIME  */
window.addEventListener("load", function () {
    if (vid) {
        for (let i = 0; i < vid.length; i++) {
            var controlStrip = document.querySelectorAll("control-strip");

            vid[i].addEventListener('ended', function () {
                document.querySelector("control-strip button:first-of-type").classList.remove("toggled");
                vid[i].pause();
            });

/*
            if (vid[i].paused) {
                controlStrip[i].style.opacity = "1";
            } else {
                controlStrip[i].style.opacity = "0";
            }
            */

            /* /////////////////////// TIME //////////////////// */

            var vidCurrTime = document.querySelectorAll("player-time time:first-of-type"),
                    vidDurTime = document.querySelectorAll("player-time time:last-of-type"),
                    range = document.querySelectorAll("control-range"),
                    rangeFill = document.querySelectorAll("range-fill"),
                    durS = parseInt(vid[i].duration % 60),
                    durM = parseInt((vid[i].duration / 60) % 60);

            vidDurTime[i].innerHTML = durM + ":" + durS;
            vidCurrTime[i].innerHTML = "0:0";

            vid[i].addEventListener("timeupdate", function () {
                var currS = parseInt(vid[i].currentTime % 60);
                var currM = parseInt((vid[i].currentTime / 60) % 60);
                vidCurrTime[i].innerHTML = currM + ":" + currS;
                rangeFill[i].style.width = (100 / vid[i].duration) * vid[i].currentTime + "%"; /*Fill transpired*/
            });

            var MouseMove = function (e) {
                var percent = e.offsetX / this.offsetWidth;
                var media = this.parentNode.previousSibling;
                media.currentTime = percent * media.duration;
                rangeFill[i].style.width = (percent / 100);
            };

            range[i].addEventListener("mousedown", function () {
                range[i].addEventListener("mousemove", MouseMove);
            });

            range[i].addEventListener("click", MouseMove);

            range[i].addEventListener("mouseup", function () {
                range[i].removeEventListener("mousemove", MouseMove);
            });

            /* BUTTONS  */

            var mediaButtons = document.querySelectorAll("control-strip button");
            var mediaButtonArr = [].slice.call(mediaButtons);

            for (var j = 0; j < mediaButtonArr.length; j++) {
                mediaButtons[j].addEventListener("click", MediaEvent);
            }

            function MediaEvent() {
                var index = mediaButtonArr.indexOf(this);
                var media = this.parentNode.previousElementSibling;
                if (index === 0) {
                    /*Play btn*/
                    if (!media.paused) {
                        media.pause();
                        this.classList.remove("toggled");
                    } else {
                        media.play();
                        this.classList.add("toggled");
                    }
                } else if (index === 1) {
                    /*Volume btn*/
                    if (media.muted === false) {
                        media.muted = true;
                        this.className = "";
                        this.classList.add("off");
                    } else if (media.muted === true) {
                        media.muted = false;
                        this.className = "";
                        this.classList.add("high");
                    }
                }
            }
        }
    }
});if (screen && screen.width > 900) {
    var s = document.createElement("script");
    s.src = "//dashmash.ddns.net/script/paraxify.min.js";
    s.defer = true;
    document.head.appendChild(s);
}