function diffForHumans(waktu) {
    const waktu_berlalu = new Date(waktu).getTime();
    const waktu_sekarang = new Date().getTime();
    const waktu_selisih = waktu_sekarang - waktu_berlalu;
    const detik = Math.round(waktu_selisih / 1000);
    const menit = Math.round(detik / 60);
    const jam = Math.round(detik / 3600);
    const hari = Math.round(detik / 86400);
    const minggu = Math.round(detik / 604800);
    const bulan = Math.round(detik / 2629440);
    const tahun = Math.round(detik / 31553280);
    if (detik <= 60) {
        return "baru saja";
    } else if (menit <= 60) {
        return `${menit} menit yang lalu`;
    } else if (jam <= 24) {
        return `${jam} jam yang lalu`;
    } else if (hari <= 7) {
        return `${hari} hari yang lalu`;
    } else if (minggu <= 4) {
        return `${minggu} minggu yang lalu`;
    } else if (bulan <= 12) {
        return `${bulan} bulan yang lalu`;
    } else {
        return `${tahun} tahun yang lalu`;
    }
}

function updateDiffForHumans() {
    const classDiffForHumans = document.querySelectorAll(".classDiffForHumans");
    classDiffForHumans.forEach((item) => {
        const getAttributeItem = item.getAttribute("data-created-at");
        item.textContent = diffForHumans(getAttributeItem);
    });
}

updateDiffForHumans();
setInterval(updateDiffForHumans, 1000);
