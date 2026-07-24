{{-- Modal Form Customer Master --}}
<div class="modal fade" id="modal-customer" tabindex="-1" aria-labelledby="modal-customerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-customerLabel">Tambah Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-customer" action="javascript:onSaveCustomer()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="customer_id">

                    <ul class="nav nav-tabs mb-3" id="customerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-identity" data-bs-toggle="tab" data-bs-target="#tab-identity-pane" type="button" role="tab">Identitas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#tab-address-pane" type="button" role="tab">Alamat</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-contact" data-bs-toggle="tab" data-bs-target="#tab-contact-pane" type="button" role="tab">Kontak</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-settings" data-bs-toggle="tab" data-bs-target="#tab-settings-pane" type="button" role="tab">Pengaturan</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Tab Identitas --}}
                        <div class="tab-pane fade show active" id="tab-identity-pane" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="c_name" class="form-label fw-semibold">Nama Customer <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_name" name="name" placeholder="Nama lengkap customer" maxlength="200">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_nik" class="form-label fw-semibold">NIK</label>
                                    <input type="text" class="form-control" id="c_nik" name="nik" placeholder="Nomor Induk Kependudukan" maxlength="30">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_nik_name" class="form-label fw-semibold">Nama (NIK)</label>
                                    <input type="text" class="form-control" id="c_nik_name" name="nik_name" placeholder="Nama sesuai NIK" maxlength="200">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_npwp" class="form-label fw-semibold">NPWP</label>
                                    <input type="text" class="form-control" id="c_npwp" name="npwp" placeholder="XX.XXX.XXX.X-XXX.XXX" maxlength="30">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_sim" class="form-label fw-semibold">SIM</label>
                                    <input type="text" class="form-control" id="c_sim" name="sim" placeholder="Nomor SIM" maxlength="30">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_marketing" class="form-label fw-semibold">Marketing</label>
                                    <input type="text" class="form-control" id="c_marketing" name="marketing" placeholder="Nama marketing" maxlength="100">
                                </div>
                            </div>
                        </div>

                        {{-- Tab Alamat --}}
                        <div class="tab-pane fade" id="tab-address-pane" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="c_address1" class="form-label fw-semibold">Alamat 1</label>
                                    <input type="text" class="form-control" id="c_address1" name="address1" placeholder="Jalan, gedung" maxlength="255">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_address2" class="form-label fw-semibold">Alamat 2</label>
                                    <input type="text" class="form-control" id="c_address2" name="address2" placeholder="RT/RW, blok, nomor" maxlength="255">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_kecamatan" class="form-label fw-semibold">Kecamatan</label>
                                    <input type="text" class="form-control" id="c_kecamatan" name="kecamatan" placeholder="Kecamatan" maxlength="100">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_kabupaten" class="form-label fw-semibold">Kabupaten</label>
                                    <input type="text" class="form-control" id="c_kabupaten" name="kabupaten" placeholder="Kabupaten" maxlength="100">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_city" class="form-label fw-semibold">Kota</label>
                                    <input type="text" class="form-control" id="c_city" name="city" placeholder="Kota" maxlength="100">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_zip" class="form-label fw-semibold">Kode Pos</label>
                                    <input type="text" class="form-control" id="c_zip" name="zip" placeholder="Kode pos" maxlength="10">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_province" class="form-label fw-semibold">Provinsi</label>
                                    <input type="text" class="form-control" id="c_province" name="province" placeholder="Provinsi" maxlength="100">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_country" class="form-label fw-semibold">Negara</label>
                                    <input type="text" class="form-control" id="c_country" name="country" placeholder="Negara" maxlength="100">
                                </div>
                            </div>
                        </div>

                        {{-- Tab Kontak --}}
                        <div class="tab-pane fade" id="tab-contact-pane" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="c_contact" class="form-label fw-semibold">Kontak Person</label>
                                    <input type="text" class="form-control" id="c_contact" name="contact" placeholder="Nama kontak" maxlength="200">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_position" class="form-label fw-semibold">Jabatan</label>
                                    <input type="text" class="form-control" id="c_position" name="position" placeholder="Jabatan kontak" maxlength="100">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_phone" class="form-label fw-semibold">Telepon</label>
                                    <input type="text" class="form-control" id="c_phone" name="phone" placeholder="021-XXXXXXXX" maxlength="30">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_mobile" class="form-label fw-semibold">Handphone</label>
                                    <input type="text" class="form-control" id="c_mobile" name="mobile_phone" placeholder="08XXXXXXXXXX" maxlength="30">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" id="c_email" name="email" placeholder="email@domain.com" maxlength="100">
                                </div>
                            </div>
                        </div>

                        {{-- Tab Pengaturan --}}
                        <div class="tab-pane fade" id="tab-settings-pane" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="c_warehouse" class="form-label fw-semibold">Warehouse</label>
                                    <input type="text" class="form-control" id="c_warehouse" name="warehouse" placeholder="Gudang default" maxlength="100">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_channel" class="form-label fw-semibold">Channel Outlet</label>
                                    <select class="form-select" id="c_channel" name="channel_outlet">
                                        <option value="">Pilih Channel</option>
                                        <option value="Distributor">Distributor</option>
                                        <option value="Sub-Distributor">Sub-Distributor</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Wholesale">Wholesale</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="c_rayon" class="form-label fw-semibold">Rayon Sales</label>
                                    <input type="text" class="form-control" id="c_rayon" name="rayon_sales" placeholder="Cth: Jawa Barat 1" maxlength="100">
                                </div>

                                <div class="col-md-6">
                                    <label for="c_price_list" class="form-label fw-semibold">Price List Id</label>
                                    <input type="text" class="form-control" id="c_price_list" name="price_list_id" placeholder="Cth: PL-001" maxlength="50">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_term" class="form-label fw-semibold">Term (Hari)</label>
                                    <input type="number" class="form-control" id="c_term" name="term" placeholder="0" min="0">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_credit_limit" class="form-label fw-semibold">Credit Limit (Rp)</label>
                                    <input type="number" class="form-control" id="c_credit_limit" name="credit_limit" placeholder="0" min="0">
                                </div>

                                <div class="col-md-4">
                                    <label for="c_due_date" class="form-label fw-semibold">Due Date Warning (Hari)</label>
                                    <input type="number" class="form-control" id="c_due_date" name="due_date_warning" placeholder="0" min="0">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Status Aktif</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="c_active" name="active" value="1" checked>
                                        <label class="form-check-label" for="c_active">Aktif</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="c_note" class="form-label fw-semibold">Catatan</label>
                                    <textarea class="form-control" id="c_note" name="note" rows="2" placeholder="Catatan tambahan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
