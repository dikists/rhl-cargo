$(document).ready(function() {
const input = $(".inputinfo").data("inputdata");
if(input == "berhasil"){
	Swal.fire({
		position: "top-end",
		icon: "success",
		title: "Data yang anda masukan telah disimpan",
		showConfirmButton: false,
		timer: 2000,
	});
}else if (input == "gagal"){
	Swal.fire({
		position: "top-end",
		icon: "error",
		title: "Data yang anda masukan gagal disimpan",
		showConfirmButton: false,
		timer: 2000,
	});	
}


const login = $(".infologin").data("logindata");
if (login == "berhasil") {
	let timerInterval;
	Swal.fire({
		title: "Success!",
		icon: "success",
		html: "Login <br> Halaman Akan Siap Dalam <b></b> ms",
		timer: 100,
		timerProgressBar: true,
		onBeforeOpen: () => {
			Swal.showLoading();
			timerInterval = setInterval(() => {
				const content = Swal.getContent();
				if (content) {
					const b = content.querySelector("b");
					if (b) {
						b.textContent = Swal.getTimerLeft();
					}
				}
			}, 100);
		},
		onClose: () => {
			clearInterval(timerInterval);
		},
	}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			// console.log('I was closed by the timer')
			document.location.href = "home";
		}
	});
} else if (login == "gagal") {
	Swal.fire({
		icon: "error",
		title: "Oops...",
		text: "Username Tidak Terdaftar!",
	});
}else if(login == "password"){
	Swal.fire({
		icon: "error",
		title: "Oops...",
		text: "Password Salah!",
	});	
}



const infologin = $(".login").data("logindata");

if (infologin == "berhasil") {
	let timerInterval;
	Swal.fire({
		title: "Success!",
		icon: "success",
		html: "Login <br> Halaman Akan Siap Dalam <b></b> ms",
		timer: 100,
		timerProgressBar: true,
		onBeforeOpen: () => {
			Swal.showLoading();
			timerInterval = setInterval(() => {
				const content = Swal.getContent();
				if (content) {
					const b = content.querySelector("b");
					if (b) {
						b.textContent = Swal.getTimerLeft();
					}
				}
			}, 100);
		},
		onClose: () => {
			clearInterval(timerInterval);
		},
	}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			// console.log('I was closed by the timer')
			document.location.href = "home";
		}
	});
} else if (infologin == "gagal") {
	Swal.fire({
		icon: "error",
		title: "Oops...",
		text: "Password Yang Anda Masukan Salah!",
	});
}
});
