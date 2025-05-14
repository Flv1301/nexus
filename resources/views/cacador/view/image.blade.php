{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body" id="images-preview">
        <div class="d-flex flex-wrap">
            @foreach ($person->images as $count => $image)
                @if(\Illuminate\Support\Facades\Storage::exists($image->path))
                    @php
                        $type = \Illuminate\Support\Facades\Storage::mimeType($image->path);
                        $content = \Illuminate\Support\Facades\Storage::get($image->path);
                        $content = base64_encode($content);
                    @endphp
                    <div class="d-flex flex-column ml-2">
                        <a href="#" label="Open Modal" data-toggle="modal" data-target="#modalMin-{{$count}}">
                            <img class="img-thumbnail" width="200px" height="200px"
                                 src="data:{{$type}};base64,{{$content}}" alt="Imagem {{$count}}"/>
                            @if(request()->routeIs('person.edit'))
                                <a href="#" class="remove-image"
                                   data-url="{{ route('person.remove.image', ['personId' => $person->id, 'imageId' => $image->id]) }}">
                                    <i class="fas fa-trash-alt" style="color: red"></i>
                                </a>
                            @endif
                        </a>
                        <x-adminlte-modal size="xl" id="modalMin-{{$count}}" title="{{$image->description}}"
                                          style="text-align: center">
                            <img width="800" src="data:{{$type}};base64,{{$content}}" alt="Imagem {{$count}}"/>
                        </x-adminlte-modal>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const removeLinks = document.querySelectorAll(".remove-image");
            removeLinks.forEach(function (link) {
                link.addEventListener("click", function (event) {
                    event.preventDefault();
                    const url = this.getAttribute("data-url");
                    const parentElement = this.parentNode;

                    Swal.fire({
                        title: "Deseja realmente excluir?",
                        text: "A imagem será excluída permanentemente!",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                }
                            }).then(response => {
                                if (response.ok) {
                                    return response.json();
                                }
                            }).then(data => {
                                if (data.success) {
                                    parentElement.remove();
                                }
                            })
                        }
                    });
                });
            });
        });
    </script>
@endpush
