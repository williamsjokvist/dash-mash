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
}

let accountDialog = document.getElementsByClassName('account_msg')[0];

accountDialog.addEventListener('click', function (){
    accountDialog.removeAttribute('open');
});