<div class="story-form-fields">
    <label class="modern-field">
        <span>Foto Kegiatan</span>
        <div class="photo-input-row">
            <input id="photoInput" class="file-input" type="file" name="photos[]" accept="image/png,image/jpeg" multiple required>
            <button type="button" id="addPhotoButton" class="btn-add-photo" aria-label="Tambah gambar">Tambah Gambar</button>
        </div>
        <small class="field-help">Pilih satu atau lebih foto kegiatan. Format JPG, JPEG, atau PNG. Maksimal 2 MB per foto.</small>
        @error('photos')
            <small class="field-error">{{ $message }}</small>
        @enderror
        @error('photos.*')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>

    <div id="photoPreview" class="story-photo-preview-grid" hidden>
    </div>

    <label class="modern-field">
        <span>Judul Cerita</span>
        <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Lapak Baca di Tepian Sungai Siak" required>
        @error('title')
            <small>{{ $message }}</small>
        @enderror
    </label>

    <label class="modern-field">
        <span>Isi Cerita</span>
        <textarea name="story" rows="8" placeholder="Tulis cerita kegiatan singkat..." required>{{ old('story') }}</textarea>
        @error('story')
            <small>{{ $message }}</small>
        @enderror
    </label>

    <button class="modern-submit" type="submit">Kirim Cerita</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addBtn = document.getElementById('addPhotoButton');
        const photoInput = document.getElementById('photoInput');
        const preview = document.getElementById('photoPreview');

        if (!addBtn || !photoInput) return;

        // Store selected files so user can add multiple times
        let filesStore = [];

        // Open file chooser when clicking the visible button
        addBtn.addEventListener('click', function () {
            photoInput.click();
        });

        // When user selects files, append them to filesStore (avoid exact duplicates)
        photoInput.addEventListener('change', function () {
            const newFiles = Array.from(photoInput.files || []);

            newFiles.forEach(function (file) {
                const duplicate = filesStore.some(f => f.name === file.name && f.size === file.size && f.type === file.type);
                if (!duplicate) {
                    filesStore.push(file);
                }
            });

            syncInputWithStore();
            renderPreview();
        });

        // Sync the hidden file input's FileList to match filesStore so form submits correctly
        function syncInputWithStore() {
            const dataTransfer = new DataTransfer();
            filesStore.forEach(function (file) {
                dataTransfer.items.add(file);
            });
            photoInput.files = dataTransfer.files;
            updateButtonLabel();
        }

        // Render preview grid with remove buttons
        function renderPreview() {
            preview.innerHTML = '';

            if (filesStore.length === 0) {
                preview.hidden = true;
                return;
            }

            preview.hidden = false;

            filesStore.forEach(function (file, index) {
                const reader = new FileReader();
                const item = document.createElement('div');
                item.className = 'preview-item';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'preview-remove';
                removeBtn.setAttribute('aria-label', 'Hapus gambar');
                removeBtn.textContent = '×';
                removeBtn.addEventListener('click', function () {
                    filesStore.splice(index, 1);
                    syncInputWithStore();
                    renderPreview();
                });

                reader.addEventListener('load', function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    item.appendChild(img);
                    item.appendChild(removeBtn);
                });

                reader.readAsDataURL(file);
                preview.appendChild(item);
            });
        }

        function updateButtonLabel() {
            addBtn.textContent = filesStore.length > 0 ? `Tambah Gambar (${filesStore.length})` : 'Tambah Gambar';
        }

    });
</script>
