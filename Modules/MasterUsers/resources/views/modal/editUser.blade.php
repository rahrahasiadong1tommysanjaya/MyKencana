<!-- Modal -->
<div class="modal fade" id="editUser" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editUser"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUser">Edit User <span id="nama-user"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditUser" class="formEditUser" autocomplete="off" novalidate>
                    <div class="col-md-12 mb-4">
                        <input class="form-control visually-hidden" type="text" id="id-edit" name="id"
                            required>
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" type="text" id="username-edit" name="username" required>
                        <div class="invalid-feedback"> Username harus diisi... </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="name" class="form-label">Nama</label>
                        <input class="form-control" type="text" id="name-edit" name="name" required>
                        <div class="invalid-feedback"> Nama harus diisi... </div>
                    </div>
                    <hr class="m-2  border-3 mx-0" />
                    <p class="fst-normal text-danger">* kosongkan password jika tidak ingin mengubah password...</p>
                    <div class="form-password-toggle col-md-12 mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password-edit" name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password" />
                            <span id="password" class="input-group-text cursor-pointer"><i
                                    class="ti ti-eye-off"></i></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
