var separator = (window.location.href.indexOf("?") === -1) ? "?" : "&";
document.getElementById('perPage').onchange = function () {
    window.location.replace(window.location.href + separator + "perPage=" + this.value);
}
document.getElementById('dateSort').onchange = function () {
    window.location.replace(window.location.href + separator + "sort=" + this.value);
}
