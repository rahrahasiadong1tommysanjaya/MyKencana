('use strict');

// Datatable (jquery)
$(function () {
  var dt_cusssup_table = $('.listCusSup'),
    selectJenis = $('.selectJenis'),
    selectKelurahan = $('.selectKelurahan');

  $(document).on('click', '.edit-cussup', function (e) {
    e.preventDefault();

    selectJenis.select2({
      allowClear: true,
      placeholder: 'pilih jenis...',
      dropdownParent: '#edit'
    });

    selectKelurahan.select2({
      allowClear: true,
      placeholder: 'Pilih kelurahan...',
      dropdownParent: '#edit',
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

    // Ambil data berdasarkan id
    let id = $(this).data('id');

    $.ajax({
      url: route('master-cussup-edit', id),
      method: 'GET',
      success: function (response) {
        $('#edit').modal('show');
        $('#id-edit').val(response.id);
        $('#acc-edit').val(response.acc);
        $('#ket-edit').val(response.ket);
        $('#alm-edit').val(response.ket);
        $('#kel-edit').select2('trigger', 'select', {
          data: {
            id: response.kelurahan_id,
            text: response.alm2
          }
        });
        $('#kec-edit').val(response.kec);
        $('#kab_kota-edit').val(response.kab_kota);
        $('#prov-edit').val(response.prov);
      },
      error: function (xhr, status, error) {
        console.error('Error fetching data:', error);
      }
    });
  });

  // Update  Barang
  const formEdit = document.querySelectorAll('.formEdit');

  // Loop over them and prevent submission
  Array.prototype.slice.call(formEdit).forEach(function (form) {
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
            url: route('master-cussup-update'),
            data: formData,
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

                // Close the modal
                $('#edit').modal('hide');

                // Reload datatables users
                dt_cusssup_table.DataTable().ajax.reload();

                //reset form
                formEdit.resetForm(true);
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
