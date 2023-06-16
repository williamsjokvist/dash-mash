


document.addEventListener("DOMContentLoaded", function () {

    /* ///////////////// Editor ////////////////*/

    let btnEdit = document.getElementById('edit'),
            editToggled = false,
            refreshBanner = false,
            proSections = document.querySelectorAll(".profile-container>section:not(:first-of-type)"),
            profileSects = document.querySelectorAll('.profile-container>section>header');

    if (btnEdit) {

        btnEdit.addEventListener("click", function () {
            if (refreshBanner === true) {
                bannerElement.setAttribute("data-refresh", "true");
            } else {
                bannerElement.setAttribute("data-refresh", "false");
            }

            if (editToggled === false) {
                editToggled = true;
                btnEdit.innerHTML = 'Cancel';

                /*Create form*/
                let form = document.createElement("form");
                document.body.appendChild(form);

                form.setAttribute("id", "edit-profile");
                form.setAttribute("action", "lib/user/upload.php");
                form.setAttribute("method", "POST");
                form.setAttribute("enctype", "multipart/form-data");

                /*Create movers
                for (let bs = 0; bs < profileSects.length; bs++) {
                    let mover = document.createElement('a');
                    mover.setAttribute('title', 'Move this section');
                    mover.setAttribute('href', 'javascript:void(0)');
                    mover.classList.add('dragger');
                    profileSects[bs].appendChild(mover);
                }

                for (let bj = 0; bj < proSections.length; bj++) {
                    proSections[bj].classList.add('drag-target');
                    //  proSections[bj].classList.add('dragger');
                }

                new dragdrop.start({
                    element: '.dragger',
                    targets: '.drag-target'
                });*/


            } else if (editToggled === true) {
                editToggled = false;
                refreshBanner = false;
                btnEdit.innerHTML = 'Edit Profile';
                previewStyles = document.getElementsByClassName('color-preview');
                for (let ms = 0; ms < previewStyles.length; ms++) {
                    previewStyles[ms].innerHTML = "";
                }
                let moverz = document.querySelectorAll(".profile-container>section>header>a");
                for (let mz = 0; mz < moverz.length; mz++) {
                    moverz[mz].remove();
                    proSections[mz].classList.remove('drag-target');
                }
                document.getElementById("edit-profile").remove();
            }

            document.body.classList.toggle("edit");
        });
    }


    /*///////////////////// File preview /////////////////////////*/

    let banUpload = document.querySelector('input[name="banner-upload"]'),
            profileBanner = document.getElementsByTagName('banner-image')[0],
            bannerElement = document.getElementsByTagName('profile-banner')[0],
            profileBg = document.querySelector('.user-profile>main>section'),
            profileAvi = document.querySelector('.avatar-container>img'),
            currentAvi = profileAvi.getAttribute("src");

    banUpload.setAttribute("data-refresh", "false");

    if (window.FileReader && banUpload) {
        banUpload.addEventListener('change', function (e) {
            filePreview(e, 0);
        });
        document.querySelector('input[name="bg-upload"]').addEventListener('change', function (e) {
            filePreview(e, 1);
        });
        document.querySelector('input[name="avatar-upload"]').addEventListener('change', function (e) {
            filePreview(e, 2);
        });

        function filePreview(evt, num) {
            var file = evt.target.files[0],
                    reader = new FileReader();

            reader.onload = (function () {
                return function (e) {
                    if (num === 0) {
                        profileBanner.setAttribute("style", "background-image: url('" + e.target.result.replace(/(\r\n|\n|\r)/gm, "") + "')");
                        bannerElement.setAttribute("data-refresh", "true");
                        refreshBanner = true;
                    } else if (num === 1) {
                        profileBg.setAttribute("style", "background-image: url('" + e.target.result.replace(/(\r\n|\n|\r)/gm, "") + "')");
                    } else if (num === 2) {
                        profileAvi.setAttribute("src", e.target.result.replace(/(\r\n|\n|\r)/gm));
                    }
                };
            })(file);

            reader.readAsDataURL(file);
        }

        btnEdit.addEventListener("click", function () {
            if (editToggled === false && bannerElement.getAttribute("data-refresh") === "true") {
                profileBanner.removeAttribute("style");
            }
            profileBg.removeAttribute("style");
            profileAvi.setAttribute("src", currentAvi);
        });
    }

    /*/////////////////////// Color preview /////////////////////// */

    let selectIcon = document.querySelectorAll('input[name="icon-color"]'),
            selectLink = document.querySelectorAll('input[name="link-color"]'),
            selectBg = document.querySelectorAll('input[name="bg-color"]'),
            selectPart = document.querySelectorAll('input[name="part-color"]');

    if (selectIcon || selectLink || selectBg || selectPart) {

        /*Create style tags*/
        for (let ss = 0; ss < 4; ss++) {
            let styleTag = document.createElement("style");
            document.head.appendChild(styleTag);
            styleTag.setAttribute("class", "color-preview");
            if (ss === 0) {
                styleTag.setAttribute("id", "icon-preview");
            } else if (ss === 1) {
                styleTag.setAttribute("id", "link-preview");
            } else if (ss === 2) {
                styleTag.setAttribute("id", "bg-preview");
            } else if (ss === 3) {
                styleTag.setAttribute("id", "part-preview");
            }
        }

        /*Update style tags*/
        function colorPreview(el, value) {
            //  console.log("you got " + el + " " + value);

            /*Icons*/
            let iconStyle = document.getElementById("icon-preview"),
                    linkStyle = document.getElementById("link-preview"),
                    bgStyle = document.getElementById("bg-preview"),
                    partStyle = document.getElementById("part-preview");

            if (el === 0) {
                if (value === "0") {
                    iconStyle.innerHTML = ".profile-container h3::before, .subsection h4::before, .profile-container>section>header>a{filter: none;}";
                } else if (value === "1") {
                    iconStyle.innerHTML = ".profile-container h3::before, .subsection h4::before, .profile-container>section>header>a{filter: hue-rotate(86.25deg) saturate(175%) brightness(145%)}";
                } else if (value === "2") {
                    iconStyle.innerHTML = ".profile-container h3::before, .subsection h4::before, .profile-container>section>header>a{filter: hue-rotate(549.25deg) saturate(50%) brightness(100%);}";
                } else if (value === "3") {
                    iconStyle.innerHTML = ".profile-container h3::before, .subsection h4::before, .profile-container>section>header>a{filter: hue-rotate(244.25deg) saturate(266%) brightness(110%)}";
                } else if (value === "4") {
                    iconStyle.innerHTML = ".profile-container h3::before, .subsection h4::before, .profile-container>section>header>a{filter: hue-rotate(-91.75deg) saturate(192%) brightness(120%)}";
                } else if (value === "5") {
                    iconStyle.innerHTML = ".profile-container h3::before, .subsection h4::before, .profile-container>section>header>a{filter: hue-rotate(320deg) saturate(900%) brightness(110%)}";
                } else if (value === "6") {
                    iconStyle.innerHTML = ".profile-container h3::before, .subsection h4::before, .profile-container>section>header>a{filter: hue-rotate(307.25deg) saturate(475%) brightness(145%)}";
                }
            } else if (el === 1) {
                if (value === "0") {
                    linkStyle.innerHTML = ".profile-bar a, main a{color: var(--color-high)!important;}";
                } else if (value === "1") {
                    linkStyle.innerHTML = ".profile-bar a, main a{color:#57ff3a!important}";
                } else if (value === "2") {
                    linkStyle.innerHTML = ".profile-bar a, main a{color:#6eb4ff!important}";
                } else if (value === "3") {
                    linkStyle.innerHTML = ".profile-bar a, main a{color:#d03aff!important}";
                } else if (value === "4") {
                    linkStyle.innerHTML = ".profile-bar a, main a{color:#f95df9!important}";
                } else if (value === "5") {
                    linkStyle.innerHTML = ".profile-bar a, main a{color:#ff4343!important}";
                }
            } else if (el === 2) {
                if (value === "0") {
                    bgStyle.innerHTML = ".profile-container{background: rgba(0,0,0,0.3)!important} #topic-reply textarea, .subsection textarea{border-color:#fff!important}";
                } else if (value === "1") {
                    bgStyle.innerHTML = ".profile-container{background: rgba(26, 255, 0, 0.3)!important} #topic-reply textarea, .subsection textarea{border-color:#57ff3a!important}";
                } else if (value === "2") {
                    bgStyle.innerHTML = ".profile-container{background: rgba(0, 53, 255, 0.3)!important} #topic-reply textarea, .subsection textarea{border-color:#6eb4ff!important}";
                } else if (value === "3") {
                    bgStyle.innerHTML = ".profile-container{background: rgba(159, 0, 255, 0.3)!important} #topic-reply textarea, .subsection textarea{border-color:#d03aff}!important";
                } else if (value === "4") {
                    bgStyle.innerHTML = ".profile-container{background: rgba(218, 0, 255, 0.3)!important} #topic-reply textarea, .subsection textarea{border-color:#f95df9!important}";
                } else if (value === "5") {
                    bgStyle.innerHTML = ".profile-container{background: rgba(255, 0, 0, 0.3)!important} #topic-reply textarea, .subsection textarea{border-color:#ff4343!important}";
                } else if (value === "6") {
                    bgStyle.innerHTML = ".profile-container{background: rgba(255,255,255,0.3)!important} #topic-reply textarea, .subsection textarea{border-color:#fff!important}";
                }
            } else if (el === 3) {
                if (value === "0") {
                    partStyle.innerHTML = ".profile-container>section{background: rgba(15, 15, 15, 0.95)} #topic-reply,  .editor, .content-showcase{background-color: #0c0a0a;}";
                } else if (value === "1") {
                    partStyle.innerHTML = ".profile-container>section{background: #18291a} #topic-reply, .editor, .content-showcase{background-color: rgba(0, 0, 0, 0.5);}";
                } else if (value === "2") {
                    partStyle.innerHTML = ".profile-container>section{background: #1e1829} #topic-reply, .editor, .content-showcase{background-color: rgba(0, 0, 0, 0.5);}";
                } else if (value === "3") {
                    partStyle.innerHTML = ".profile-container>section{background: #391d4a} #topic-reply, .editor, .content-showcase{background-color: rgba(0, 0, 0, 0.5);}";
                } else if (value === "4") {
                    partStyle.innerHTML = ".profile-container>section{background: #351c2d} #topic-reply, .editor, .content-showcase{background-color: rgba(0, 0, 0, 0.5);}}";
                } else if (value === "5") {
                    partStyle.innerHTML = ".profile-container>section{background: #421a1a} #topic-reply, .editor, .content-showcase{background-color: rgba(0, 0, 0, 0.5);}";
                }
            }
        }

        /*Event Listeners*/
        for (let aa = 0; aa < selectIcon.length; aa++) {
            selectIcon[aa].addEventListener('click', function () {
                colorPreview(0, selectIcon[aa].getAttribute("value"));
            });
        }
        for (let ab = 0; ab < selectLink.length; ab++) {
            selectLink[ab].addEventListener('click', function () {
                colorPreview(1, selectLink[ab].getAttribute("value"));
            });
        }
        for (let ac = 0; ac < selectBg.length; ac++) {
            selectBg[ac].addEventListener('click', function () {
                colorPreview(2, selectBg[ac].getAttribute("value"));
            });
        }
        for (let ad = 0; ad < selectPart.length; ad++) {
            selectPart[ad].addEventListener('click', function () {
                colorPreview(3, selectPart[ad].getAttribute("value"));
            });
        }
    }
});