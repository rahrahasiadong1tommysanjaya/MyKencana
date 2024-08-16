'use strict';

// Datatable (jquery)
$(function () {
  var dt_barang_table = $('.listBarang'),
    selectSatuanEdit = $('#sat_id-edit'),
    selectJenisBarangEdit = $('#jb_id-edit');

  // edit user
  $(document).on('click', '.edit-barang', function () {
    var id = $(this).data('id'),
      name = $(this).data('name'),
      editModal = $('#edit');

    selectSatuanEdit.select2({
      allowClear: true,
      placeholder: 'pilih satuan...',
      dropdownParent: '#edit',
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

    selectJenisBarangEdit.select2({
      allowClear: true,
      placeholder: 'pilih jenis barang...',
      dropdownParent: '#edit',
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

    // hide responsive modal in small screen
    if (editModal.length) {
      editModal.modal('hide');
    }

    editModal.modal('show');

    $('#nama-barang').text(name);

    // get data
    // Use jQuery AJAX to send form data to the server
    $.ajax({
      type: 'GET',
      url: route('master-barang-edit', id),
      success: function (data) {
        $('#id-edit').val(data.id);
        $('#acc-edit').val(data.acc);
        $('#ket-edit').val(data.ket);
        $('#hst-edit').val(
          new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(data.hst)
        );
        $('#sat_id-edit').select2('trigger', 'select', {
          data: {
            id: data.sat_id,
            text: data.sat
          }
        });
        $('#jb_id-edit').select2('trigger', 'select', {
          data: {
            id: data.jb_id,
            text: data.jb
          }
        });
      },
      error: function (error) {
        console.error('Error:', error);
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
            url: route('master-barang-update'),
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

                // Reload datatables users
                dt_barang_table.DataTable().ajax.reload();

                // Close the modal
                $('#edit').modal('hide');

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
