/**
 * File Upload
 */

('use strict');

(function () {
  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Variable declaration for table
  var dt_backup_database_table = $('.backupTable');

  if (dt_backup_database_table.length) {
    var dt_backup_database = dt_backup_database_table.DataTable({
      processing: false,
      serverSide: false,
      responsive: true,
      ajax: {
        url: route('backupdatabase-show')
      },
      columns: [
        // columns according to JSON
        { data: 'name' },
        { data: 'date' },
        { data: 'location' }
      ],
      columnDefs: [
        {
          defaultContent: '-',
          targets: '_all'
        },
        {
          searchable: true,
          orderable: true,
          targets: 0,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return full['name'];
          }
        },
        {
          searchable: true,
          orderable: true,
          targets: 1,
          render: function (data, type, full, meta) {
            return moment(full['date']).format('DD-MM-YYYY HH:mm:s');
          }
        },
        {
          searchable: true,
          orderable: true,
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return full['location'];
          }
        }
      ],
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search..'
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('backupBtn').addEventListener('click', function () {
      Swal.fire({
        title: 'Apakah anda yakin?',
        text: 'Anda akan melakukan backup database',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Proses!',
        customClass: {
          confirmButton: 'btn btn-primary me-1',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(result => {
        if (result.isConfirmed) {
          $('#page-loader-text').text('Proses backup database sedang berlangsung...');

          // Tampilkan page-loader
          $('#page-loader').show();

          $.ajax({
            url: route('backupdatabase-backup'),
            type: 'POST',
            dataType: 'json',
            data: {},
            success: function (response) {
              console.log(response);
              if (response.status == 'success') {
                Swal.fire({
                  title: 'Success',
                  text: response.message,
                  icon: 'success',
                  showConfirmButton: false,
                  timer: 1500
                });
                // Reload tabel menggunakan DataTables Ajax
                $('.backupTable').DataTable().ajax.reload(null, false);
              } else {
                Swal.fire({
                  title: 'Error',
                  text: response.message,
                  icon: 'error',
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
              }

              // Sembunyikan page-loader
              $('#page-loader').hide();
            },
            error: function (error) {
              console.error('Error:', error);
              Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat melakukan backup database.',
                icon: 'error',
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              });

              // Sembunyikan page-loader
              $('#page-loader').hide();
            }
          });
        }
      });
    });
  });
})();
