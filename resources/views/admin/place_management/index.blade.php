@extends('layouts.admin')
@section('content')
    <br><br>
    <style>
        @media (max-width: 576px) {

            .table th,
            .table td {
                padding: 0.25rem;
                font-size: 0.875rem;
            }
        }

        .description-cell {
            max-width: 300px;
            word-wrap: break-word;
            white-space: normal;
        }
    </style>
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
                        <h4>Place Lists

                            <a href="{{url('admin/place_management/create')}}" class="btn btn-primary float-end">Add
                                Place</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm w-100">
                                <thead>
                                    <tr>
                                        <th>Place Name</th>
                                        <th>Town</th>
                                        <th>Barangay</th>
                                        <th>Description</th>
                                        <th>Images</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($places as $place)
                                        <tr>
                                            <td>{{ $place['name'] }}</td>
                                            <td>{{ $place['town_name'] }}</td>
                                            <td>{{ $place['barangay'] }}</td>
                                            <td style="max-width: 300px; word-wrap: break-word; white-space: normal;">
                                                {{ $place['description'] }}
                                            </td>
                                            <td>
                                                @if ($place['image'])
                                                    <img src="{{ $place['image'] }}" width="70" height="70"
                                                        style="object-fit:cover;border-radius:6px;cursor:pointer;"
                                                        class="place-image" data-id="{{ $place['id'] }}">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="{{ route('place_management.edit', $place['id']) }}"
                                                        class="btn btn-outline-primary btn-sm" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <form action="{{ route('place_management.destroy', $place['id']) }}"
                                                        method="POST" onsubmit="return confirm('Delete this place?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-outline-danger btn-sm" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $places->links() }}
                            </div>

                        </div>
                        <!-- SweetAlert2 JS -->
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            function confirmDelete(id) {
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "This action cannot be undone!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Yes, delete it!',
                                    cancelButtonText: 'Cancel'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('delete-form-' + id).submit();
                                    }
                                });
                            }
                        </script>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                document.addEventListener('click', async (e) => {
                                    if (!e.target.classList.contains('place-image')) return;
                                    const id = e.target.dataset.id;

                                    try {
                                        const res = await fetch(`/admin/place_management/${id}/images`);
                                        if (!res.ok) throw new Error('Network error');

                                        const images = await res.json();

                                        // Scrollable container with margin
                                        let html = `
                                                        <div style="
                                                            display:flex;
                                                            flex-direction:column;
                                                            gap:20px;
                                                            overflow-y:auto;
                                                            max-height:calc(100vh - 192px);
                                                            padding:96px;
                                                        ">
                                                    `;
                                        images.forEach(src => {
                                            html += `
                                                            <img 
                                                                src="${src}" 
                                                                style="
                                                                    max-width:100%;
                                                                    max-height:calc(100vh - 192px);
                                                                    object-fit:contain;
                                                                    border-radius:8px;
                                                                    margin:auto;
                                                                "
                                                            >
                                                        `;
                                        });

                                        html += '</div>';

                                        Swal.fire({
                                            title: 'Place Images',
                                            html,
                                            width: '100%',
                                            heightAuto: false,
                                            showConfirmButton: false,
                                            showCloseButton: true,
                                            customClass: {
                                                popup: 'swal2-fullscreen-modal'
                                            }
                                        });

                                    } catch (err) {
                                        console.error(err);
                                        Swal.fire('Error', 'Could not load images', 'error');
                                    }
                                });
                            });
                        </script>

                        <div class="d-flex justify-content-center mt-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection