@extends('layouts.admin')
@section('content')
    <br><br>
    <div class="container">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Place
                            <a href="{{ url('admin/place_management') }}" class="btn btn-primary float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <!-- IMPORTANT: add enctype -->
                        <form action="{{route('place_management.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter name"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="town" class="form-label">Town</label>
                                <select id="town" name="town" class="form-control" required>
                                    <option value="">Select town</option>
                                </select>
                            </div>
                            <input type="hidden" name="town_name" id="town_name">


                            <div class="mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <select id="barangay" name="barangay" class="form-control" required>
                                    <option value="">Select barangay</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control"
                                    placeholder="Enter Description" required></textarea>
                            </div>

                            <!-- Image upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Images (max 7 images, 1MB each)</label>
                                <input type="file" id="image" name="image[]" class="form-control" accept="image/*" multiple
                                    required>
                            </div>

                            <!-- Preview container -->
                            <div id="preview" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>

                            <!-- Submit -->
                            <div class="mb-3 mt-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                const townSelect = document.getElementById('town');
                const barangaySelect = document.getElementById('barangay');
                const townNameInput = document.getElementById('town_name'); // hidden input for town name

                // Load towns (municipalities + cities)
                fetch("https://psgc.gitlab.io/api/provinces/013300000/municipalities/")
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            const option = document.createElement("option");
                            option.value = item.code;       // PSGC code
                            option.textContent = item.name; // Town name
                            townSelect.appendChild(option);
                        });
                    });

                // When town changes → load barangays & set town_name
                townSelect.addEventListener("change", function () {
                    const code = this.value; // PSGC code
                    const name = this.options[this.selectedIndex].text; // town name

                    // Set hidden input value for town_name
                    townNameInput.value = name;

                    // Load barangays
                    barangaySelect.innerHTML = `<option value="">Loading...</option>`;
                    fetch(`https://psgc.gitlab.io/api/municipalities/${code}/barangays/`)
                        .then(res => res.json())
                        .then(data => {
                            barangaySelect.innerHTML = `<option value="">Select barangay</option>`;
                            data.forEach(item => {
                                const option = document.createElement("option");
                                option.value = item.name;       // You can store code if needed: item.code
                                option.textContent = item.name;
                                barangaySelect.appendChild(option);
                            });
                        });
                });

                // Image upload preview
                const input = document.getElementById("image");
                const preview = document.getElementById("preview");

                input.addEventListener("change", function () {
                    const files = this.files;
                    preview.innerHTML = ""; // clear preview

                    // Check number of images
                    if (files.length > 7) {
                        alert("You can only upload a maximum of 7 images.");
                        this.value = "";
                        return;
                    }

                    // Process each image
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];

                        // Size check (1MB)
                        if (file.size > 1048576) {
                            alert("Each image must be 1MB or smaller.");
                            this.value = "";
                            preview.innerHTML = "";
                            return;
                        }

                        // Preview
                        const reader = new FileReader();
                        reader.onload = function (e) {
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
                    }
                });

            });
        </script>


@endsection