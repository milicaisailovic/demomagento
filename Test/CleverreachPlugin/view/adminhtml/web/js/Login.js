localStorage.removeItem('initialSyncDone');
let checkLoginCall;
let popupWindow;

function openCleverReachPopUp(url, checkUrl, redirectUrl) {
    popupWindow = window.open(url, '_blank', 'location=yes,height=570,width=900,scrollbars=yes,status=yes');
    checkLoginCall = setInterval(function () {
        checkLogin(checkUrl, redirectUrl, checkLoginCall)
    }, 1000);
}

function checkLogin(checkUrl, redirectUrl) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        console.log(this.responseText);
        if (this.readyState === 4 && this.responseText === '1') {
            clearInterval(checkLoginCall);
            popupWindow.close();
            window.location.replace(redirectUrl);
        }
    }

    xhttp.open('GET', checkUrl);
    xhttp.send();
}