initialSync = function () {
    if(localStorage.getItem('initialSyncDone') === null || localStorage.getItem('initialSyncDone') !== 'done') {
        Demomagento.ajaxService.get(document.getElementById('syncUrl').value, initialSyncDone, initialSyncError);
    } else {
        initialSyncDone();
    }
}

initialSyncDone = function () {
    document.getElementById('statusValue').innerHTML = 'Done';
    document.getElementById('statusValue').style.color = 'forestgreen';
    localStorage.setItem('initialSyncDone', 'done');
}

initialSyncError = function () {
    document.getElementById('statusValue').innerText = 'Error';
    document.getElementById('statusValue').style.color = 'red';
}