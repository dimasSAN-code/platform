const form = document.getElementById('formBarang');

form.addEventListener('submit', async function(e){
    e.preventDefault();

    const nama = document.getElementById('nama_barang').value;
    const harga = document.getElementById('harga').value;

    try {
        const response = await fetch('/platform/api-toko/tambah_barang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nama_barang: nama,
                harga: harga
            })
        });

        const hasil = await response.json();

        if (hasil.status === 'success') {
            alert('Data berhasil ditambahkan');

            // redirect balik ke index
            window.location.href = 'index.html';
        } else {
            alert('Gagal: ' + hasil.pesan);
        }

    } catch (error) {
        console.log(error);
        alert('Error koneksi');
    }
});