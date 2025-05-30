/**
 * Script pour améliorer l'expérience utilisateur des modales d'administration
 * Ajoute des animations et des effets pour un aspect plus moderne et professionnel
 */

// Attendre que le document soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les améliorations
    initModalEnhancements();
});

/**
 * Initialise toutes les améliorations des modales
 */
function initModalEnhancements() {
    // Amélioration des modales
    enhanceModals();
    
    // Amélioration des formulaires dans les modales
    enhanceForms();
    
    // Amélioration de la gestion des images
    enhanceImageManagement();
    
    // Ajouter une classe pour activer les animations CSS
    setTimeout(function() {
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.classList.add('animations-enabled');
        });
    }, 100);
}

/**
 * Améliore l'apparence et le comportement des modales
 */
function enhanceModals() {
    // Ajouter une classe pour l'animation d'entrée
    $('.modal').on('show.bs.modal', function() {
        setTimeout(() => {
            $(this).addClass('modal-animated');
        }, 10);
    });
    
    // Réinitialiser l'animation à la fermeture
    $('.modal').on('hidden.bs.modal', function() {
        $(this).removeClass('modal-animated');
    });
    
    // Améliorer l'apparence des boutons de fermeture
    $('.modal .close').html('<i class="fas fa-times"></i>');
    
    // Ajouter un effet de survol aux boutons
    $('.modal .btn').hover(
        function() { $(this).addClass('btn-hover'); },
        function() { $(this).removeClass('btn-hover'); }
    );
}

/**
 * Améliore l'expérience utilisateur des formulaires dans les modales
 */
function enhanceForms() {
    // Ajouter des effets de focus aux champs de formulaire
    $('.modal .form-control').focus(function() {
        $(this).closest('.form-group').addClass('focused');
    }).blur(function() {
        $(this).closest('.form-group').removeClass('focused');
    });
    
    // Améliorer l'apparence des champs de fichier
    $('.modal .form-control-file').each(function() {
        const fileInput = $(this);
        const label = fileInput.prev('label');
        
        fileInput.on('change', function() {
            let fileName = '';
            if (this.files && this.files.length > 1) {
                fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
            } else if (this.files && this.files.length === 1) {
                fileName = this.files[0].name;
            }
            
            if (fileName) {
                label.addClass('has-file').find('.file-name').html(fileName);
            } else {
                label.removeClass('has-file').find('.file-name').html('');
            }
        });
    });
}

/**
 * Améliore la gestion des images dans les modales
 */
function enhanceImageManagement() {
    // Prévisualisation des images avant upload
    $('#add_images, #edit_images').on('change', function() {
        const preview = $('<div class="image-preview-container"></div>');
        const files = this.files;
        
        // Supprimer les prévisualisations précédentes
        $(this).siblings('.image-preview-container').remove();
        
        if (files && files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.match('image.*')) {
                    const reader = new FileReader();
                    const previewItem = $('<div class="image-preview-item"></div>');
                    
                    reader.onload = function(e) {
                        previewItem.append('<img src="' + e.target.result + '" alt="Aperçu">');
                        preview.append(previewItem);
                    };
                    
                    reader.readAsDataURL(files[i]);
                }
            }
            
            $(this).after(preview);
        }
    });
    
    // Améliorer l'interaction avec les images existantes
    $('#current_images .image-container').hover(
        function() { $(this).addClass('hover'); },
        function() { $(this).removeClass('hover'); }
    );
    
    // Animation lors de la suppression d'une image
    $('#current_images .delete-image').on('click', function() {
        const container = $(this).closest('.image-container');
        container.addClass('removing');
        setTimeout(() => {
            container.hide('fade');
        }, 300);
    });
}