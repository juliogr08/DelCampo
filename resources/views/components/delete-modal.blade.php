<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-muted" id="deleteMessage">¿Estás seguro de que quieres eliminar este registro?</h6>
                    <p class="small text-muted mt-2">
                        <strong>Nota:</strong> Esta acción no se puede deshacer y los datos se perderán permanentemente.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash"></i> Sí, Eliminar
                </button>
            </div>
        </div>
    </div>
</div>