'use strict';

// Datatable (jquery)
$(function () {
  var dt_cusssup_table = $('.listCusSup'),
    selectJenis = $('.selectJenis'),
    selectKelurahan = $('.selectKelurahan');

  $('#add').on('shown.bs.modal', function (e) {
    $('.formAdd')[0].reset();

    selectJenis.select2({
      allowClear: true,
      placeholder: 'pilih jenis...',
      dropdownParent: '#add'
    });

    selectKelurahan.select2({
      allowClear: true,
      placeholder: 'Pilih kelurahan...',
      dropdownParent: '#add',
      ajax: {
        url: route('master-cussup-list-kelurahan'),
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

    selectKelurahan.on('select2:select', function (e) {
      var data = e.params.data;
      $('#kec').val(data.kec);
      $('#kab_kota').val(data.kab_kota);
      $('#prov').val(data.prov);
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
            url: route('master-cussup-store'),
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
              dt_cusssup_table.DataTable().ajax.reload();
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
