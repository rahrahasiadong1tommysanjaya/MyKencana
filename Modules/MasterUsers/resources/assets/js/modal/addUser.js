'use strict';

// Datatable (jquery)
$(function () {
  var dt_users_table = $('.listUsers');

  $('#addUser').on('shown.bs.modal', function (e) {
    $('.formAddUser')[0].reset();
  });

  // Validate Form Input User
  const formAddUser = document.querySelectorAll('.formAddUser');

  // Loop over them and prevent submission
  Array.prototype.slice.call(formAddUser).forEach(function (form) {
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
            url: route('master-user-store'),
            data: formData,
            success: function (response) {
              Swal.fire({
                title: 'Success',
                text: response.msg,
                type: 'success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
              });
              // Close the modal
              $('#addUser').modal('hide');
              //reset form
              form.reset();
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
