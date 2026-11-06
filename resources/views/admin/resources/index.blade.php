@extends('layouts.portal')

@section('title', 'Media & Resources — Admin Portal')

@section('sidebar')
    <li><a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> User Management</a></li>
    <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-boxes"></i> Product Moderation</a></li>
    <li><a href="{{ route('admin.cms') }}"><i class="fas fa-edit"></i> CMS & Pages</a></li>
    <li><a href="{{ route('admin.resources') }}" class="active"><i class="fas fa-file-pdf"></i> Media & Resources</a></li>
    <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Site Settings</a></li>
@endsection

@section('styles')
<style>
/* Custom pill styles to match TEFC's elegant gold/burgundy theme */
.nav-pills .nav-link {
    color: #7d6e5e;
    font-weight: 600;
    border-radius: 20px;
    padding: 0.4rem 1.2rem;
    transition: all 0.2s;
    border: 1px solid transparent;
}
.nav-pills .nav-link:hover {
    color: #6c1340;
    background-color: #fbf9f6;
    border-color: rgba(179, 142, 93, 0.2);
}
.nav-pills .nav-link.active {
    background-color: #6c1340;
    color: #fff;
}
.media-preview-container {
    aspect-ratio: 4/3;
    background: #fbf9f6;
    position: relative;
    border-bottom: 1px solid #ebdcb9;
}
.media-preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.badge-media-category {
    background-color: #fbf9f6;
    color: #7d6e5e;
    border: 1px solid #ebdcb9;
    font-size: 0.65rem;
}
.badge-media-section {
    background-color: #f3e8ee;
    color: #6c1340;
    border: 1px solid #ebdcb9;
    font-size: 0.65rem;
}
</style>
@endsection

