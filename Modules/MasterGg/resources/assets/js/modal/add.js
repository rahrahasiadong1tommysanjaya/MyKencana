'use strict';

// Datatable (jquery)
$(function () {
  var dt_barang_table = $('.listBarang'),
    selectSatuan = $('#sat_id'),
    selectJenisBarang = $('#jb_id');

  $('#add').on('shown.bs.modal', function (e) {
    $('.formAdd')[0].reset();

    selectSatuan.select2({
      allowClear: true,
      placeholder: 'pilih satuan...',
      dropdownParent: '#add',
      ajax: {
        url: route('master-barang-list-satuan'),
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

    selectJenisBarang.select2({
      allowClear: true,
      placeholder: 'pilih jenis barang...',
      dropdownParent: '#add',
      ajax: {
        url: route('master-barang-list-jenis-barang'),
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
  });

  // Validate Form Input User
  const formAdd = document.querySelectorAll('.formAdd');

  // Loop over them and prevent submission
  Array.prototype.slice.call(formAdd).forEach(function (form) {
    form.addEventListener(
      'submit',
      function (event) {
        event.preventDefault();
        if (!form.checkValidity()) {
          event.stopPropagation();
        } else {
          const formData = $(form).serialize();
          // Use jQuery AJAX to send form data to the server
          $.ajax({
            type: 'POST',
            url: route('master-barang-store'),
            data: formData,
            success: function (response) {
              Swal.fire({
                title: 'Success',
                text: response.msg,
                type: 'success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
              });
              // Close the modal
              $('#add').modal('hide');
              //reset form
              form.reset();
              // Reload datatables users
              dt_barang_table.DataTable().ajax.reload();
              form.classList.add('was-validated');
            },
            error: function (error) {
              console.error('Error:', error);
            }
          });
        }

        form.classList.add('was-validated');
      },
      false
    );
  });
});
