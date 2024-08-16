<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="formAdd" class="formAdd" autocomplete="off" novalidate>
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="ket" class="form-label fs-6">Tanggal Order
                            </label>
                            <input type="text" id="tgl" name="tgl" class="tgl form-control date-picker"
                                value="{{ date('d-m-Y') }}" placeholder="Pilih tanggal" autocomplete="off" required>
                            <div class="invalid-feedback"> Tanggal Order diisi... </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="ar_id" class="form-label">Customer</label>
                            <select class="form-select form-select-md" data-allow-clear="true" id="ar_id"
                                name="ar_id" required></select>
                            <div class="invalid-feedback"> Customer harus dipilih... </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="ket" class="form-label fs-6">Tanggal jatuh Tempo
                            </label>
                            <input type="text" id="tjt" name="tjt" class="tjt form-control date-picker"
                                placeholder="Pilih tanggal" autocomplete="off" required>
                            <div class="invalid-feedback"> Tanggal Jatuh Tempo harus dipilih... </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="ctt" class="form-label fs-6">Catatan</label>
                        <textarea id="ctt" name="ctt" class="form-control" placeholder="Masukkan catatan" autocomplete="off"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
