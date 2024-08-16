'use strict';

// Datatable (jquery)
$(function () {
  var selectCustomer = $('#ar_id');

  $('#add').on('shown.bs.modal', function (e) {
    $('.formAdd')[0].reset();

    $('.tgl,.tjt').datepicker({
      format: 'dd-mm-yyyy',
      viewMode: 'days',
      minViewMode: 'days',
      autoclose: true
    });

    selectCustomer.select2({
      allowClear: true,
      placeholder: 'pilih customer...',
      dropdownParent: '#add',
      ajax: {
        url: route('order-list-customers'),
        dataType: 'json',
        delay: 250,
        data: function (params) {
          if (params.term == undefined) {
            return {
              q: ''
            };
          } else {
            return {
              q: params.term
            };
          }
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
  });

  // Validate Form Input User
  const formAdd = document.querySelectorAll('.formAdd');

  // Loop over them and prevent submission
  Array.prototype.slice.call(formAdd).forEach(function (form) {
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
            url: route('order-store'),
            data: formData,
            success: function (response) {
              // Close the modal
              $('#add').modal('hide');

              Swal.fire({
                title: 'Success',
                text: response.msg,
                type: 'success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
              });

              setInterval(() => {
                window.location.href = route('order-edit', response.id);
              }, 1500);
              //reset form
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
