('use strict');

var dt_detail_Gg_table = $('.listDetailMasterGg'),
  gg_id;

// Datatable (jquery)
$(function () {
  $(document).on('click', '.show-masterSubGG', function () {
    // Ambil data dari tombol yang diklik
    gg_id = $(this).data('id');
    var name = $(this).data('name');

    // Masukkan data ke dalam elemen modal (misalnya untuk header modal)
    $('#gg-txt').text(name);

    // Load data ke dalam tabel atau elemen lain dalam modal jika diperlukan, menggunakan id

    // Tampilkan modal
    $('#listDetail').modal('show');

    if (dt_detail_Gg_table.length) {
      // Check if DataTable is already initialized
      if ($.fn.DataTable.isDataTable(dt_detail_Gg_table)) {
        dt_detail_Gg_table.DataTable().destroy(); // Destroy the existing DataTable instance
      }

      var dt_detail_Gg = dt_detail_Gg_table.DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: route('master-gg-detail-show'),
          data: {
            gg_id: gg_id
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
          },
          {
            targets: 3,
            title: 'Actions',
            searchable: false,
            orderable: false,
            render: function (data, type, full, meta) {
              return `<div class="d-inline-block text-nowrap">
                  <button class="btn btn-md btn-icon update-detail" data-id="${full['id']}"><i class="ti ti-device-floppy"></i></button>
                  <button class="btn btn-sm btn-icon delete-detail" data-id="${full['id']}" data-name="${full['ket']}"><i class="ti ti-trash"></i></button>
              </div>`;
            }
          }
        ],
        //   order: [[0, 'desc']],
        language: {
          sLengthMenu: '_MENU_',
          search: '',
          searchPlaceholder: 'Search..'
        },
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
                title: 'Master Detail Gg ' + name,
                text: '<i class="ti ti-printer me-1" ></i>Print',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [0, 1, 2],
                  format: {
                    body: function (data, row, column, node) {
                      return $(node).find('input').val() || data;
                    }
                  }
                }
              },
              {
                extend: 'csv',
                title: 'Master Detail Gg ' + name,
                text: '<i class="ti ti-file-text me-1" ></i>Csv',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [0, 1, 2],
                  format: {
                    body: function (data, row, column, node) {
                      return $(node).find('input').val() || data;
                    }
                  }
                }
              },
              {
                extend: 'excel',
                title: 'Master Detail Gg ' + name,
                text: '<i class="ti ti-file-description me-1"></i>Excel',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [0, 1, 2],
                  format: {
                    body: function (data, row, column, node) {
                      return $(node).find('input').val() || data;
                    }
                  }
                }
              },
              {
                extend: 'pdf',
                title: 'Master Detail Gg ' + name,
                text: '<i class="ti ti-file-description me-1"></i>Pdf',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [0, 1, 2],
                  format: {
                    body: function (data, row, column, node) {
                      return $(node).find('input').val() || data;
                    }
                  }
                }
              },
              {
                extend: 'copy',
                title: 'Master Detail Gg ' + name,
                text: '<i class="ti ti-copy me-1" ></i>Copy',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [0, 1, 2],
                  format: {
                    body: function (data, row, column, node) {
                      return $(node).find('input').val() || data;
                    }
                  }
                }
              }
            ],
            title: ' '
          }
        ]
      });
    }
  });

  $(document).on('click', '.btn-tambah-detail', function () {
    let acc = $('#acc-detail').val();
    let ket = $('#ket-detail').val();

    $.ajax({
      type: 'POST',
      url: route('master-Gg-detail-store'),
      data: {
        gg_id: gg_id,
        acc: acc,
        ket: ket
      },
      success: function (data) {
        if (data.sts == 0) {
          $('#acc-detail').val('');
          $('#ket-detail').val('');
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

        dt_detail_Gg_table.DataTable().ajax.reload();
      }
    });
  });

  //update data detail
  $(document).on('click', '.update-detail', function () {
    let button = $(this);
    let id = button.data('id');
    let acc = $('input[data-id="' + id + '"][data-field="acc"]').val();
    let ket = $('input[data-id="' + id + '"][data-field="ket"]').val();

    $.ajax({
      type: 'POST',
      url: route('master-Gg-detail-update'),
      data: {
        id: id,
        acc: acc,
        ket: ket
      },
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

        dt_detail_Gg_table.DataTable().ajax.reload(null, false);
      },
      error: function (error) {
        console.error('Error:', error);
      }
    });
  });

  // Delete detail Gg
  $(document).on('click', '.delete-detail', function () {
    var id = $(this).data('id'),
      name = $(this).data('name');

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Apakah Anda yakin?',
      html: 'anda akan menghapus Gg <b>' + name + ' </b> !',
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
          url: route('master-Gg-detail-destroy', id),
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
            dt_detail_Gg_table.DataTable().ajax.reload();
          },
          error: function (error) {
            console.log(error);
          }
        });
      }
    });
  });
});
