function redirect(url) {
    window.location.href = url;
}

function deleteErrMsg() {
    document.querySelectorAll('.authinp.msg').forEach(function(msg) {
        setTimeout(() => {
            msg.classList.toggle('hidden');
            setTimeout(() => {
                msg.remove();
            }, 300);
        }, 4000);
    });
    document.querySelectorAll('.authinp.err').forEach(function(msg) {
        setTimeout(() => {
            msg.classList.toggle('hidden');
            setTimeout(() => {
                msg.remove();
            }, 300);
        }, 4000);
    });
}

window.onload = function() {
    setInterval(deleteErrMsg, 500);
}