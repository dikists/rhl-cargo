const infoTAdmin = $(".tAdmin").data("admindata");

if (infoTAdmin == "berhasil") {
	Swal.fire({
		position: "top-end",
		icon: "success",
		title: "Data yang anda masukan telah disimpan",
		showConfirmButton: false,
		timer: 2000,
	});
} else if (infoTAdmin == "gagal") {
	Swal.fire({
		icon: "error",
		title: "Oops...",
		text: "Password Yang Anda Masukan Salah!",
	});
}
