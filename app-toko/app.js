const URL_API = '../api-toko/';

if (!localStorage.getItem('token_toko')) {
    window.location.href = 'login.html';
}

async function ambilDataBarang() {
    const tokenSegar = localStorage.getItem('token_toko') || '9397803a71d9ac82e33827b0e08b765d';

    try {
        const response = await fetch(`${URL_API}get_barang.php?token=${tokenSegar}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const hasil = await response.json();
        let html = '';

        if (hasil.status === 'success' && hasil.data) {
            hasil.data.forEach((barang, index) => {
                html += `
                <tr class="border-b hover:bg-gray-100 transition">
                    <td class="p-3 text-center font-medium">${index + 1}</td>
                    <td class="p-3">${barang.nama_barang}</td>
                    <td class="p-3">
                        <div class="flex justify-center">
                            <div class="w-[120px] flex items-center gap-1">
                                <span class="font-medium">Rp</span>
                                <span>${parseInt(barang.harga).toLocaleString()}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-3 min-w-[140px]">
                        <div class="flex gap-2 justify-center">
                            <button onclick="editBarang(${barang.id}, '${barang.nama_barang}', ${barang.harga})"
                                    class="border border-yellow-400 hover:bg-yellow-400 hover:text-white p-2 rounded-lg text-yellow-500 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 14.536A4 4 0 019.172 16H7v-2.172A4 4 0 018.464 10.536L15 4"/>
                                </svg>
                            </button>
                            <button onclick="hapusBarang(${barang.id})"
                                    class="border border-red-400 hover:bg-red-500 hover:text-white p-2 rounded-lg text-red-500 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12M10 11v6M14 11v6M9 7V4h6v3"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
        } else {
            html = `<tr><td colspan="4" class="p-3 text-center text-gray-500">${hasil.pesan || 'Data kosong.'}</td></tr>`;
        }

        document.getElementById('tabel-barang').innerHTML = html;
    } catch (error) {
        console.error(error);
        document.getElementById('tabel-barang').innerHTML = `<tr><td colspan="4" class="p-3 text-center text-red-500">Akses Ditolak atau Masalah Koneksi API.</td></tr>`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    ambilDataBarang();

    const form = document.getElementById('formBarang');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const nama = encodeURIComponent(document.getElementById('nama_barang').value);
            const harga = document.getElementById('harga').value;
            const tokenSegar = localStorage.getItem('token_toko') || '9397803a71d9ac82e33827b0e08b765d';

            try {
                const response = await fetch(`${URL_API}tambah_barang.php?token=${tokenSegar}&nama_barang=${nama}&harga=${harga}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const hasil = await response.json();
                if (hasil.status === 'success') {
                    alert('Data berhasil ditambahkan');
                    form.reset();
                    ambilDataBarang();
                } else {
                    alert('Gagal: ' + hasil.pesan);
                }
            } catch (error) {
                console.log(error);
                alert('Error koneksi atau Akses Ditolak');
            }
        });
    }
});

async function hapusBarang(id) {
    const yakin = confirm('Yakin ingin menghapus data ini?');
    if (!yakin) return;
    const tokenSegar = localStorage.getItem('token_toko') || '9397803a71d9ac82e33827b0e08b765d';

    try {
        const response = await fetch(`${URL_API}hapus_barang.php?token=${tokenSegar}&id=${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const hasil = await response.json();
        if (hasil.status === 'success') {
            alert('Data berhasil dihapus');
            ambilDataBarang();
        } else {
            alert('Gagal: ' + hasil.pesan);
        }
    } catch (error) {
        console.log(error);
    }
}

function editBarang(id, nama, harga) {
    const modal = document.getElementById('modalEdit');
    modal.classList.remove('hidden');
    modal.classList.add('flex', 'items-center', 'justify-center');

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_harga').value = harga;
}

function tutupModal() {
    const modal = document.getElementById('modalEdit');
    modal.classList.add('hidden');
    modal.classList.remove('flex', 'items-center', 'justify-center');
}

async function updateBarang() {
    const id = document.getElementById('edit_id').value;
    const nama = encodeURIComponent(document.getElementById('edit_nama').value);
    const harga = document.getElementById('edit_harga').value;
    const tokenSegar = localStorage.getItem('token_toko') || '9397803a71d9ac82e33827b0e08b765d';

    try {
        const response = await fetch(`${URL_API}edit_barang.php?token=${tokenSegar}&id=${id}&nama_barang=${nama}&harga=${harga}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const hasil = await response.json();
        if (hasil.status === 'success') {
            alert('Data berhasil diupdate');
            tutupModal();
            ambilDataBarang();
        } else {
            alert('Gagal: ' + hasil.pesan);
        }
    } catch (error) {
        console.log(error);
    }
}

function logout() {
    const yakin = confirm('Apakah Anda yakin ingin keluar dari sistem?');
    if (!yakin) return;

    localStorage.removeItem('token_toko');
    alert('Anda telah keluar.');
    window.location.href = 'login.html';
}