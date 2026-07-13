$(document).ready(function () {
   function formatRupiah(angka) {
      let number_string = angka.replace(/[^,\d]/g, "").toString(),
         split = number_string.split(","),
         sisa = split[0].length % 3,
         rupiah = split[0].substr(0, sisa),
         ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      if (ribuan) {
         let separator = sisa ? "." : "";
         rupiah += separator + ribuan.join(".");
      }

      rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
      return rupiah;
   }

   $(document).on("input", ".number-separator", function (e) {
      let caretPos = this.selectionStart;
      let originalLength = this.value.length;

      this.value = formatRupiah(this.value);

      // Reset caret position
      let newLength = this.value.length;
      this.setSelectionRange(caretPos + (newLength - originalLength), caretPos + (newLength - originalLength));
   });
});