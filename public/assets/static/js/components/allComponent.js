// window.addEventListener("load", function () {
// });

document.addEventListener("DOMContentLoaded", function () {
    presensiStatus();
    setTimeout(() => {
        const wrapperLoading = document.querySelector(".wrapperLoading");
        if (wrapperLoading) {
            wrapperLoading.classList.add("animasi");
            wrapperLoading.innerHTML = "";
        }
    }, 500);
});

// fungsi cek value presensi
function presensiStatus(e) {
    let aksesHadir = false;
    if (e) {
        keteranganPresensi = document.getElementById(
            "keterangan_presensi_" + e.id.slice(16)
        );
        aksesHadir = document.getElementById("akses_hadir_" + e.id.slice(16));
    } else {
        e = document.getElementById("presensi_status");
        keteranganPresensi = document.getElementById("keterangan_presensi");
    }

    if (e && keteranganPresensi)
        if (e.value !== "hadir") {
            keteranganPresensi.classList.remove("d-none");
            if (aksesHadir) aksesHadir.classList.add("d-none");
        } else {
            keteranganPresensi.classList.add("d-none");
            if (aksesHadir) aksesHadir.classList.remove("d-none");
        }
}

// fungsi profile nav
function borderBottom(el, event) {
    event.preventDefault();

    const customContent = document.querySelectorAll(".customContent");
    customContent.forEach((cc) => {
        cc.classList.add("d-none");
    });

    const target_href = el.getAttribute("href").slice(1);
    document.getElementById(target_href).classList.remove("d-none");

    const customContentEdit = document.querySelectorAll(".custom_content_edit");
    customContentEdit.forEach((cce) => cce.classList.add("d-none"));

    const aNoWrap = document.querySelectorAll("a.text-nowrap");
    aNoWrap.forEach((anw) => {
        anw.classList.remove("border-bottom", "border-primary", "border-2");
    });
    el.classList.add("border-bottom", "border-primary", "border-2");
}

// fungsi profile edit
function showEdit(el, event) {
    // event.preventDefault();
    const customContentEdit = document.querySelectorAll(".custom_content_edit");
    customContentEdit.forEach((cce) => cce.classList.add("d-none"));
    const target_href = el.getAttribute("href").slice(1);
    document.getElementById(target_href).classList.remove("d-none");
}

// fungsi textarea panjang
function autoHeight(el) {
    el.style.height = "auto";
    el.style.height = el.scrollHeight + "px";
    el.style.maxHeight = "400px";
}

// fungsi menampilkan gambar
function showProfile(el) {
    const img_target = el.parentElement.querySelector("img");
    img_target.src = URL.createObjectURL(el.files[0]);
}

//fungsi arah collapse
function changeDirection(e) {
    const bi = e.querySelector(".bi");
    if (bi.classList.contains("bi-caret-down-fill")) {
        e.innerHTML = `<i class="bi bi-caret-up-fill"></i>`;
    } else {
        e.innerHTML = `<i class="bi bi-caret-down-fill"></i>`;
    }
}

// fungsi menampilkan password
function showPassword(e) {
    const password = document.getElementById("password");
    if (password.type == "password") {
        password.type = "text";
        e.innerHTML = `<i class="bi bi-lock-fill"></i>`;
    } else {
        password.type = "password";
        e.innerHTML = `<i class="bi bi-unlock-fill"></i>`;
    }
}

// fungsi loading button
function proses(e) {
    const val = e.innerHTML;
    e.classList.add("disabled");
    e.innerHTML = `
        <span class="d-flex gap-2 align-items-center">
            <span class="spinner-border spinner-border-sm" ></span>
            <span class="d-none d-md-block">Loading...</span>
        </span>
            `;
    setTimeout(() => {
        e.classList.remove("disabled");
        e.innerHTML = val;
    }, 60000);
}
