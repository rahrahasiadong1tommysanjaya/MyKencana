'use strict';

// Datatable (jquery)
$(function () {
  var dt_users_table = $('.listUsers');

  // edit user
  $(document).on('click', '.edit-user', function () {
    var user_id = $(this).data('id'),
      name = $(this).data('name'),
      editModal = $('#editUser');

    $('.formEditUser')[0].reset();

    // hide responsive modal in small screen
    if (editModal.length) {
      editModal.modal('hide');
    }

    editModal.modal('show');

    $('#nama-user').text(name);

    // get data
    // Use jQuery AJAX to send form data to the server
    $.ajax({
      type: 'GET',
      url: route('user-edit', user_id),
      success: function (data) {
        $('#id-edit').val(data.id);
        $('#username-edit').val(data.username);
        $('#name-edit').val(data.name);
      },
      error: function (error) {
        console.error('Error:', error);
      }
    });
  });

  // Update  User
  // Validate Form Input User
  const formEditUser = document.querySelectorAll('.formEditUser');

  // Loop over them and prevent submission
  Array.prototype.slice.call(formEditUser).forEach(function (form) {
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
            url: route('user-update'),
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
                dt_users_table.DataTable().ajax.reload();

                // Close the modal
                $('#editUser').modal('hide');

                //reset form
                formEditUser.resetForm(true);
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
              dt_users_table.DataTable().ajax.reload();
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
