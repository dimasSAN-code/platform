const form = document.getElementById('formBarang');

if (!localStorage.getItem('token_toko')) {
    window.location.href = 'login.html';
}

if (form) {
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const nama = encodeURIComponent(document.getElementById('nama_barang').value);
        const harga = document.getElementById('harga').value;
        const tokenSegar = localStorage.getItem('token_toko') || '9397803a71d9ac82e33827b0e08b765d';

        try {
            const response = await fetch(`../api-toko/tambah_barang.php?token=${tokenSegar}&nama_barang=${nama}&harga=${harga}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const hasil = await response.json();

            if (hasil.status === 'success') {
                alert('Data berhasil ditambahkan');
                window.location.href = 'index.html';
            } else {
                alert('Gagal: ' + hasil.pesan);
            }

        } catch (error) {
            console.log(error);
            alert('Error koneksi atau Akses Ditolak');
        }
    });
}