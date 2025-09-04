const flashData = $(".flash-data").data("flashdata");

if (flashData == "diubah") {
	Swal.fire({
		position: "top-end",
		icon: "success",
		title: "Data yang anda masukan telah diubah",
		showConfirmButton: false,
		timer: 2000,
	});
}
if (flashData == "disimpan") {
	Swal.fire({
		position: "top-end",
		icon: "success",
		title: "Data yang anda masukan telah disimpan",
		showConfirmButton: false,
		timer: 2000,
	});
}
if (flashData == "gagal simpan data admin") {
	Swal.fire({
		icon: "error",
		title: "Oops...",
		text: "Password Yang Anda Masukan Salah!",
	});
}

if (flashData == "dihapus") {
	Swal.fire({
		position: "top-end",
		icon: "success",
		title: "Data berhasil dihapus !",
		showConfirmButton: false,
		timer: 2000,
	});
}

// hapus admin

$(".hapus").on("click", function (e) {
	e.preventDefault();

	const href = $(this).attr("href");
	Swal.fire({
		title: "Apakan anda yakin?",
		text: "data akan dihapus",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Hapus Data !",
	}).then((result) => {
		if (result.value == true) {
			document.location.href = href;
		}
	});
});

$("#tutup").on("click", function () {
	$("#cariDataMaterial").modal("hide");
});

$(".ambilDataMaterial").on("click", function () {
	const nama = $(this).attr("data-namaMaterial");
	const kode = $(this).attr("data-kodeMaterial");

	document.getElementById("nama_material").value = nama;
	document.getElementById("kode_material").value = kode;

	$("#cariDataMaterial").modal("hide");
});


$(".ambilDataUjiMaterial").on("click", function(){
	const nama = $(this).attr("data-namaMaterial");
	const kode = $(this).attr("data-kodeMaterial");

	document.getElementById("ujiMaterial").value = nama;
	document.getElementById("kodeUjiMaterial").value = kode;

	console.log(nama);
	$("#modalMaterial").modal("hide");

});

$(".ambilDataUjiMedia").on("click", function(){
	const namaMedia = $(this).attr("data-namaMedia");
	const kodeMedia = $(this).attr("data-kodeMedia");

	document.getElementById("ujiMedia").value = namaMedia;
	document.getElementById("kodeUjimedia").value = kodeMedia;

	console.log(namaMedia);
	$("#modalMedia").modal("hide");

});
