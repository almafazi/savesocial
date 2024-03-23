function insertAndExecute(id, text) {
    domelement = document.getElementById(id);
    domelement.innerHTML = text;
    var scripts = [];
    ret = domelement.childNodes;
    for (var i = 0; ret[i]; i++) {
        if (scripts && nodeName(ret[i], "script") && (!ret[i].type || ret[i].type.toLowerCase() === "text/javascript")) {
            scripts.push(ret[i].parentNode ? ret[i].parentNode.removeChild(ret[i]) : ret[i]);
        }
    }
    for (script in scripts) {
        evalScript(scripts[script]);
    }
}

function nodeName(elem, name) {
    return elem.nodeName && elem.nodeName.toUpperCase() === name.toUpperCase();
}

function evalScript(elem) {
    data = (elem.text || elem.textContent || elem.innerHTML || "");
    var head = document.getElementsByTagName("head")[0] || document.documentElement,
        script = document.createElement("script");
    script.type = "text/javascript";
    script.appendChild(document.createTextNode(data));
    head.insertBefore(script, head.firstChild);
    head.removeChild(script);
    if (elem.parentNode) {
        elem.parentNode.removeChild(elem);
    }
}
var j = 0;

function runProgress() {
    if (0 == j) {
        j = 1;
        var e = document.querySelector(".progress-bar");
        e.style.width = 0;
        var r = 1,
            t = setInterval(function() {
                r >= 99 ? (clearInterval(t), j = 0) : (r++, e.ariaValueNow = r, e.style.width = r + "%")
            }, 140)
    }
}
var form = document.forms.namedItem("formurl");
var formdy = document.forms.namedItem("formurldy");
var formtm = document.forms.namedItem("formurltm");
var formts = document.forms.namedItem("formurlts");
var progress = document.querySelector(".progress-box");
if (form) {
    form.addEventListener('submit', function(ev) {
        //var oData = new FormData(form);
        progress.classList.add('active');
        var oData = JSON.stringify({
            "url": document.getElementById("url").value,
            "menu": document.getElementsByName("menu")[0]?.value ? document.getElementsByName("menu")[0]?.value : null,
            "download_url": "https://snaptik.vip/tiktok/download",
            "website_url": "https://snaptik.vip",
            "webpage_download_url": "https://node2.canehill.info/wdl/download",
         });
        progress.classList.add('active');
        runProgress();
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "https://node2.canehill.info/dl/tiktok", true);
        oReq.setRequestHeader("Content-Type", "application/json");
        oReq.setRequestHeader("Accept", "application/json");
        oReq.onload = function(oEvent) {
            if (oReq.status === 200) {
                var data = JSON.parse(oReq.response);
                if (data.error) {
                    j = 0;
                    progress.classList.remove('active');
                    showAlert("Error: " + data.error);
                } else {
                    $('#hero').remove();
                    $('#content').remove();
                    document.getElementById('download').innerHTML = data.html;
                }
            } else {
                showAlert("Error " + oReq.status + " something went wrong.");
            }
        };
        oReq.send(oData);
        ev.preventDefault();
    }, false);
}
if (formdy) {
    formdy.addEventListener('submit', function(ev) {
        var oData = new FormData(formdy);
        progress.classList.add('active');
        runProgress();
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "/check-dy/", true);
        oReq.onload = function(oEvent) {
            if (oReq.status === 200) {
                var data = JSON.parse(oReq.response);
                if (data.error) {
                    j = 0;
                    progress.classList.remove('active');
                    showAlert("Error: " + data.error);
                } else {
                    $('#hero').remove();
                    $('#content').remove();
                    document.getElementById('download').innerHTML = data.html;
                }
            } else {
                showAlert("Error " + oReq.status + " something went wrong.");
            }
        };
        oReq.send(oData);
        ev.preventDefault();
    }, false);
}
if (formtm) {
    formtm.addEventListener('submit', function(ev) {
        var oData = new FormData(formtm);
        progress.classList.add('active');
        runProgress();
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "/check-tm/", true);
        oReq.onload = function(oEvent) {
            if (oReq.status === 200) {
                var data = JSON.parse(oReq.response);
                if (data.error) {
                    j = 0;
                    progress.classList.remove('active');
                    showAlert("Error: " + data.error);
                } else {
                    $('#hero').remove();
                    $('#content').remove();
                    document.getElementById('download').innerHTML = data.html;
                }
            } else {
                showAlert("Error " + oReq.status + " something went wrong.");
            }
        };
        oReq.send(oData);
        ev.preventDefault();
    }, false);
}
if (formts) {
    formts.addEventListener('submit', function(ev) {
        var oData = new FormData(formts);
        progress.classList.add('active');
        runProgress();
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "/check-ts/", true);
        oReq.onload = function(oEvent) {
            if (oReq.status === 200) {
                var data = JSON.parse(oReq.response);
                if (data.error) {
                    j = 0;
                    progress.classList.remove('active');
                    showAlert("Error: " + data.error);
                } else {
                    $('#hero').remove();
                    $('#content').remove();
                    document.getElementById('download').innerHTML = data.html;
                }
            } else {
                showAlert("Error " + oReq.status + " something went wrong.");
            }
        };
        oReq.send(oData);
        ev.preventDefault();
    }, false);
}

function iOS() {
    return ['iPad Simulator', 'iPhone Simulator', 'iPod Simulator', 'iPad', 'iPhone', 'iPod'].includes(navigator.platform) || (navigator.userAgent.includes("Mac") && "ontouchend" in document)
}

