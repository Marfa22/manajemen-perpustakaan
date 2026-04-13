      <div class="modal fade delete-modal" id="modal-delete-{{ $inventory->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modern-delete-modal">
              <form action="{{ request()->getBaseUrl() }}/inventory/{{ $inventory->id }}" method="post" class="delete-modal-form">
                  @csrf
                  @method('DELETE')
                  <div class="modal-header">
                      <span class="delete-modal-icon">
                        <i class="fas fa-trash-alt"></i>
                      </span>
                      <button type="button" class="delete-modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <h5 class="delete-modal-title">Hapus barang kantor?</h5>
                      <p class="delete-modal-text">
                        Barang <strong>{{ $inventory->nama }}</strong> akan dihapus permanen dari sistem.
                      </p>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-cancel" data-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-confirm">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                      </button>
                  </div>
              </form>
            </div>
        </div>
      </div>
