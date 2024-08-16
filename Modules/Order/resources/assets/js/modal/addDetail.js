'use strict';

// Datatable (jquery)
$(function () {
  var dt_inv_table = $('.listBarang'),
    dt_detail_order_table = $('.listDetail');

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#addDetail').on('shown.bs.modal', function (e) {
    if (dt_inv_table.length) {
      // Check if DataTable is already initialized
      if ($.fn.DataTable.isDataTable(dt_inv_table)) {
        dt_inv_table.DataTable().destroy(); // Destroy the existing DataTable instance
      }

      var dt_inv = dt_inv_table.DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: route('order-list-inv')
        },
        columns: [
          // columns according to JSON
          { data: 'id' },
          { data: 'ket' },
          { data: 'hst' },
          { data: 'sat' },
          { data: 'jb' }
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
              return full['ket'];
            }
          },
          {
            targets: 2,
            render: function (data, type, full, meta) {
              return full['sat'];
            }
          },
          {
            targets: 3,
            responsivePriority: 4,
            render: function (data, type, full, meta) {
              return full['jb'];
            }
          },
          {
            targets: 4,
            responsivePriority: 4,
            render: function (data, type, full, meta) {
              return `<input type="number" class="form-control editable-input" value="${full['hst']}" data-id="${full['id']}" data-field="hst" onfocus="this.select()">`;
            }
          },
          {
            targets: 5,
            responsivePriority: 4,
            render: function (data, type, full, meta) {
              return `<input type="number" class="form-control editable-input" data-id="${full['id']}" data-field="qti">`;
            }
          },
          {
            targets: 6,
            title: 'Actions',
            searchable: false,
            orderable: false,
            render: function (data, type, full, meta) {
              return `<div class="d-inline-block text-nowrap">
                <button class="btn btn-md btn-icon save-barang" data-id="${full['id']}" data-name="${full['buk']}"><i class="ti ti-device-floppy"></i></button>
            </div>`;
            }
          }
        ],
        //   order: [[0, 'desc']],
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
                exportOptions: { columns: [0, 1, 2, 3, 4] }
              },
              {
                extend: 'csv',
                text: '<i class="ti ti-file-text me-1" ></i>Csv',
                className: 'dropdown-item',
                exportOptions: { columns: [0, 1, 2, 3, 4] }
              },
              {
                extend: 'excel',
                text: '<i class="ti ti-file-description me-1"></i>Excel',
                className: 'dropdown-item',
                exportOptions: { columns: [0, 1, 2, 3, 4] }
              },
              {
                extend: 'pdf',
                text: '<i class="ti ti-file-description me-1"></i>Pdf',
                className: 'dropdown-item',
                exportOptions: { columns: [0, 1, 2, 3, 4] }
              },
              {
                extend: 'copy',
                text: '<i class="ti ti-copy me-1" ></i>Copy',
                className: 'dropdown-item',
                exportOptions: { columns: [0, 1, 2, 3, 4] }
              }
            ],
            title: ' '
          }
        ]
      });
    }
  });

  $(document).on('click', '.save-barang', function () {
    let button = $(this);
    let id = button.data('id');
    let hst = $('input[data-id="' + id + '"][data-field="hst"]').val();
    let qti = $('input[data-id="' + id + '"][data-field="qti"]').val();
    let ok_id = $('#id').val();

    $.ajax({
      type: 'POST',
      url: route('order-detail-store'),
      data: {
        ok_id: ok_id,
        id: id,
        hst: hst,
        qti: qti
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

        $('#addDetail').modal('hide');

        dt_detail_order_table.DataTable().ajax.reload(null, false);
      },
      error: function (error) {
        console.error('Error:', error);
      }
    });
  });
});
