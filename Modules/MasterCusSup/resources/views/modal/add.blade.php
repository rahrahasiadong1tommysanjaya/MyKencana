<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Customer dan Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="formAdd" class="formAdd" autocomplete="off" novalidate>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label for="kode" class="form-label">Kode</label>
                            <input class="form-control" type="text" id="acc" name="acc"
                                placeholder="masukkan kode" required>
                            <div class="invalid-feedback"> kode harus diisi... </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="ket" name="ket"
                                placeholder="masukkan nama" required>
                            <div class="invalid-feedback"> nama harus diisi... </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="name" class="form-label">Jenis</label>
                            <select id="jns" name="jns" class="selectJenis form-select form-select-md"
                                data-allow-clear="true" required>
                                <option value="cust">Customer</option>
                                <option value="supp">Supplier</option>
                            </select>
                            <div class="invalid-feedback"> jenis harus dipilih... </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="alm" class="form-label fs-6">Alamat <span class="text-danger small">*
                                    harap diisi detail alamat lengkap <b>tanpa</b> kelurahan,kecamatan dan
                                    provinsi</span></label>
                            <textarea name="alm" class="form-control" placeholder="Masukkan alamat" autocomplete="off" required></textarea>
                            <div class="invalid-feedback"> alamat harus diisi... </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <label for="kel" class="form-label fs-6">Kelurahan</label>
                            <select id="kel" name="kel" class="selectKelurahan form-select form-select-md"
                                data-allow-clear="true" required>
                            </select>
                            <div class="invalid-feedback"> Kelurahan harus dipilih... </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="kec" class="form-label fs-6">Kecamatan</label>
                            <input type="text" id="kec" name="kec" class="form-control" autocomplete="off"
                                readonly>
                        </div>
                        <div class="col-sm-3">
                            <label for="kab_kota" class="form-label fs-6">Kabupaten/Kota</label>
                            <input type="text" id="kab_kota" name="kab_kota" class="form-control" autocomplete="off"
                                readonly>
                        </div>
                        <div class="col-sm-3">
                            <label for="prov" class="form-label fs-6">Provinsi</label>
                            <input type="text" id="prov" name="prov" class="form-control" autocomplete="off"
                                readonly>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