function isAndroid() {
    var useragent = navigator.userAgent.toLowerCase();
    return useragent.indexOf("android") > -1;
}

function openModal(e) {
    e.classList.add("is-active"), document.getElementById("body").style.overflow = "hidden"
}

function closeModal(e) {
    e.classList.remove("is-active"), document.getElementById("body").style.overflow = "auto"
}

function closeAllModals() {
    (document.querySelectorAll(".modal") || []).forEach(e => {
        closeModal(e)
    })
}

function showAd(e, l) {
    var t = document.querySelectorAll('ins[data-vignette-loaded="true"]');
    0 == t.length && iOS() && l.preventDefault(), l.stopPropagation(), openModal(document.getElementById("ad-modal")), document.getElementById("ads-content").innerHTML = '<ins class="adsbygoogle" style="display:block; margin: 0 auto;" data-ad-client="ca-pub-9671911224630064" data-ad-slot="1542130732" data-ad-format="auto" data-full-width-responsive="true"></ins>', (adsbygoogle = window.adsbygoogle || []).push({}), 0 == t.length && iOS() && setTimeout(function() {
        null != e.getAttribute("href") ? window.location.href = e.getAttribute("href") : ''
    }, 1e3)
}(document.querySelectorAll(".modal-background, .modal-close, .btn-modal-close") || []).forEach(e => {
    var l = e.closest(".modal-down");
    e.addEventListener("click", () => {
        closeModal(l)
    })
}), document.addEventListener("keydown", e => {
    27 === (e || window.event).keyCode && closeAllModals()
});
if (isAndroid()) {
    document.querySelector('.navbar-app').style.display = 'flex';
}
var toggleSwitch = document.querySelector(".btn-darkmode");
toggleSwitch.addEventListener("click", function() {
    document.body.classList.toggle("dark-theme");
    var theme = document.body.classList.contains("dark-theme") ? "dark" : "light";
    localStorage.setItem("theme", theme);
});
var btnPaste = document.querySelector(".btn-paste");
var inputUrl = document.getElementById("url");

function showBtnClear() {
    btnPaste.classList.add('active');
    document.querySelector(".btn-paste span").innerHTML = lang.clear;
}
if (!navigator.clipboard?.readText) {
    document.querySelector('.paste').style.display = 'none';
}
btnPaste.addEventListener("click", function() {
    if (btnPaste.classList.contains("active")) {
        inputUrl.value = "";
        if (navigator.clipboard) {
            btnPaste.classList.remove('active');
            document.querySelector(".btn-paste span").innerHTML = lang.paste;
        }
    } else {
        if (navigator.clipboard) {
            navigator.clipboard.readText().then(function(e) {
                if (e != "") {
                    inputUrl.value = e;
                    showBtnClear();
                } else {
                    showAlert(lang.linkEmpty);
                }
            })
        }
    }
});
if (navigator.clipboard) {
    btnPaste.style.display = "flex";
}
inputUrl.addEventListener("keyup", function(e) {
    if (inputUrl.value.length > 0) {
        showBtnClear();
    }
    hideAlert();
});
var alertEL = document.getElementById("alert");

function showAlert(mess) {
    alertEL.classList.add('active');
    alertEL.innerHTML = mess;
}

function hideAlert(mess) {
    alertEL.classList.remove('active');
    alertEL.innerHTML = '';
}

function toggleLang() {
    document.querySelectorAll('.navbar-lang')[0].classList.toggle('show');
    document.querySelector('.dropdown-lang').classList.toggle('show')
}
var accBtns = document.querySelectorAll(".accordion-button")
var accContents = document.querySelectorAll(".accordion-content")
accBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
        var accContent = btn.nextElementSibling
        if (btn.classList.contains("active")) {
            btn.classList.remove("active");
            accContent.style.maxHeight = null;
        } else {
            var accsIsOpen = document.querySelectorAll(".accordion-button.active");
            accsIsOpen.forEach((accIsOpen) => {
                accIsOpen.classList.remove("active");
                accIsOpen.nextElementSibling.style.maxHeight = null;
            });
            btn.classList.add("active");
            accContent.style.maxHeight = accContent.scrollHeight + "px";
        }
    });
});
var shareButton = document.querySelector('.share-button');
shareButton.addEventListener('click', event => {
    if (navigator.share) {
        navigator.share({
            title: 'Share SnapTik',
            url: url + '/' + lang.currentLang + '/'
        }).then(() => {}).catch(console.error);
    } else {}
});
var download = document.querySelector('.download');
var webShareSupported = 'canShare' in navigator;
var handleShareFile = async (blob, fileName, title, text) => {
    if (webShareSupported) {
        var data = {
            files: [new File([blob], fileName, {
                type: blob.type,
            }), ],
            title,
            text,
        };
        if (navigator.canShare(data)) {
            try {
                await navigator.share(data);
                console.log('Successfully sent share');
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error(err.name, err.message);
                }
            } finally {
                return;
            }
        }
    }
};
if (webShareSupported) {
    download.classList.add('can-share');
}
download.addEventListener('click', async (e) => {
    var fileShareNode = e.target.closest('.btn-share');
    if (fileShareNode) {
        fileShareNode.innerText = "please wait";
        var blob = await fetch(fileShareNode.dataset.file).then(res => res.blob());
        await handleShareFile(blob, fileShareNode.dataset.filename, fileShareNode.dataset.title, fileShareNode.dataset.desc);
    }
});