/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_users_table = $('.listUsers');

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Users datatable
  if (dt_users_table.length) {
    var dt_users = dt_users_table.DataTable({
      responsive: true,
      ajax: {
        url: route('master-users-show')
      },
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'username' },
        { data: 'name' }
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
          searchable: false,
          orderable: false,
          targets: 1,
          render: function (data, type, full, meta) {
            return full['username'];
          }
        },
        {
          // User full name
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return full['name'];
          }
        },
        {
          // Actions
          targets: 3,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return `<div class="d-inline-block text-nowrap">
                        <button class="btn btn-sm btn-icon edit-user" data-id="${full['id']}" data-name="${
                          full['name']
                        }"data-bs-toggle="modal"><i class="ti ti-edit"></i></button>
                        <a href="${route(
                          'master-users-menu',
                          full['id']
                        )}" class="btn btn-sm btn-icon btn-view"><i class="ti ti-list"></i></a>
                        <button class="btn btn-sm btn-icon delete-user" data-id="${full['id']}" data-name="${
                          full['name']
                        }"><i class="ti ti-trash"></i></button>
                        </div>
                    </div>`;
          }
        }
      ],
      order: [[2, 'desc']],
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
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-primary dropdown-toggle mx-3',
          text: '<i class="ti ti-logout rotate-n90 me-2"></i>Export',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-1" ></i>Print',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2] }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2] }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-description me-1"></i>Excel',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2] }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-description me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2] }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-1" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2] }
            }
          ],
          title: ' '
        },
        {
          text: '<i class="ti ti-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah User</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addUser'
          }
        }
      ]
    });
  }

  // Delete User
  $(document).on('click', '.delete-user', function () {
    var user_id = $(this).data('id'),
      name = $(this).data('name');

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Apakah Anda yakin?',
      html: 'anda akan menghapus user <b>' + name + ' </b> !',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: route('user-destroy', user_id),
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

            //reload datatables users
            dt_users_table.DataTable().ajax.reload();
          },
          error: function (error) {
            console.log(error);
          }
        });
      }
    });
  });
});
