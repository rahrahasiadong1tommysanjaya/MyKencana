<!-- Modal -->
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="formAddUser" class="formAddUser" autocomplete="off" novalidate>
                    <div class="col-md-12 mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" type="text" id="username" name="username" required>
                        <div class="invalid-feedback"> Username harus diisi... </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="name" class="form-label">Nama</label>
                        <input class="form-control" type="text" id="name" name="name" required>
                        <div class="invalid-feedback"> Nama harus diisi... </div>
                    </div>
                    <div class="form-password-toggle col-md-12 mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password" required />
                            <span id="password" class="input-group-text cursor-pointer"><i
                                    class="ti ti-eye-off"></i></span>
                            <div class="invalid-feedback"> Password harus diisi... </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