@section('content')
<div class="row g-4">
    <!-- LEFT COLUMN: UPLOAD/ADD FORM -->
    <div class="col-md-4">
        <div class="portal-card">
            <div class="portal-card-header">
                <h3 id="form_header_title">Upload New Media</h3>
            </div>
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- UPLOAD TYPE SELECT -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Type</label>
                        <select name="upload_type" id="upload_type" class="form-select" required>
                            <option value="document">Document (PDF / Excel / Word)</option>
                            <option value="photo">Photo (Gallery Image)</option>
                            <option value="video">Video (MP4 or YouTube)</option>
                        </select>
                    </div>

                    <!-- TITLE FIELD (OPTIONAL) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold" id="title_label">Title <span class="text-muted fw-normal small">(Optional - defaults to file name)</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Optional title (leave blank to use filename)">
                    </div>

                    <!-- CATEGORIES TOGGLE FIELDS -->
                    <div class="mb-3" id="document_category_container">
                        <label class="form-label fw-bold">Document Category <span class="text-muted fw-normal small">(Optional)</span></label>
                        <select name="category" id="document_category" class="form-select">
                            <option value="General">General</option>
                            <option value="Trade Reports">Trade Reports</option>
                            <option value="Scheme Guidelines">Scheme Guidelines</option>
                            <option value="Training Materials">Training Materials</option>
                            <option value="Market Research">Market Research</option>
                        </select>
                    </div>

                    <div class="mb-3" id="photo_category_container" style="display: none;">
                        <label class="form-label fw-bold">Photo Category <span class="text-muted fw-normal small">(Optional)</span></label>
                        <input type="text" name="category" id="photo_category" class="form-control" placeholder="Optional category (defaults to General)" disabled>
                        <div class="form-text small">Common: Capacity Building, Buyer-Seller Meets, Exhibitions, Quality Forums</div>
                    </div>

                    <div class="mb-3" id="video_category_container" style="display: none;">
                        <label class="form-label fw-bold">Video Category <span class="text-muted fw-normal small">(Optional)</span></label>
                        <input type="text" name="category" id="video_category" class="form-control" placeholder="Optional category (defaults to General)" disabled>
                        <div class="form-text small">Common: Export Promotion, Quality Standards, Documentation, FTA Benefits, MSME Capacity</div>
                    </div>

                    <!-- VIDEO SPECIFIC FIELDS -->
                    <div class="mb-3" id="video_source_container" style="display: none;">
                        <label class="form-label fw-bold">Video Source</label>
                        <select name="video_source" id="video_source" class="form-select" disabled>
                            <option value="file">Local Video Upload (MP4)</option>
                            <option value="youtube">YouTube URL</option>
                        </select>
                    </div>

                    <div class="mb-3" id="video_section_container" style="display: none;">
                        <label class="form-label fw-bold">Target Video Section <span class="text-muted fw-normal small">(Optional)</span></label>
                        <select name="video_section" id="video_section" class="form-select" disabled>
                            <option value="gallery">Home Gallery Grid</option>
                            <option value="media_training">Training Videos Section</option>
                            <option value="media_cluster">Cluster Videos Section</option>
                            <option value="media_other">Other Videos Section</option>
                        </select>
                    </div>

                    <div class="mb-3" id="video_url_container" style="display: none;">
                        <label class="form-label fw-bold">YouTube Video URL</label>
                        <input type="url" name="video_url" id="video_url" class="form-control" placeholder="e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ" disabled>
                    </div>

                    <!-- COMMON FILE UPLOAD FIELD -->
                    <div class="mb-3" id="file_container">
                        <label class="form-label fw-bold" id="file_label">Select File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file_input" class="form-control" required>
                    </div>

                    <div class="alert alert-light border py-2 px-3 small text-muted mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Only the <strong>file</strong> is mandatory. Title and Category are optional.</span>
                    </div>

                    <button type="submit" id="submit_btn" class="btn btn-primary w-100 py-2 fw-bold">Upload Resource</button>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: MANAGED MEDIA LIBRARY -->
    <div class="col-md-8">
        <div class="portal-card">
            <!-- Card Header with dynamic tabs for documents, photos and videos -->
            <div class="portal-card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h3 class="mb-0">Managed Media Library</h3>
                
                <ul class="nav nav-pills" id="mediaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs-sec" type="button" role="tab" aria-controls="docs-sec" aria-selected="true">
                            <i class="fas fa-file-pdf me-1"></i> Documents
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos-sec" type="button" role="tab" aria-controls="photos-sec" aria-selected="false">
                            <i class="fas fa-images me-1"></i> Photo Gallery
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="videos-tab" data-bs-toggle="tab" data-bs-target="#videos-sec" type="button" role="tab" aria-controls="videos-sec" aria-selected="false">
                            <i class="fas fa-video me-1"></i> Videos Grid
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="mediaTabContent">
                
                <!-- 1. DOCUMENTS TAB CONTENT -->
                <div class="tab-pane fade show active" id="docs-sec" role="tabpanel" aria-labelledby="docs-tab">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>File Info</th>
                                    <th>Category</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resources as $resource)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-light rounded p-2 text-primary">
                                                    <i class="fas fa-file-alt fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $resource->title }}</div>
                                                    <div class="text-muted small text-uppercase" style="font-size: 0.65rem;">{{ $resource->file_type }} • {{ round($resource->file_size / 1024, 2) }} KB</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $resource->category }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ asset($resource->file_path) }}" target="_blank" class="action-btn" title="View Document"><i class="fas fa-external-link-alt"></i></a>
                                                <form action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Permanently delete this resource?')" class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">No media resources uploaded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. PHOTOS TAB CONTENT -->
                <div class="tab-pane fade" id="photos-sec" role="tabpanel" aria-labelledby="photos-tab">
                    <div class="p-4">
                        @php
                            $galleryItems = $gallerySection ? ($gallerySection->content['items'] ?? []) : [];
                            // Photos from gallery section
                            $photosList = [];
                            foreach ($galleryItems as $idx => $item) {
                                if (empty($item['video']) && (!isset($item['type']) || $item['type'] !== 'video')) {
                                    $photosList[] = array_merge($item, [
                                        'gallery_index' => $idx,
                                        'section_type' => 'gallery'
                                    ]);
                                }
                            }
                            // Also check media page if any additional items exist
                            if ($mediaPage) {
                                $mediaPhotosSec = $mediaPage->sections->where('type', 'media_photos')->first()
                                    ?? $mediaPage->sections()->where('type', 'media_center')->first();
                                if ($mediaPhotosSec) {
                                    $mItems = $mediaPhotosSec->content['items'] ?? [];
                                    foreach ($mItems as $mIdx => $mItem) {
                                        $exists = false;
                                        foreach ($photosList as $p) {
                                            if (($p['image'] ?? '') === ($mItem['image'] ?? '')) {
                                                $exists = true; break;
                                            }
                                        }
                                        if (!$exists && !empty($mItem['image'])) {
                                            $photosList[] = array_merge($mItem, [
                                                'gallery_index' => $mIdx,
                                                'section_type' => $mediaPhotosSec->type
                                            ]);
                                        }
                                    }
                                }
                            }
                        @endphp
                        @if(count($photosList) > 0)
                            <div class="row g-3">
                                @foreach($photosList as $photo)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="card h-100 border shadow-sm rounded-3 overflow-hidden">
                                            <div class="media-preview-container position-relative" style="height: 160px; background: #111;">
                                                @if(!empty($photo['image']))
                                                    <img src="{{ asset($photo['image']) }}" alt="{{ $photo['title'] ?? 'Gallery Photo' }}" class="media-preview-img" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                        <i class="far fa-image fa-2x"></i>
                                                    </div>
                                                @endif
                                                <div class="position-absolute top-0 end-0 p-2">
                                                    <form action="{{ route('admin.media.destroy', ['section_type' => $photo['section_type'], 'index' => $photo['gallery_index']]) }}" method="POST" onsubmit="return confirm('Delete this photo from the gallery?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 28px; height: 28px; padding: 0;" title="Delete Photo">
                                                            <i class="fas fa-trash-alt" style="font-size: 0.75rem;"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="{{ $photo['title'] ?? 'Gallery Photo' }}">{{ $photo['title'] ?? 'Gallery Photo' }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge badge-media-category bg-light text-primary border">{{ $photo['category'] ?? 'General' }}</span>
                                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $photo['published'] ?? '' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-images fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">No photos in the gallery yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 3. VIDEOS TAB CONTENT -->
                <div class="tab-pane fade" id="videos-sec" role="tabpanel" aria-labelledby="videos-tab">
                    <div class="p-4">
                        @php
                            $allVideos = [];
                            // Videos from gallery section
                            foreach ($galleryItems as $idx => $item) {
                                if (!empty($item['video']) || (isset($item['type']) && $item['type'] === 'video')) {
                                    $allVideos[] = array_merge($item, [
                                        'section_type' => 'gallery',
                                        'section_title' => 'Home Gallery',
                                        'original_index' => $idx,
                                        'url' => $item['video'] ?? $item['url'] ?? ''
                                    ]);
                                }
                            }
                            // Videos from media page
                            if ($mediaPage) {
                                $videoSections = ['media_training' => 'Training Videos', 'media_cluster' => 'Cluster Videos', 'media_other' => 'Other Videos'];
                                foreach ($videoSections as $type => $secTitle) {
                                    $vSec = $mediaPage->sections->where('type', $type)->first();
                                    $items = $vSec ? ($vSec->content['items'] ?? []) : [];
                                    foreach ($items as $idx => $item) {
                                        $allVideos[] = array_merge($item, [
                                            'section_type' => $type,
                                            'section_title' => $secTitle,
                                            'original_index' => $idx
                                        ]);
                                    }
                                }
                            }
                        @endphp
                        @if(count($allVideos) > 0)
                            <div class="row g-3">
                                @foreach($allVideos as $video)
                                    <div class="col-md-6">
                                        <div class="card h-100 border shadow-sm rounded-3">
                                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                        <h6 class="fw-bold text-dark mb-0 text-truncate" style="flex: 1;" title="{{ $video['title'] ?? 'Video' }}">{{ $video['title'] ?? 'Video' }}</h6>
                                                        <form action="{{ route('admin.media.destroy', ['section_type' => $video['section_type'], 'index' => $video['original_index']]) }}" method="POST" onsubmit="return confirm('Delete this video from the gallery?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Delete Video">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <p class="text-muted small text-truncate mb-3" style="font-size: 0.75rem;" title="{{ $video['url'] ?? '' }}">
                                                        <i class="fas fa-play-circle me-1 text-primary"></i> {{ $video['url'] ?? 'Video Stream' }}
                                                    </p>
                                                </div>
                                                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between pt-2 border-top">
                                                    <div>
                                                        <span class="badge badge-media-category bg-light text-primary border">{{ $video['category'] ?? 'General' }}</span>
                                                        <span class="badge badge-media-section bg-secondary text-white">{{ $video['section_title'] }}</span>
                                                    </div>
                                                    <small class="text-muted" style="font-size: 0.7rem;">
                                                        <i class="far fa-clock"></i> {{ $video['duration'] ?? 'Video' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-video fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">No videos uploaded yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadTypeSelect = document.getElementById('upload_type');
    const formHeaderTitle = document.getElementById('form_header_title');
    
    // Category containers
    const documentCategoryContainer = document.getElementById('document_category_container');
    const photoCategoryContainer = document.getElementById('photo_category_container');
    const videoCategoryContainer = document.getElementById('video_category_container');
    
    // Category input fields
    const documentCategory = document.getElementById('document_category');
    const photoCategory = document.getElementById('photo_category');
    const videoCategory = document.getElementById('video_category');

    // File input details
    const fileContainer = document.getElementById('file_container');
    const fileLabel = document.getElementById('file_label');
    const fileInput = document.getElementById('file_input');

    // Video details
    const videoSourceContainer = document.getElementById('video_source_container');
    const videoSourceSelect = document.getElementById('video_source');
    const videoUrlContainer = document.getElementById('video_url_container');
    const videoUrlInput = document.getElementById('video_url');
    const videoSectionContainer = document.getElementById('video_section_container');
    const videoSectionSelect = document.getElementById('video_section');

    const submitBtn = document.getElementById('submit_btn');

    function toggleFields() {
        const type = uploadTypeSelect.value;
        
        // Hide and disable all category fields by default
        documentCategoryContainer.style.display = 'none';
        documentCategory.disabled = true;
        photoCategoryContainer.style.display = 'none';
        photoCategory.disabled = true;
        videoCategoryContainer.style.display = 'none';
        videoCategory.disabled = true;

        // Hide and disable all video specific fields by default
        videoSourceContainer.style.display = 'none';
        videoSourceSelect.disabled = true;
        videoUrlContainer.style.display = 'none';
        videoUrlInput.disabled = true;
        videoUrlInput.required = false;
        videoSectionContainer.style.display = 'none';
        videoSectionSelect.disabled = true;

        // Reset file input by default
        fileContainer.style.display = 'none';
        fileInput.disabled = true;
        fileInput.required = false;

        if (type === 'document') {
            formHeaderTitle.textContent = 'Upload New Document';
            documentCategoryContainer.style.display = 'block';
            documentCategory.disabled = false;
            
            fileContainer.style.display = 'block';
            fileLabel.innerHTML = 'Select Document File (PDF, Excel, Word) <span class="text-danger">*</span>';
            fileInput.accept = '.pdf,.doc,.docx,.xls,.xlsx';
            fileInput.disabled = false;
            fileInput.required = true;
            
            submitBtn.textContent = 'Upload Document';
        } else if (type === 'photo') {
            formHeaderTitle.textContent = 'Upload Gallery Photo';
            photoCategoryContainer.style.display = 'block';
            photoCategory.disabled = false;
            
            fileContainer.style.display = 'block';
            fileLabel.innerHTML = 'Select Image File (JPG, PNG, WEBP) <span class="text-danger">*</span>';
            fileInput.accept = 'image/jpeg,image/png,image/webp,image/gif,image/svg+xml';
            fileInput.disabled = false;
            fileInput.required = true;
            
            submitBtn.textContent = 'Upload Photo to Gallery';
        } else if (type === 'video') {
            formHeaderTitle.textContent = 'Add Gallery Video';
            videoCategoryContainer.style.display = 'block';
            videoCategory.disabled = false;
            
            videoSourceContainer.style.display = 'block';
            videoSourceSelect.disabled = false;
            
            videoSectionContainer.style.display = 'block';
            videoSectionSelect.disabled = false;

            const source = videoSourceSelect.value;
            if (source === 'file') {
                fileContainer.style.display = 'block';
                fileLabel.innerHTML = 'Select Video File (MP4, WEBM) <span class="text-danger">*</span>';
                fileInput.accept = 'video/mp4,video/webm,video/ogg';
                fileInput.disabled = false;
                fileInput.required = true;
                videoUrlContainer.style.display = 'none';
                videoUrlInput.disabled = true;
                videoUrlInput.required = false;
            } else {
                fileContainer.style.display = 'none';
                fileInput.disabled = true;
                fileInput.required = false;
                videoUrlContainer.style.display = 'block';
                videoUrlInput.disabled = false;
                videoUrlInput.required = true;
            }
            
            submitBtn.textContent = 'Add Video to Gallery';
        }
    }

    uploadTypeSelect.addEventListener('change', toggleFields);
    videoSourceSelect.addEventListener('change', toggleFields);
    
    // Perform initial toggle
    toggleFields();
});
</script>
@endsection
