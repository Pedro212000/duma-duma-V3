@extends('layouts.admin')

@section('content')
    <br><br>
    <div class="container">

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4>Edit Place
                            <a href="{{ url('admin/place_management') }}" class="btn btn-primary float-end">Back</a>
                        </h4>
                    </div>

                    <div class="card-body">
                        {{-- Update Place Form --}}
                        <form action="{{ route('place_management.update', $place_detail->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter name"
                                    value="{{ old('name') ?? $place_detail->name }}" required>
                            </div>

                            {{-- Town --}}
                            <div class="mb-3">
                                <label for="town" class="form-label">Town</label>
                                <select id="town" name="town" class="form-control" required>
                                    <option value="">Select town</option>
                                </select>
                                <input type="hidden" name="town_name" id="town_name" value="{{ $place_detail->town_name }}">
                            </div>

                            {{-- Barangay --}}
                            <div class="mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <select id="barangay" name="barangay" class="form-control" required>
                                    <option value="">Select barangay</option>
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control"
                                    placeholder="Enter Description"
                                    required>{{ old('description') ?? $place_detail->description }}</textarea>
                            </div>

                            {{-- Upload New Images --}}
                            <div class="mb-3">
                                <label class="form-label">Upload New Images (optional – max 7, 1MB each)</label>
                                <input type="file" id="image" name="image[]" class="form-control" accept="image/*" multiple>
                            </div>

                            {{-- New Image Preview --}}
                            <div id="preview" style="display:flex; flex-wrap:wrap; gap:10px;"></div>

                            {{-- Submit --}}
                            <div class="mb-3 mt-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                        {{-- Existing Images --}}
                        @if($place_detail->images->count())
                            <div class="mb-3">
                                <label class="form-label">Existing Images</label>
                                <div style="display:flex; flex-wrap:wrap; gap:12px;">
                                    @foreach($place_detail->images as $img)
                                        <div class="existing-image-container"
                                            style="position:relative; width:120px; height:120px; border-radius:8px; border:1px solid #ccc;">
                                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                                style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                                            <form method="POST" action="{{ route('place_management.image.delete', $img->id) }}"
                                                style="position:absolute; top:2px; right:2px; z-index:50; margin:0; padding:0;"
                                                onsubmit="return confirm('Delete this image?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    style="width:24px; height:24px; font-size:14px; line-height:1; padding:0; border-radius:50%;">✕</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const townSelect = document.getElementById('town');
            const barangaySelect = document.getElementById('barangay');
            const townNameInput = document.getElementById('town_name');
            const imageInput = document.getElementById("image");
            const preview = document.getElementById("preview");

            const dbTownCode = "{{ $place_detail->town_code }}";
            const dbTownName = "{{ $place_detail->town_name }}";
            const dbBarangay = "{{ $place_detail->barangay }}";

            // Load barangays
            function loadBarangays(townCode) {
                barangaySelect.innerHTML = `<option value="">Loading...</option>`;
                fetch(`https://psgc.gitlab.io/api/municipalities/${townCode}/barangays/`)
                    .then(res => res.json())
                    .then(data => {
                        barangaySelect.innerHTML = `<option value="">Select barangay</option>`;
                        data.forEach(item => {
                            const option = document.createElement("option");
                            option.value = item.name;
                            option.textContent = item.name;
                            if (item.name === dbBarangay) option.selected = true;
                            barangaySelect.appendChild(option);
                        });
                    });
            }

            // Load towns
            fetch("https://psgc.gitlab.io/api/provinces/013300000/municipalities/")
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        const option = document.createElement("option");
                        option.value = item.code;
                        option.textContent = item.name;
                        if (item.code === dbTownCode) {
                            option.selected = true;
                            townNameInput.value = item.name;
                            loadBarangays(item.code);
                        }
                        townSelect.appendChild(option);
                    });
                });

            // On town change
            townSelect.addEventListener("change", function () {
                const code = this.value;
                const name = this.options[this.selectedIndex].text;
                townNameInput.value = name;
                if (code) loadBarangays(code);
                else barangaySelect.innerHTML = `<option value="">Select barangay</option>`;
            });


        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const imageInput = document.getElementById("image");
            const preview = document.getElementById("preview");

            function getExistingImagesCount() {
                return document.querySelectorAll('.existing-image-container').length;
            }

            imageInput.addEventListener("change", function () {
                preview.innerHTML = "";

                const files = Array.from(this.files);
                const existingCount = getExistingImagesCount();

                if ((existingCount + files.length) > 7) {
                    alert(`Maximum of 7 images allowed. You already have ${existingCount}.`);
                    this.value = "";
                    return;
                }

                for (const file of files) {
                    if (file.size > 1048576) {
                        alert(`"${file.name}" exceeds 1MB. Please choose smaller images.`);
                        this.value = "";   // ❗ REQUIRED
                        preview.innerHTML = "";
                        return;            // ❗ STOP completely
                    }
                }

                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.style.width = "120px";
                        img.style.height = "120px";
                        img.style.objectFit = "cover";
                        img.style.borderRadius = "8px";
                        img.style.border = "1px solid #ccc";
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>

@endsection