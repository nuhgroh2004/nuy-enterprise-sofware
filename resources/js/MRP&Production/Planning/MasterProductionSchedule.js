const pageInfo = {
    planning: {
        title: "Production Planning",
        sub: "Rencana produksi aktif — Agustus 2026"
    },

    calendar: {
        title: "Production Calendar",
        sub: "Sebaran beban produksi harian dalam tampilan kalender"
    },

    capacity: {
        title: "Capacity Planning",
        sub: "Utilisasi kapasitas tiap work center saat ini"
    },

    adjustment: {
        title: "Schedule Adjustment",
        sub: "Riwayat dan usulan perubahan jadwal produksi"
    },

    priority: {
        title: "Priority",
        sub: "Urutan prioritas pengerjaan seluruh jadwal produksi"
    }
};


document.querySelectorAll('.menu-pill').forEach(pill => {

    pill.addEventListener('click', () => {

        document
            .querySelectorAll('.menu-pill')
            .forEach(item => item.classList.remove('active'));

        pill.classList.add('active');

        const tab = pill.dataset.tab;

        if (!pageInfo[tab]) {
            return;
        }

        document.getElementById('pageTitle').textContent =
            pageInfo[tab].title;

        document.getElementById('pageSub').textContent =
            pageInfo[tab].sub;

    });

});