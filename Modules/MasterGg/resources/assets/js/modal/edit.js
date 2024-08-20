'use strict';

// Datatable (jquery)
$(function () {
  var dt_masterSubGg_table = $('.list-detail-masterSubGg'),
    selectSatuanEdit = $('#sat_id-edit'),
    selectJenisBarangEdit = $('#jb_id-edit');

  // edit user
  $(document).on('click', '.edit-masterGG', function () {
    var id = $(this).data('id'),
      name = $(this).data('name'),
      editModal = $('#edit');

    var dt_masterSubGg = dt_masterSubGg_table.DataTable({
      responsive: true,
      ajax: {
        url: route('master-gg-show'),
        data: {
          id: id
        }
      },
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'acc' },
        { data: 'ket' }
      ],
      columnDefs: [
        {
          defaultContent: '-',
          targets: '_all'
        },
        {
          searchable: false,
          orderable: false,
          targets: 0,
          render: function (data, type, full, meta) {
            return meta.row + 1;
          }
        },
        {
          searchable: true,
          orderable: false,
          targets: 1,
          render: function (data, type, full, meta) {
            if (type === 'display') {
              return `<input type="text" class="form-control editable-input" value="${full['acc']}" data-id="${full['id']}" data-field="acc">`;
            } else if (type === 'filter') {
              return full['acc']; // Return the raw data for search purposes
            }
            return data;
          }
        },
        {
          searchable: true,
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            if (type === 'display') {
              return `<input type="text" class="form-control editable-input" value="${full['ket']}" data-id="${full['id']}" data-field="ket">`;
            } else if (type === 'filter') {
              return full['ket']; // Return the raw data for search purposes
            }
            return data;
          }
        }
      ],
      //   order: [[1, 'desc']],
      dom:
        '<"row mx-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search..'
      }
      // Buttons with Dropdown
    });

    // hide responsive modal in small screen
    if (editModal.length) {
      editModal.modal('hide');
    }
    editModal.modal('show');

    // get data
    // Use jQuery AJAX to send form data to the server
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
