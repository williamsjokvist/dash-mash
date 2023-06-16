
/*Game focus*/

window.addEventListener("load", function () {
    let iframe = document.getElementsByTagName('iframe')[0];
    if (iframe) {
        let inner = (iframe.contentDocument || iframe.contentWindow),
                canv = inner.getElementsByTagName("canvas")[0];

        canv.addEventListener("click", function () {
            canv.focus();
        });
    }
});

/*Button functionality*/

var buttons = document.querySelectorAll(".play section:last-of-type button");
var buttArr = [].slice.call(buttons);

for (var i = 0; i < buttArr.length; i++) {
    buttons[i].addEventListener("click", event);
}

function event(el) {

    var window = document.querySelector(".play>section:first-of-type");
    var windowSettings = document.querySelector(".play>section:last-of-type");
    var index = buttArr.indexOf(el.target);

    if (index === 0) {
        window.classList.toggle("expanded");
        windowSettings.classList.toggle("expanded");
        if (window.classList.contains("expanded")) {
            el.target.innerHTML = "Minimize";
            el.target.classList.add("toggled");
        } else {
            el.target.innerHTML = "Expand";
            el.target.classList.remove("toggled");
        }
    } else if (index === 2) {

        var moody = document.querySelector(".moody");

        if (!moody.classList.contains("on")) {
            moody.classList.add("on");
            el.target.innerHTML = 'Lights On';
        } else {
            moody.classList.remove("on");
            el.target.innerHTML = 'Lights Off';
        }
    }
}