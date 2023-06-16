if (window.opener && window.opener !== window) {
    var el = document.createElement("a");
    el.innerHTML = "Quit";
    el.setAttribute("href", "javascript:window.close()");
    document.querySelector("form>footer").appendChild(el);
}