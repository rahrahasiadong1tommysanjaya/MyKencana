/**
 * Page User List
 */

'use strict';

function getUserIdFromUrl() {
  // Get the current URL
  var url = window.location.href;

  // Split the URL by '/'
  var segments = url.split('/');

  // The user ID is in the last segment
  var userId = segments[segments.length - 1];

  return userId;
}

var user_id = getUserIdFromUrl();

// Datatable (jquery)
$(function () {
  var theme = $('html').hasClass('light-style') ? 'default' : 'default-dark',
    dragDrop = $('#jstree-menu'),
    selectMenu = $('.selectMenu'),
    selectPermission = $('.selectPermission'),
    modalPermissionUser = $('#editPermissionMenu');

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //Get Data All Menu User
  loadMenuData();
  //Get Data avaiable Menu User
  fetchMenuData();

  // Drag Drop
  // --------------------------------------------------------------------
  function loadMenuData() {
    dragDrop.jstree('destroy').empty();
    $.ajax({
      url: route('user-menu', [user_id]),
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        // Recursively set opened property for each node and its sub-children
        function setOpenedProperty(node) {
          node.state = node.state || {};
          node.state.opened = true;

          if (node.children) {
            node.children.forEach(setOpenedProperty);
          }
        }

        // Apply the setOpenedProperty function to each node in the data
        data.forEach(setOpenedProperty);
        dragDrop
          .jstree({
            core: {
              themes: {
                name: theme
              },
              check_callback: true,
              data: data,
              state: {
                opened: true
              }
            },
            plugins: ['types', 'dnd', 'contextmenu'],
            types: {
              default: {
                icon: 'ti ti-folder'
              }
              // Add other types as needed
            },
            contextmenu: {
              items: function (node) {
                var menu = {
                  permissionItem: {
                    label: 'Permission',
                    action: function () {
                      permissionModalMenu(node);
                    }
                  },
                  deleteItem: {
                    label: 'Delete',
                    action: function () {
                      // Handle delete action here
                      deleteMenu(node);
                    }
                  }
                };

                return menu;
              }
            }
          })
          .on('move_node.jstree', function (e, data) {
            // Get the new order
            var serializedData = dragDrop.jstree(true).get_json('#', { flat: true });

            // Update the database with the new order
            updateSortOrderInDatabase(user_id, serializedData);
          });
      },
      error: function (error) {
        console.log(error);
      }
    });
  }

  function updateSortOrderInDatabase(user_id, serializedData) {
    // Send an AJAX request to update the database

    $.ajax({
      url: route('user-menu-sort', user_id),
      type: 'POST',
      data: {
        user_id: user_id,
        serialized_data: JSON.stringify(serializedData)
      },
      dataType: 'json',
      success: function (response) {
        console.log('Sort order updated successfully:', response);
      },
      error: function (jqXHR) {
        loadMenuData();
        Swal.fire({
          title: 'Error',
          text: jqXHR.responseJSON.message,
          type: 'error',
          icon: 'error',
          showConfirmButton: false
        });
      }
    });
  }

  //Handle Edit Permission
  function permissionModalMenu(node) {
    let initialSelection;

    // Fetch default data for the Select2 dropdown
    $.ajax({
      url: route('user-menu-listpermissionUser', [node.original.menu_id, user_id]),
      dataType: 'json',
      success: function (data) {
        // Map the default data to the format expected by Select2
        initialSelection = data.map(function (item) {
          return {
            id: item[0],
            text: item[1]
          };
        });

        // Clear existing options
        $('#permissionMenuUser').empty();

        // Initialize Select2 with the default data
        $('#permissionMenuUser').select2({
          data: initialSelection, // The default data from the database
          placeholder: 'Pilih Permission...',
          dropdownParent: $('#editPermissionMenu'),
          multiple: true,
          initSelection: function (element, callback) {
            // Provide the initial selection to Select2
            callback(initialSelection);
          },
          ajax: {
            url: route('user-menu-listpermission', node.original.menu_id),
            dataType: 'json',
            data: function (params) {
              return {
                search: params.term
              };
            },
            processResults: function (data) {
              return {
                results: data
              };
            },
            cache: false
          }
        });

        // Set the value of the Select2 input to trigger the visual update
        $('#permissionMenuUser')
          .val(initialSelection.map(item => item.id))
          .trigger('change');
      }
    });

    // Open the modal
    modalPermissionUser.modal('show');

    // Display node information in the modal
    $('#namaMenu').text(node.text);
    $('#menuId').val(node.original.menu_id);
  }

  // Validate Form Input Permission Menu User
  const formPermissionMenuUser = document.querySelectorAll('.formPermissionMenuUser');

  // Loop over them and prevent submission
  Array.prototype.slice.call(formPermissionMenuUser).forEach(function (form) {
    form.addEventListener(
      'submit',
      function (event) {
        event.preventDefault();
        const selectInput = $('#permissionMenuUser');

        // Check if at least one permission is selected
        if (selectInput.val() === null || selectInput.val().length === 0) {
          // If no permission is selected, mark the select input as invalid
          selectInput.addClass('is-invalid');
        } else {
          // If at least one permission is selected, remove the invalid class
          selectInput.removeClass('is-invalid');

          // Proceed with form submission
          if (!form.checkValidity()) {
            event.stopPropagation();
          } else {
            const formData = $(form).serialize();

            // Use jQuery AJAX to send form data to the server
            $.ajax({
              type: 'POST',
              url: route('user-menu-permission-update', user_id),
              data: formData,
              success: function (data) {
                if (data.status) {
                  // Hide the modal
                  modalPermissionUser.modal('hide');
                  form.classList.add('was-validated');

                  Swal.fire({
                    title: 'Success',
                    text: data.message,
                    type: 'success',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                  });
                } else {
                  Swal.fire({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1500
                  });
                }
              },
              error: function (jqXHR) {
                Swal.fire({
                  title: 'Error',
                  text: jqXHR.responseJSON.message,
                  type: 'error',
                  icon: 'error',
                  showConfirmButton: false
                });
              }
            });
          }
        }

        form.classList.add('was-validated');
      },
      false
    );
  });

  //Handle delete menu
  function deleteMenu(node) {
    Swal.fire({
      title: 'Apakah anda yakin?',
      text: 'Anda akan menghapus menu ' + node.text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      customClass: {
        confirmButton: 'btn btn-primary me-1',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          type: 'DELETE',
          url: route('user-menu-destroy', [node.original.menu_id, user_id]),
          success: function () {
            // Reload JSTree menu
            loadMenuData();

            //Reload Dropdown data menu
            fetchMenuData();
          },
          error: function (jqXHR) {
            Swal.fire({
              title: 'Error',
              text: jqXHR.responseJSON.message,
              type: 'error',
              icon: 'error',
              showConfirmButton: false
            });
          }
        });
      }
    });
  }

  // Fetch menu data from the server using AJAX
  function fetchMenuData() {
    $.ajax({
      url: route('user-menu-listmenu', user_id),
      type: 'GET',
      dataType: 'json',
      success: function (menuData) {
        if (selectMenu.length) {
          selectMenu.each(function () {
            var $this = $(this);

            // Clear existing options
            $this.empty();

            // Add new options from the fetched data
            menuData.forEach(function (menuItem) {
              $this.append('<option value=""></option>');
              $this.append('<option value="' + menuItem.id + '">' + menuItem.text + '</option>');
            });

            $this.select2({
              placeholder: 'Pilih Menu...',
              dropdownParent: $this.parent()
            });

            // Attach a change event to the dropdown
            $this.on('change', function () {
              // Get the selected menu_id
              var selectedMenuId = $(this).val();

              // Fetch permission data based on the selected menu_id
              fetchPermissionData(selectedMenuId);
            });
          });
        }
      },
      error: function (error) {
        console.error('Error fetching menu data:', error);
      }
    });
  }

  // Get data permission
  // Function to fetch permission data based on the selected menu_id
  function fetchPermissionData(menuId) {
    // Fetch permission data from the server using AJAX
    $.ajax({
      url: route('user-menu-listpermission', menuId),
      type: 'GET',
      dataType: 'json',
      success: function (permissionData) {
        // Your existing Select2 initialization logic for the second dropdown
        if (selectPermission.length) {
          selectPermission.each(function () {
            var $this = $(this);

            // Clear existing options
            $this.empty();

            // Add new options from the fetched data
            permissionData.forEach(function (permissionItem) {
              $this.append('<option value="' + permissionItem.id + '">' + permissionItem.text + '</option>');
            });

            $this.select2({
              placeholder: 'Pilih Permission...',
              dropdownParent: $this.parent(),
              multiple: true
            });
          });
        }
      },
      error: function (error) {
        console.error('Error fetching permission data:', error);
      }
    });
  }

  // Validate Form Input Menu User
  const formInputMenuUser = document.querySelectorAll('.formInputMenuUser');

  // Loop over them and prevent submission
  Array.prototype.slice.call(formInputMenuUser).forEach(function (form) {
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
            url: route('user-menu-store'),
            data: formData,
            success: function (data) {
              // Reload JSTree menu
              loadMenuData();

              //Reload Dropdown data menu
              fetchMenuData();

              //Reload Dropdown data permission
              fetchPermissionData(0);

              form.classList.add('was-validated');
            },
            error: function (jqXHR) {
              Swal.fire({
                title: 'Error',
                text: jqXHR.responseJSON.permission,
                type: 'error',
                icon: 'error',
                showConfirmButton: false
              });
            }
          });
        }

        form.classList.add('was-validated');
      },
      false
    );
  });
});
