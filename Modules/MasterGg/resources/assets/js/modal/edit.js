'use strict';

// Datatable (jquery)
$(function () {
  var dt_barang_table = $('.listBarang'),


  // edit user
  $(document).on('click', '.edit-masterGG', function () {
    var id = $(this).data('id'),
      name = $(this).data('name'),
      editModal = $('#edit');


    // hide responsive modal in small screen
    if (editModal.length) {
      editModal.modal('hide');
    }

    editModal.modal('show');

    $('#nama-masterGg').text(name);

    // get data
    // Use jQuery AJAX to send form data to the server

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
