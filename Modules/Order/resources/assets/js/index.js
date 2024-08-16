/**
 * Page User List
 */

('use strict');

// Datatable (jquery)
$(function () {
  // Get the current date
  var currentDate = new Date();
  var month = currentDate.getMonth() + 1;
  var year = currentDate.getFullYear();
  var defaultValue = ('0' + month).slice(-2) + ' ' + year;
  var defaultDate = currentDate.toISOString().slice(0, 10);

  // Variable declaration for table
  var dt_order_table = $('.listOrder');
  var dt_order;

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Users datatable
  if (dt_order_table.length) {
    var dt_order = dt_order_table.DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: route('order-show') // Kirim tanggal default saat inisialisasi
      },
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'buk' },
        { data: 'tgl' },
        { data: 'tjt' },
        { data: 'nar' },
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
          searchable: false,
          orderable: false,
          targets: 1,
          render: function (data, type, full, meta) {
            var $row_output = '<a href="' + route('order-edit', full['id']) + '">' + full['buk'] + '</a>';
            return $row_output;
          }
        },
        {
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return moment(full['tgl']).format('DD-MM-YYYY');
          }
        },
        {
          targets: 3,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return moment(full['tjt']).format('DD-MM-YYYY');
          }
        },
        {
          targets: 4,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return full['nar'];
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
            <a href="${route(
              'order-edit',
              full['id']
            )}" class="btn btn-sm btn-icon btn-view"><i class="ti ti-edit"></i></a>
            <button class="btn btn-md btn-icon delete-order" data-id="${full['id']}" data-name="${full['buk']}"><i class="ti ti-trash"></i></button>
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
              exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-description me-1"></i>Excel',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-description me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-1" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            }
          ],
          title: ' '
        },
        {
          text: '<i class="ti ti-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Tambah</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#add'
          }
        }
      ]
    });
  }

  //Datepicker indonesia format
  $('#monthPicker')
    .datepicker({
      format: 'MM yyyy',
      viewMode: 'months',
      minViewMode: 'months',
      autoclose: true
    })
    .datepicker('setDate', defaultValue)
    .on('changeDate', function () {
      var newDate = $('#monthPicker').datepicker('getDate');
      newDate.setMonth(newDate.getMonth() + 1); // Add 1 month
      var formattedDate = newDate.toISOString().slice(0, 10);
      //   if (dt_order) {
      dt_order.ajax.url(baseUrl + 'order/show?tgl=' + formattedDate).load();
      //   }
    });

  // Trigger DataTables load initially based on the default date
  dt_order.ajax.url(baseUrl + 'order/show?tgl=' + defaultDate).load();

  // Delete order
  $(document).on('click', '.delete-order', function () {
    var id = $(this).data('id'),
      name = $(this).data('name');

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Apakah Anda yakin?',
      html: 'anda akan menghapus order no : <br><b>' + name + ' </b> !',
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
          url: route('order-destroy', id),
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
            dt_order_table.DataTable().ajax.reload();
          },
          error: function (error) {
            console.log(error);
          }
        });
      }
    });
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
