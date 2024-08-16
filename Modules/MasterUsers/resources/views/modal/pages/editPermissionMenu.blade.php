<!-- Modal -->
<div class="modal fade" id="editPermissionMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">List Permission Menu <span id="namaMenu"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="formPermissionMenuUser" class="formPermissionMenuUser" novalidate>
                    <div class="col-md-12 mb-4">
                        <input class="form-control visually-hidden" type="text" id="menuId" name="menuId">
                        <label for="permissionMenuUser" class="form-label">Permission</label>
                        <select id="permissionMenuUser" name="permissionMenuUser[]" class="form-select">
                            <option value=""></option>
                        </select>
                        <div class="invalid-feedback"> Permission harus dipilih... </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
