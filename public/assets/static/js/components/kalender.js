let tahun = new Date().getFullYear();
let bulan = new Date().getMonth();
let hari_ini = new Date().getDate();

const nama_bulan = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
];

const aic = document.getElementById("area_info_calender");
const aic_bulan = aic.querySelector(".nama_bulan");
const aic_tahun = aic.querySelector(".tahun_calender");
aic_bulan.textContent = nama_bulan[bulan];
aic_tahun.textContent = tahun;

function buildCalender(tahun, bulan) {
    aic_bulan.textContent = nama_bulan[bulan];
    aic_tahun.textContent = tahun;

    const tanggal_pertama = new Date(tahun, bulan, 1).getDay();
    const jumlah_tanggal = new Date(tahun, bulan + 1, 0).getDate();
    let template = "";
    for (let i = 1; i <= tanggal_pertama; i++) {
        template += `<span></span>`;
    }

    for (let i = 1; i <= jumlah_tanggal; i++) {
        let presensi = "";
        const tanggal = `${tahun}-${(bulan + 1).toString().padStart(2, 0)}-${i
            .toString()
            .padStart(2, 0)}`;

        const riwayat_presensi = data.find((item) => item.tanggal === tanggal);
        if (riwayat_presensi) {
            presensi = riwayat_presensi.presensi.replace(" ", "-");
        } else if (
            tahun == new Date().getFullYear() &&
            bulan == new Date().getMonth() &&
            i == new Date().getDate()
        ) {
            presensi = "hari-ini";
        }
        template += `<span class="${presensi}">${i}</span>`;
    }
    document.getElementById("calender_date").innerHTML = template;
}

buildCalender(tahun, bulan);

function prevCalender() {
    bulan--;
    if (bulan < 0) {
        bulan = 11;
        tahun--;
    }
    buildCalender(tahun, bulan);
}
function nextCalender() {
    bulan++;
    if (bulan > 11) {
        bulan = 0;
        tahun++;
    }
    buildCalender(tahun, bulan);
}
