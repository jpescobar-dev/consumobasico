

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-chart-one">
                    <div class="widget-heading">
                        {{-- <h5 class="">{{ $componentName }} | {{ $pageTitle }}</h5> --}}
                        <button wire:click.prevent="resetUI()" class="btn btn-dark btn-sm">Agregar</button>
                    </div>

                    <div class="widget-content">
                        @include('livewire.roles.table')
                    </div>
                </div>
            </div>
        </div>

        @include('livewire.roles.form')
    </div>
@endsection
