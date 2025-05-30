/**
 * Script pour la gestion des carnets de voyage dans l'interface d'administration
 */

// Function to confirm deletion of a carnet 
function confirmDelete(id, title) { 
    document.getElementById('delete_carnet_title').textContent = title; 
    document.getElementById('confirm_delete_btn').href = 'admin.php?section=carnets&action=delete&id=' + id; 
    $('#deleteCarnetModal').modal('show'); 
}

// Validate form fields 
function validateForm(formId) { 
    let form;
    
    if (typeof formId === 'string') {
        form = document.getElementById(formId);
    } else {
        // Si formId n'est pas une chaîne, on suppose que c'est l'élément form lui-même
        form = formId;
    }
    
    // Si form est toujours null ou undefined, on utilise le formulaire actif
    if (!form) {
        console.warn('Formulaire non trouvé, utilisation du formulaire actif');
        form = document.querySelector('form:focus') || document.querySelector('form');
    }
    
    const titleField = form.querySelector('[name="title"]');
    const authorField = form.querySelector('[name="author"]');
    
    if (!titleField || !authorField) {
        console.error('Champs titre ou auteur non trouvés dans le formulaire');
        return true; // Allow submission if fields not found
    }
    
    const title = titleField.value.trim(); 
    const author = authorField.value.trim(); 
    
    if (!title || !author) { 
        alert('Les champs Titre et Auteur sont obligatoires.'); 
        return false; 
    } 
    return true; 
}

// Remove the duplicate form validation (lines 85-92)
$(document).ready(function() { 
    // Handle edit button clicks 
    $('.edit-carnet-btn').on('click', function() { 
        var carnetId = $(this).data('id'); 
        
        // Reset form and show loading state 
        $('#editCarnetForm')[0].reset(); 
        $('#current_images').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Chargement...</span></div></div>'); 
        $('#editCarnetModal').modal('show'); 
        
        // Load carnet data via AJAX 
        $.ajax({ 
            url: 'admin.php?section=carnets&action=edit&id=' + carnetId + '&ajax=1', 
            type: 'GET', 
            dataType: 'json', 
            // Update the AJAX success handler to populate new fields
            success: function(response) {
                if (response.success) {
                    var carnet = response.carnet;
                    
                    // Populate form fields
                    $('#edit_carnet_id').val(carnet.id);
                    $('#edit_title').val(carnet.title);
                    $('#edit_author').val(carnet.author);
                    $('#edit_locations').val(carnet.locations.join(', '));
                    $('#edit_place').val(carnet.place || '');
                    $('#edit_transport').val(carnet.transport || '');
                    $('#edit_content').val(carnet.content || '');
                    
                    // Display current images
                    var imagesHtml = '';
                    if (carnet.images && carnet.images.length > 0) {
                        carnet.images.forEach(function(image) {
                            imagesHtml += '<div class="col-md-4 mb-2">';
                            imagesHtml += '<div class="card">';
                            imagesHtml += '<img src="../' + image.path + '" class="card-img-top" alt="Image du carnet" style="max-height: 150px; object-fit: cover;">';
                            imagesHtml += '<div class="card-body p-2 text-center">';
                            imagesHtml += '<div class="custom-control custom-checkbox">';
                            imagesHtml += '<input type="checkbox" class="custom-control-input" id="delete_image_' + image.id + '" name="delete_images[]" value="' + image.id + '">';
                            imagesHtml += '<label class="custom-control-label" for="delete_image_' + image.id + '">Supprimer</label>';
                            imagesHtml += '</div></div></div></div>';
                        });
                    } else {
                        imagesHtml = '<div class="col-12"><p class="text-muted">Aucune image disponible</p></div>';
                    }
                    $('#current_images').html(imagesHtml);
                } else {
                    $('#current_images').html('<div class="col-12 text-danger">Erreur: ' + response.message + '</div>');
                }
            },
            error: function() { 
                $('#current_images').html('<div class="col-12 text-danger">Erreur lors du chargement des données.</div>'); 
            } 
        }); 
    }); 

    // Simple form validation
    $('#addCarnetForm').on('submit', function(e) {
        if (!validateForm('addCarnetForm')) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires');
        }
    });

    // Validate edit carnet form on submit 
    // Add this temporary debug code
    // Temporarily disable validation
    $('#editCarnetForm').on('submit', function(e) { 
    // if (!validateForm(this)) { 
    //     e.preventDefault(); 
    // }
    console.log('Form submitting without validation');
    });
    console.log('Form submission attempted'); // Debug log
    if (!validateForm(this)) { 
        console.log('Validation failed'); // Debug log
        e.preventDefault(); 
    } else {
        console.log('Validation passed, submitting form'); // Debug log
    }
});

// Initialize modal functionality
$(document).ready(function() {
    $('#addCarnetModal').on('shown.bs.modal', function () {
        // Reset form on modal show
        $('#addCarnetForm')[0].reset();
        $('#locationsContainer').html(`
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="locations[]">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary add-location" type="button">+</button>
                </div>
            </div>
        `);
    });

    // Modify the modal hidden event handler
    $('#addCarnetModal').on('hidden.bs.modal', function() {
        const form = document.getElementById('addCarnetForm');
        if(form) form.reset();
        
        // Clear image previews
        $('#imagePreviews').html('');
        // Reset validation states
        $('.needs-validation').removeClass('was-validated');
    });

    // Dynamic location fields
    $(document).on('click', '.add-location', function() {
        const newLocation = $('<div>').addClass('input-group mb-2').html(`
            <input type="text" class="form-control" name="locations[]">
            <div class="input-group-append">
                <button class="btn btn-outline-danger remove-location" type="button">-</button>
            </div>
        `);
        $('#locationsContainer').append(newLocation);
    });

    $(document).on('click', '.remove-location', function() {
        $(this).closest('.input-group').remove();
    });
});