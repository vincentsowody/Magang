// ══════════════════════════════════════════════
//  UNIVERSITY & MAJOR DATA + DROPDOWN LOGIC
// ══════════════════════════════════════════════
const UNIVERSITY_DATA = {
    // ── Pulau Jawa ──
    "Universitas Indonesia (UI)": {
        icon: "🏛️",
        location: "Jakarta",
        majors: ["Teknik Informatika", "Sistem Informasi", "Ilmu Komputer", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Teknik Industri", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Ilmu Administrasi", "Ekonomi", "Hubungan Internasional", "Sosiologi", "Ilmu Politik", "Sastra Inggris", "Arsitektur"]
    },
    "Institut Teknologi Bandung (ITB)": {
        icon: "⚙️",
        location: "Bandung",
        majors: ["Teknik Informatika", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Teknik Industri", "Teknik Kimia", "Teknik Fisika", "Matematika", "Fisika", "Kimia", "Manajemen", "Desain Komunikasi Visual", "Arsitektur", "Teknik Lingkungan", "Teknik Penerbangan", "Teknik Pertambangan", "Teknik Geologi", "Astronomi", "Meteorologi", "Desain Produk"]
    },
    "Universitas Gadjah Mada (UGM)": {
        icon: "🌿",
        location: "Yogyakarta",
        majors: ["Teknik Informatika", "Sistem Informasi", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Manajemen", "Akuntansi", "Ekonomi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Farmasi", "Ilmu Administrasi Negara", "Sosiologi", "Antropologi", "Sastra Indonesia", "Sastra Inggris", "Hubungan Internasional", "Geografi"]
    },
    "Institut Teknologi Sepuluh Nopember (ITS)": {
        icon: "🔬",
        location: "Surabaya",
        majors: ["Teknik Informatika", "Sistem Informasi", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Teknik Industri", "Teknik Kimia", "Teknik Fisika", "Matematika", "Statistika", "Teknik Kelautan", "Teknik Perkapalan", "Desain Produk Industri", "Desain Interior", "Teknik Lingkungan", "Teknik Material", "Fisika", "Kimia", "Manajemen Bisnis", "Teknik Geomatika"]
    },
    "Universitas Brawijaya (UB)": {
        icon: "🌾",
        location: "Malang",
        majors: ["Teknik Informatika", "Sistem Informasi", "Teknik Elektro", "Teknik Sipil", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Pertanian", "Peternakan", "Perikanan", "Ilmu Administrasi", "Sosiologi", "Ilmu Politik", "Ekonomi Pembangunan", "Agribisnis", "Teknologi Pangan", "Teknik Industri"]
    },
    "Universitas Padjadjaran (UNPAD)": {
        icon: "🎓",
        location: "Bandung",
        majors: ["Teknik Informatika", "Sistem Informasi", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Farmasi", "Ilmu Administrasi", "Sosiologi", "Sastra Inggris", "Hubungan Internasional", "Ilmu Politik", "Ekonomi", "Matematika", "Statistika", "Biologi", "Kimia", "Fisika"]
    },
    "Universitas Diponegoro (UNDIP)": {
        icon: "⚓",
        location: "Semarang",
        majors: ["Teknik Informatika", "Sistem Informasi", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Teknik Industri", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Ilmu Kelautan", "Perikanan", "Ilmu Administrasi", "Hubungan Internasional", "Ekonomi", "Statistika", "Arsitektur", "Teknik Kimia"]
    },
    "Universitas Airlangga (UNAIR)": {
        icon: "🐊",
        location: "Surabaya",
        majors: ["Manajemen", "Akuntansi", "Ekonomi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Farmasi", "Ilmu Administrasi", "Sosiologi", "Ilmu Politik", "Sastra Inggris", "Sistem Informasi", "Teknologi Informasi", "Kesehatan Masyarakat", "Keperawatan", "Ilmu Hubungan Internasional", "Biologi", "Kimia", "Fisika"]
    },
    "Universitas Sebelas Maret (UNS)": {
        icon: "🏔️",
        location: "Solo",
        majors: ["Teknik Informatika", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Teknik Industri", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Pendidikan", "Sastra Indonesia", "Sastra Inggris", "Ekonomi Pembangunan", "Ilmu Administrasi", "Sosiologi", "Agribisnis", "Arsitektur", "Desain Komunikasi Visual"]
    },
    "Universitas Bina Nusantara (BINUS)": {
        icon: "💻",
        location: "Jakarta",
        majors: ["Teknik Informatika", "Sistem Informasi", "Ilmu Komputer", "Teknik Elektro", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Desain Komunikasi Visual", "Teknik Industri", "Data Science", "Cyber Security", "Game Application and Technology", "Mobile Application and Technology", "Business Management", "Marketing Communication", "International Business", "Film", "Animasi", "Interior Design", "Entrepreneurship"]
    },
    "Telkom University": {
        icon: "📡",
        location: "Bandung",
        majors: ["Teknik Informatika", "Sistem Informasi", "Ilmu Komputer", "Teknik Elektro", "Teknik Telekomunikasi", "Teknik Industri", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Desain Komunikasi Visual", "Data Science", "Rekayasa Perangkat Lunak", "Teknik Komputer", "Business Administration", "Teknik Biomedis", "Teknik Material", "Administrasi Bisnis", "Creative Arts", "Teknik Logistik", "Teknik Mesin"]
    },
    "Universitas Mercu Buana": {
        icon: "🏙️",
        location: "Jakarta",
        majors: ["Teknik Informatika", "Sistem Informasi", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Teknik Industri", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Desain Produk", "Arsitektur", "Psikologi", "Teknik Kimia", "Periklanan", "Broadcasting", "Hubungan Masyarakat", "Ilmu Administrasi", "Teknik Lingkungan", "Desain Interior", "Ekonomi"]
    },
    // ── Luar Jawa ──
    "Universitas Sam Ratulangi (UNSRAT)": {
        icon: "🌴",
        location: "Manado",
        majors: ["Teknik Informatika", "Sistem Informasi", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Pertanian", "Perikanan", "Ilmu Administrasi", "Sosiologi", "Matematika", "Fisika", "Biologi", "Kimia", "Keperawatan"]
    },
    "Politeknik Negeri Manado": {
        icon: "🔧",
        location: "Manado",
        majors: ["Teknik Informatika", "Manajemen Informatika", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Akuntansi", "Administrasi Bisnis", "Teknik Listrik", "Teknik Telekomunikasi", "Komputerisasi Akuntansi"]
    },
    "Universitas Klabat": {
        icon: "🏫",
        location: "Manado",
        majors: ["Teknik Informatika", "Sistem Informasi", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Hukum", "Pendidikan", "Teologi", "Keperawatan", "Bahasa Inggris"]
    },
    "Universitas De La Salle Manado": {
        icon: "✝️",
        location: "Manado",
        majors: ["Teknik Informatika", "Sistem Informasi", "Manajemen", "Akuntansi", "Teknik Sipil", "Arsitektur", "Farmasi", "Keperawatan", "Pendidikan", "Hukum"]
    },
    "Universitas Hasanuddin (UNHAS)": {
        icon: "🌊",
        location: "Makassar",
        majors: ["Teknik Informatika", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Teknik Industri", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Kelautan", "Perikanan", "Pertanian", "Kehutanan", "Ilmu Administrasi", "Sosiologi", "Sastra Indonesia", "Hubungan Internasional", "Ekonomi"]
    },
    "Universitas Udayana (UNUD)": {
        icon: "🌺",
        location: "Bali",
        majors: ["Teknik Informatika", "Sistem Informasi", "Teknik Elektro", "Teknik Sipil", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Pariwisata", "Sastra", "Pertanian", "Peternakan", "Keperawatan", "Ilmu Administrasi", "Sosiologi", "Matematika", "Fisika", "Biologi"]
    },
    "Universitas Sumatera Utara (USU)": {
        icon: "🌳",
        location: "Medan",
        majors: ["Teknik Informatika", "Teknik Elektro", "Teknik Sipil", "Teknik Mesin", "Teknik Industri", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Psikologi", "Hukum", "Kedokteran", "Farmasi", "Pertanian", "Kehutanan", "Ilmu Administrasi", "Sosiologi", "Sastra Inggris", "Ekonomi", "Kesehatan Masyarakat", "Keperawatan"]
    },
    "Universitas Sriwijaya (UNSRI)": {
        icon: "🏺",
        location: "Palembang",
        majors: ["Teknik Informatika", "Teknik Elektro", "Teknik Sipil", "Teknik Kimia", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Hukum", "Kedokteran", "Pertanian", "Ilmu Administrasi", "Ekonomi", "Matematika", "Fisika", "Kimia", "Biologi", "Keperawatan", "Kesehatan Masyarakat", "Pendidikan", "Teknik Mesin"]
    },
    "Universitas Mulawarman (UNMUL)": {
        icon: "🌿",
        location: "Samarinda",
        majors: ["Teknik Informatika", "Teknik Elektro", "Teknik Sipil", "Manajemen", "Akuntansi", "Ilmu Komunikasi", "Hukum", "Kedokteran", "Pertanian", "Kehutanan", "Perikanan", "Ilmu Administrasi", "Ekonomi", "Matematika", "Biologi", "Kimia", "Keperawatan", "Kesehatan Masyarakat", "Fisika", "Teknik Mesin"]
    },
};

let selectedUniv = null;
let univDropdownOpen = false;
let majorDropdownOpen = false;

// ── BUILD UNIV LIST ──
function buildUnivList(filter = '') {
    const list = document.getElementById('univ-list');
    list.innerHTML = '';
    const keys = Object.keys(UNIVERSITY_DATA).filter(u =>
        u.toLowerCase().includes(filter.toLowerCase()) ||
        UNIVERSITY_DATA[u].location.toLowerCase().includes(filter.toLowerCase())
    );
    if (!keys.length) {
        list.innerHTML = `<div style="padding:16px;text-align:center;font-size:12px;color:rgba(100,116,139,0.5)">Tidak ditemukan</div>`;
        return;
    }
    keys.forEach(univ => {
                const d = UNIVERSITY_DATA[univ];
                const isSelected = selectedUniv === univ;
                const item = document.createElement('div');
                item.style.cssText = `padding:9px 12px;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:10px;transition:background .15s;${isSelected?'background:rgba(0,185,232,0.1);':''}`;
                item.innerHTML = `
                        <span style="font-size:16px;flex-shrink:0">${d.icon}</span>
                        <div style="flex:1;min-width:0">
                            <div style="font-size:12px;font-weight:600;color:${isSelected?'#00b9e8':'#e2e8f0'};white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${univ}</div>
                            <div style="font-size:10px;color:rgba(100,116,139,0.55);margin-top:1px">📍 ${d.location} · ${d.majors.length} prodi</div>
                        </div>
                        ${isSelected ? `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00b9e8" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>` : ''}`;
                    item.onmouseenter = () => { if (!isSelected) item.style.background = 'rgba(255,255,255,0.04)'; };
                    item.onmouseleave = () => { if (!isSelected) item.style.background = ''; };
                    item.onclick = () => selectUniv(univ);
                    list.appendChild(item);
                });
            }

            function selectUniv(univ) {
                selectedUniv = univ;
                // Update display
                const d = UNIVERSITY_DATA[univ];
                document.getElementById('univ-display-text').textContent = `${d.icon} ${univ}`;
                document.getElementById('univ-display-text').style.color = '#e2e8f0';
                document.getElementById('reg-univ').value = univ;
                closeUnivDropdown();
                // Reset major
                document.getElementById('reg-major').value = '';
                document.getElementById('major-display-text').textContent = 'Pilih Program Studi...';
                document.getElementById('major-display-text').style.color = 'rgba(100,116,139,0.5)';
                buildMajorList();
            }

            // ── BUILD MAJOR LIST ──
            function buildMajorList(filter = '') {
                const list = document.getElementById('major-list');
                list.innerHTML = '';
                if (!selectedUniv) {
                    list.innerHTML = `<div style="padding:16px;text-align:center;font-size:12px;color:rgba(100,116,139,0.5)">Pilih universitas terlebih dahulu</div>`;
                    return;
                }
                const majors = UNIVERSITY_DATA[selectedUniv].majors.filter(m =>
                    m.toLowerCase().includes(filter.toLowerCase())
                );
                if (!majors.length) {
                    list.innerHTML = `<div style="padding:16px;text-align:center;font-size:12px;color:rgba(100,116,139,0.5)">Tidak ditemukan</div>`;
                    return;
                }
                majors.forEach(major => {
                    const curVal = document.getElementById('reg-major').value;
                    const isSelected = curVal === major;
                    const item = document.createElement('div');
                    item.style.cssText = `padding:9px 12px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;gap:8px;transition:background .15s;${isSelected?'background:rgba(0,185,232,0.1);':''}`;
                    item.innerHTML = `
                        <div style="display:flex;align-items:center;gap:8px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="${isSelected?'#00b9e8':'rgba(100,116,139,0.4)'}" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            <span style="font-size:13px;font-weight:500;color:${isSelected?'#00b9e8':'#cbd5e1'}">${major}</span>
                        </div>
                        ${isSelected ? `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#00b9e8" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>` : ''}`;
                    item.onmouseenter = () => { if (!isSelected) item.style.background = 'rgba(255,255,255,0.04)'; };
                    item.onmouseleave = () => { if (!isSelected) item.style.background = ''; };
                    item.onclick = () => selectMajor(major);
                    list.appendChild(item);
                });
            }

            function selectMajor(major) {
                document.getElementById('reg-major').value = major;
                document.getElementById('major-display-text').textContent = major;
                document.getElementById('major-display-text').style.color = '#e2e8f0';
                closeMajorDropdown();
            }

            // ── TOGGLE / OPEN / CLOSE ──
            function toggleUnivDropdown() {
                if (univDropdownOpen) { closeUnivDropdown(); return; }
                openUnivDropdown();
            }
            function openUnivDropdown() {
                closeMajorDropdown();
                univDropdownOpen = true;
                const panel = document.getElementById('univ-panel');
                const chevron = document.getElementById('univ-chevron');
                panel.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
                buildUnivList();
                setTimeout(() => document.getElementById('univ-search').focus(), 50);
            }
            function closeUnivDropdown() {
                univDropdownOpen = false;
                document.getElementById('univ-panel').style.display = 'none';
                document.getElementById('univ-chevron').style.transform = 'rotate(0deg)';
            }
            function toggleMajorDropdown() {
                if (!selectedUniv) { showToast('Pilih Universitas', 'Pilih universitas terlebih dahulu.', 'error'); return; }
                if (majorDropdownOpen) { closeMajorDropdown(); return; }
                openMajorDropdown();
            }
            function openMajorDropdown() {
                closeUnivDropdown();
                majorDropdownOpen = true;
                const panel = document.getElementById('major-panel');
                const chevron = document.getElementById('major-chevron');
                panel.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
                buildMajorList();
            }
            function closeMajorDropdown() {
                majorDropdownOpen = false;
                document.getElementById('major-panel').style.display = 'none';
                document.getElementById('major-chevron').style.transform = 'rotate(0deg)';
            }
            function filterUnivList() {
                buildUnivList(document.getElementById('univ-search').value);
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                const univWrap = document.getElementById('univ-dropdown-wrap');
                const majorWrap = document.getElementById('major-dropdown-wrap');
                if (!univWrap || !univWrap.contains(e.target)) closeUnivDropdown();
                if (!majorWrap || !majorWrap.contains(e.target)) closeMajorDropdown();
            });

// Catatan refactor: dua blok di bawah ini awalnya tercecer di lokasi
// yang jauh dari sini di file aslinya (satu di dekat bagian Laporan,
// satu lagi di paling bawah script). Disatukan ke sini karena memang
// secara logika berhubungan dengan reset state dropdown universitas/jurusan.
const _origCloseReg = window.closeRegModal;

            window.closeRegModal = function() {
                if (_origCloseReg) _origCloseReg();
                selectedUniv = null;
                document.getElementById('univ-display-text').textContent = 'Pilih Universitas...';
                document.getElementById('univ-display-text').style.color = 'rgba(100,116,139,0.5)';
                document.getElementById('major-display-text').textContent = 'Pilih universitas dulu...';
                document.getElementById('major-display-text').style.color = 'rgba(100,116,139,0.5)';
                document.getElementById('reg-univ').value = '';
                document.getElementById('reg-major').value = '';
                closeUnivDropdown();
                closeMajorDropdown();
            };