<!-- Modal -->
<div class="modal fade" id="listDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Akun dari Grup <span id="gg-txt"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="card-datatable table-responsive">
                    <table class="listDetailMasterGg table">
                        <thead class="border-top">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="text" class="form-control" id="acc-detail" name="acc"
                                        placeholder="masukkan kode" autocomplete="off" /></td>
                                <td><input type="text" class="form-control" id="ket-detail" name="ket"
                                        placeholder="masukkan nama" autocomplete="off" /></td>
                                <td><button type="button" class="btn-tambah-detail btn btn-primary me-1 mb-1">
                                        <i class="fa fa-save opacity-50 me-1 float-right"></i> Tambah
                                    </button>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
