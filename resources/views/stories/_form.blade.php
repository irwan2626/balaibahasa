<div class="story-form-fields">
    <label class="modern-field">
        <span>Foto Kegiatan</span>
        <input id="photoInput" class="file-input" type="file" name="photos[]" accept="image/png,image/jpeg" multiple required>
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
