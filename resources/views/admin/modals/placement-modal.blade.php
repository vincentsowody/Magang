<div id="placementModal" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Penerimaan & Penempatan Magang</h5>
                <button type="button" class="close" onclick="closePlacementModal()">&times;</button>
            </div>
            
            <form id="placementForm" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="status" value="accepted">
                
                <div class="modal-body">
                    <p>Tentukan lokasi penempatan spesifik dan masa magang untuk peserta ini.</p>
                    
                    <div class="form-group mb-3">
                        <label for="lokasi_penempatan">Penempatan Spesifik (Unit/Divisi) <span class="text-danger">*</span></label>
                        <select name="lokasi_penempatan" id="lokasi_penempatan" class="form-control" required>
                            <option value="">-- Pilih Unit Penempatan --</option>
                            <option value="Aviation Security (AVSEC)">Aviation Security (AVSEC)</option>
                            <option value="Terminal Inspector">Terminal Inspector</option>
                            <option value="Information Technology (IT)">Information Technology (IT)</option>
                            <option value="Customer Service">Customer Service</option>
                            <option value="Human Capital">Human Capital</option>
                            <option value="Cargo & Logistics">Cargo & Logistics</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closePlacementModal()">Batal</button>
                    <button type="submit" class="btn btn-success">Terima & Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPlacementModal(id) {
        // 1. Gunakan RELATIVE PATH (/api/...) agar otomatis mengikuti host yang sedang dibuka di browser
        document.getElementById('placementForm').action = '/api/applicants/' + id; 
        document.getElementById('placementModal').style.display = 'block';
    }

    function closePlacementModal() {
        document.getElementById('placementModal').style.display = 'none';
        document.getElementById('placementForm').reset();
    }

    // 2. Tangani pengiriman form secara manual menggunakan Fetch API
    document.getElementById('placementForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Cegah form melakukan loading halaman (default submission)

        const form = e.target;
        const formData = new FormData(form);
        const url = form.action;

        try {
            const response = await fetch(url, {
                // Gunakan POST (FormData akan otomatis mengirim _method=PUT dari input blade)
                method: 'POST', 
                headers: {
                    // WAJIB: Beritahu Laravel agar membalas dengan JSON, bukan Redirect saat error!
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                // Jika validasi gagal (Status 422 atau 500)
                console.error("Error Validasi/Server:", data);
                alert("Gagal menyimpan: " + (data.message || "Periksa kembali isian Anda."));
                return;
            }

            // Jika sukses
            alert("Berhasil: " + data.message);
            closePlacementModal();
            window.location.reload(); // Refresh halaman untuk memperbarui tampilan tabel

        } catch (error) {
            console.error("Fetch Error:", error);
            alert("Terjadi kesalahan jaringan atau server.");
        }
    });
</script>