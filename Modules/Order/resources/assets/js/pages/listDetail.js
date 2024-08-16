/**
 * Page User List
 */

('use strict');

// Datatable (jquery)
$(function () {
  var dt_detail_order_table = $('.listDetail');
  var dt_detail_order;

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Users datatable
  if (dt_detail_order_table.length) {
    var dt_detail_order = dt_detail_order_table.DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: route('order-detail-show'), // Ki,rim tanggal default saat inisialisasi
        data: {
          id: $('#id').val()
        }
      },
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'niv' },
        { data: 'tgk' },
        { data: 'qti' },
        { data: 'hst' },
        { data: 'jrp' }
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
          width: '250px',
          targets: 1,
          render: function (data, type, full, meta) {
            return full['niv'];
          }
        },
        {
          width: '150px',
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            // Format the date using Moment.js
            const formattedDate = full['tgk'] === '0000-00-00' ? '' : moment(full['tgk']).format('DD-MM-YYYY');

            return `
                <input 
                    type="text" 
                    class="form-control datepicker editable-input" 
                    value="${formattedDate}" 
                    data-id="${full['id']}" 
                    data-field="tgk" 
                    onfocus="this.select()"
                >
            `;
          }
        },
        {
          width: '150px',
          targets: 3,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return `<input type="number" class="form-control editable-input" value="${full['qti']}" data-id="${full['id']}" data-field="qti" onfocus="this.select()">`;
          }
        },
        {
          width: '200px',
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
            return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(
              full['jrp']
            );
          }
        },
        {
          targets: 6,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return `<div class="d-inline-block text-nowrap">
            <button class="btn btn-md btn-icon update-detail" data-id="${full['id']}" data-name="${full['niv']}"><i class="ti ti-device-floppy"></i></button>
            <button class="btn btn-md btn-icon delete-detail" data-id="${full['id']}" data-name="${full['niv']}"><i class="ti ti-trash"></i></button>
        </div>`;
          }
        }
      ],
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
      drawCallback: function () {
        $('.datepicker').datepicker({
          format: 'dd-mm-yyyy',
          viewMode: 'days',
          minViewMode: 'days',
          autoclose: true,
          todayHighlight: true // Highlight today's date
        });
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
              title: 'Detail Order ' + $('#buk').val(),
              text: '<i class="ti ti-printer me-1" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                format: {
                  body: function (data, row, column, node) {
                    return $(node).find('input').val() || data;
                  }
                }
              }
            },
            {
              extend: 'csv',
              title: 'Detail Order ' + $('#buk').val(),
              text: '<i class="ti ti-file-text me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                format: {
                  body: function (data, row, column, node) {
                    return $(node).find('input').val() || data;
                  }
                }
              }
            },
            {
              extend: 'excel',
              title: 'Detail Order ' + $('#buk').val(),
              text: '<i class="ti ti-file-description me-1"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                format: {
                  body: function (data, row, column, node) {
                    return $(node).find('input').val() || data;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              title: 'Detail Order ' + $('#buk').val(),
              text: '<i class="ti ti-file-description me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                format: {
                  body: function (data, row, column, node) {
                    return $(node).find('input').val() || data;
                  }
                }
              }
            },
            {
              extend: 'copy',
              title: 'Detail Order ' + $('#buk').val(),
              text: '<i class="ti ti-copy me-1" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                format: {
                  body: function (data, row, column, node) {
                    return $(node).find('input').val() || data;
                  }
                }
              }
            }
          ],
          title: ' '
        },
        {
          text: '<i class="ti ti-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addDetail'
          }
        }
      ]
    });
  }

  // Update detail barang
  $(document).on('click', '.update-detail', function () {
    let button = $(this);
    let id = button.data('id');
    let tgk = $('input[data-id="' + id + '"][data-field="tgk"]').val();
    let hst = $('input[data-id="' + id + '"][data-field="hst"]').val();
    let qti = $('input[data-id="' + id + '"][data-field="qti"]').val();

    $.ajax({
      type: 'POST',
      url: route('order-detail-update'),
      data: {
        id: id,
        tgk: tgk,
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

        dt_detail_order_table.DataTable().ajax.reload();
      },
      error: function (error) {
        console.log(error);
      }
    });
  });

  // Delete detail barang
  $(document).on('click', '.delete-detail', function () {
    var id = $(this).data('id'),
      name = $(this).data('name');

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Apakah Anda yakin?',
      html: 'anda akan menghapus barang <b>' + name + ' </b> !',
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
          url: route('order-detail-destroy', id),
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

            dt_detail_order_table.DataTable().ajax.reload();
          },
          error: function (error) {
            console.log(error);
          }
        });
      }
    });
  });
});
