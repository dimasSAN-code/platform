async function ambilDataBarang() {
    try {
        const response = await fetch('/platform/api-toko/get_barang.php');
        const hasil = await response.json();

        let html = '';

        hasil.data.forEach(barang => {
            html += `
            <tr class="border-b">
                <td class="p-2">${barang.id}</td>
                <td class="p-2">${barang.nama_barang}</td>
                <td class="p-2 text-right">Rp ${parseInt(barang.harga).toLocaleString()}</td>
            </tr>
            `;
        });

        document.getElementById('tabel-barang').innerHTML = html;

    } catch (error) {
        console.log('Gagal ambil data:', error);
    }
}

ambilDataBarang();


document.getElementById('formBarang').addEventListener('submit', async function(e){
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

            document.getElementById('formBarang').reset();

            ambilDataBarang();
        } else {
            alert('Gagal: ' + hasil.pesan);
        }

    } catch (error) {
        console.log(error);
        alert('Error koneksi');
    }
});