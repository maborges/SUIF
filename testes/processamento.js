function updateMessages(processTitle, processName) {
    console.log('updateMessages');
    let elemento = document.getElementById("modalProcess").getElementsByClassName('processTitle');
    elemento.innerHTML = processTitle;

    elemento = document.getElementById("modalProcess").getElementsByClassName('processName');
    elemento.innerHTML = processName;
}

function startProcess() {
    document.getElementById("modalProcess").style.display = 'block';
}

function processCompleted() {
    console.log('processCompleted');
    document.getElementById("modalProcess").style.display = 'none';
}

