/**
 * File Upload
 */

'use strict';

(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // previewTemplate: Updated Dropzone default previewTemplate
  // ! Don't change it unless you really know what you are doing
  const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;

  // ? Start your code from here

  // Basic Dropzone
  // --------------------------------------------------------------------
  const dropzoneBasic = document.querySelector('#dropzone-basic');
  if (dropzoneBasic) {
    const myDropzone = new Dropzone(dropzoneBasic, {
      previewTemplate: previewTemplate,
      parallelUploads: 1,
      maxFilesize: 5,
      addRemoveLinks: true,
      maxFiles: 1
    });
  }

  document.getElementById('uploadButton').addEventListener('click', function () {
    // Akses Dropzone instance
    const dropzone = Dropzone.forElement('#dropzone-basic');

    // Ambil file yang diunggah
    const file = dropzone.files[0];

    if (!file) {
      Swal.fire({
        title: 'Error',
        text: 'No file selected.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-success'
        }
      });
      return;
    }

    // Buat FormData untuk mengirim file
    const formData = new FormData();
    formData.append('file', file);

    // Kirim file ke server menggunakan AJAX
    $.ajax({
      type: 'POST',
      url: route('restore-database'),
      data: formData,
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      processData: false,
      contentType: false,
      cache: false,
      success: function (response) {
        if (response.status === 'success') {
          Swal.fire({
            title: 'Success',
            text: response.message,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            // Opsional: Redirect atau refresh halaman
            window.location.href = route('login');
          });
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
      },
      error: function (xhr, status, error) {
        Swal.fire({
          title: 'Error',
          text: 'Failed to restore database.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
})();
