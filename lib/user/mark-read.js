let msg = document.querySelectorAll(".notifications>ol>li[data-pm]"),
        fr = document.querySelectorAll(".notifications>ol>li[data-fr]"),
        btn = document.getElementsByClassName('mark-read'),
        bell = document.querySelector("#bell>span"),
        req;

/*Messages*/
for (let i = 0; i < msg.length; i++) {
    let currmsg = msg[i],
            msgId = msg[i].getAttribute('data-pm');

    msg[i].childNodes[0].lastChild.lastChild.addEventListener('click', function () {
        req = new XMLHttpRequest();
        currmsg.remove();
        bell.innerHTML -= 1;
        req.open("GET", "//dashmash.ddns.net/lib/user/mark-read.php?id=" + msgId);
        req.send();
    });
}

/*Friend requests*/
for (let i = 0; i < fr.length; i++) {
    let currFr = fr[i],
            frId = currFr.getAttribute('data-fr');

    for (let j = 0; j < 2; j++) {
        currFr.childNodes[1].childNodes[j].addEventListener('click', function () {
            currFr.remove();
            bell.innerHTML -= 1;
            req = new XMLHttpRequest();
            req.open("GET", "//dashmash.ddns.net/lib/user/handle-fr.php?id=" + frId + "&accept=" + this.getAttribute('data-fr-accept'));
            req.send();
        });
    }
}