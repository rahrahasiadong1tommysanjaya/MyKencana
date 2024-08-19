<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Barang <span id="nama-barang"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" class="formEdit" autocomplete="off" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <input class="form-control visually-hidden" type="text" id="id-edit" name="id"
                                required>
                            <label for="kode" class="form-label">Kode</label>
                            <input class="form-control" type="text" id="acc-edit" name="acc"
                                placeholder="masukkan kode" required>
                            <div class="invalid-feedback"> kode harus diisi... </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="ket-edit" name="ket"
                                placeholder="masukkan nama" required>
                            <div class="invalid-feedback"> nama harus diisi... </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label for="hst" class="form-label">Harga</label>
                            <input class="currency form-control" type="text" id="hst-edit" name="hst"
                                placeholder="masukkan harga" required>
                            <div class="invalid-feedback"> harga harus diisi... </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="sat_id" class="form-label">Satuan</label>
                            <select class="form-select form-select-md" data-allow-clear="true" id="sat_id-edit"
                                name="sat_id" required></select>
                            <div class="invalid-feedback"> satuan harus diisi... </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="jb_id" class="form-label">Jenis Barang</label>
                            <select class="form-select form-select-md" data-allow-clear="true" id="jb_id-edit"
                                name="jb_id" required></select>
                            <div class="invalid-feedback"> jenis barang harus diisi... </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
