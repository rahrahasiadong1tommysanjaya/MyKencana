'use strict';

// Datatable (jquery)
$(function () {
  var selectCustomer = $('#ar_id');

  $('.tgl,.tjt').datepicker({
    format: 'dd-mm-yyyy',
    viewMode: 'days',
    minViewMode: 'days',
    autoclose: true
  });

  selectCustomer.select2({
    allowClear: true,
    placeholder: 'pilih customer...',
    dropdownParent: '#formEdit',
    ajax: {
      url: route('order-list-customers'),
      dataType: 'json',
      delay: 250,
      data: function (params) {
        if (params.term == undefined) {
          return {
            q: ''
          };
        } else {
          return {
            q: params.term
          };
        }
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: true
    }
  });

  const button = document.querySelector('.btn-update');

  // Tambahkan event listener klik pada tombol
  button.addEventListener('click', function () {
    if (!formEdit.checkValidity()) {
      formEdit.classList.add('was-validated');
    } else {
      const formData = new FormData(formEdit);

      // Simpan referensi ke tombol
      const btn = this;

      // Aksi sebelum mengirim
      $(btn).prop('disabled', true);
      $(btn).html('<span class="spinner-border me-1" role="status" aria-hidden="true"></span> Loading...');

      $.ajax({
        type: 'POST',
        url: route('order-update'),
        data: formData,
        processData: false, // Pastikan untuk menonaktifkan pengolahan data
        contentType: false, // Pastikan untuk menonaktifkan konten tipe
        success: function (data) {
          if (data.sts == 0) {
            Swal.fire({
              title: 'Success',
              text: data.msg,
              type: 'success',
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
            });
          } else {
            Swal.fire({
              title: 'Error',
              text: data.msg,
              type: 'error',
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
          }

          //reload halaman
          location.reload();

          // Reload datatables users
          form.classList.add('was-validated');
        },
        error: function (error) {
          console.error('Error:', error);
        }
      });
    }

    formEdit.classList.add('was-validated');
  });

  //format rupiah semua inputan yang memiliki class currency
  document.querySelectorAll('.currency').forEach(function (input) {
    input.addEventListener('focus', function () {
      // Pilih semua teks dalam input saat fokus
      this.select();
    });
    formatCurrencyInput(input);
  });
});
