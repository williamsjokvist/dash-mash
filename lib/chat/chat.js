let chat = document.querySelector('chat-window ol');

function onRequestStateChange() {
    if (this.readyState === 4 && this.status === 200) {
        chat.innerHTML += this.responseText;
        setTimeout(loadPosts, 1000);
    }
}

function loadPosts() {
    let latest = chat.lastChild,
            req = new XMLHttpRequest();

        req.addEventListener('readystatechange', onRequestStateChange);
        req.open('GET', '//dashmash.ddns.net/lib/chat/load.php?l=' + latest.getAttribute('data-msg'));
        req.send();
}

document.addEventListener("DOMContentLoaded", function () {
    let textArea = document.querySelector('chat-window textarea');

    function onRequestStateChange() {
        if (this.readyState === 4 && this.status === 200) {
            console.log("sent");
            let chatWindow = document.getElementsByTagName('chat-window');
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }
    }

    function Post(msg) {
        let req = new XMLHttpRequest();
        req.addEventListener('readystatechange', onRequestStateChange);
        req.open("GET", "//dashmash.ddns.net/lib/chat/post.php?content=" + msg);
        req.send();
    }

    document.querySelector('chat-window button').addEventListener('click', function () {
        Post(textArea.value);
        textArea.value = "";
    });
});

loadPosts();