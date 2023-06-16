/*CUSTOM ELES CLASS*/

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
});