<!-- Photo Upload Component -->
<div class="photo-upload-component" data-problem-item-id="{{ $problemItemId ?? '' }}">
    <div class="photo-upload-area">
        <label class="photo-upload-label">
            <input type="file" name="photo" accept="image/*" class="photo-input" style="display: none;">
            <div class="photo-upload-placeholder">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-600 mt-2">Klik untuk upload foto</p>
                <p class="text-xs text-gray-400">Max 5MB (JPG, PNG, GIF)</p>
            </div>
        </label>
    </div>
    
    <div class="photo-gallery mt-3">
        <div class="photo-grid row g-2">
            <!-- Photos will be loaded dynamically -->
        </div>
    </div>
    
    <div class="photo-loading" style="display: none;">
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="text-sm">Mengupload foto...</span>
        </div>
    </div>
</div>

<style>
    .photo-upload-component {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background-color: #fafafa;
    }
    
    .photo-upload-label {
        cursor: pointer;
        display: block;
    }
    
    .photo-upload-label:hover .photo-upload-placeholder {
        background-color: #f0f0f0;
    }
    
    .photo-upload-placeholder {
        transition: background-color 0.3s;
        padding: 20px;
        border-radius: 8px;
    }
    
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
    }
    
    .photo-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .photo-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        cursor: pointer;
    }
    
    .photo-delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .photo-delete-btn:hover {
        background: rgba(220, 38, 38, 1);
    }
    
    .photo-loading {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 1000;
    }
</style>

<script>
function initPhotoUpload(component) {
    const problemItemId = component.dataset.problemItemId;
    const fileInput = component.querySelector('.photo-input');
    const photoGallery = component.querySelector('.photo-gallery .photo-grid');
    const loadingIndicator = component.querySelector('.photo-loading');
    
    if (!problemItemId) {
        console.error('Problem Item ID is required');
        return;
    }
    
    // Load existing photos
    loadPhotos();
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            uploadPhoto(file);
        }
    });
    
    function uploadPhoto(file) {
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('problem_item_id', problemItemId);
        
        loadingIndicator.style.display = 'block';
        
        fetch('{{ route('photos.upload') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Photo uploaded successfully:', data);
                loadPhotos(); // Reload photos
                showToast('Foto berhasil diupload', 'success');
            } else {
                showToast('Gagal mengupload foto: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error uploading photo:', error);
            showToast('Terjadi kesalahan saat mengupload foto', 'error');
        })
        .finally(() => {
            loadingIndicator.style.display = 'none';
            fileInput.value = ''; // Reset file input
        });
    }
    
    function loadPhotos() {
        fetch(`{{ route('photos.list', ['problem_item_id' => '']) }}${problemItemId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderPhotos(data.photos);
                }
            })
            .catch(error => {
                console.error('Error loading photos:', error);
            });
    }
    
    function renderPhotos(photos) {
        photoGallery.innerHTML = '';
        
        if (photos.length === 0) {
            photoGallery.innerHTML = '<p class="text-muted text-center w-100">Belum ada foto</p>';
            return;
        }
        
        photos.forEach(photo => {
            const photoItem = document.createElement('div');
            photoItem.className = 'photo-item';
            photoItem.innerHTML = `
                <img src="${photo.url}" alt="Problem photo" onclick="window.open('${photo.url}', '_blank')">
                <button class="photo-delete-btn" onclick="deletePhoto('${photo.path}')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            photoGallery.appendChild(photoItem);
        });
    }
    
    window.deletePhoto = function(photoPath) {
        if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
            return;
        }
        
        loadingIndicator.style.display = 'block';
        
        fetch('{{ route('photos.delete') }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                photo_path: photoPath,
                problem_item_id: problemItemId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Photo deleted successfully');
                loadPhotos(); // Reload photos
                showToast('Foto berhasil dihapus', 'success');
            } else {
                showToast('Gagal menghapus foto: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting photo:', error);
            showToast('Terjadi kesalahan saat menghapus foto', 'error');
        })
        .finally(() => {
            loadingIndicator.style.display = 'none';
        });
    }
    
    function showToast(message, type = 'info') {
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                className: type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info',
                duration: 3000
            }).showToast();
        } else {
            alert(message);
        }
    }
}

// Initialize all photo upload components on page load
document.addEventListener('DOMContentLoaded', function() {
    const photoUploadComponents = document.querySelectorAll('.photo-upload-component');
    photoUploadComponents.forEach(component => {
        initPhotoUpload(component);
    });
});
</script>