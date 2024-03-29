<div>
    @mobile
    @if ($user != null)
        @if ($tab == 'tab1')
            <div style="border-bottom: 1px solid black !important;">
                <div class="row">
                    <div class="col-6 d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-block"
                            wire:click="cambioTab('tab1')">Buscador</button>
                    </div>
                    <div class="ms-auto col-6 d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-block"
                            wire:click="cambioTab('tab2')">Ver/Editar
                            trabajador</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="me-auto col-6 d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-block"
                            wire:click="cambioTab('tab3')">Añadir
                            trabajador</button>
                    </div>
                </div>
                <br>
            </div>
            <br>

            @livewire('trabajadores.index-component')
        @elseif ($tab == 'tab2')
            <div style="border-bottom: 1px solid black !important;">
                <div class="row">
                    <div class="col-6 d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-block"
                            wire:click="cambioTab('tab1')">Buscador</button>
                    </div>
                    <div class="ms-auto col-6 d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-block"
                            wire:click="cambioTab('tab2')">Ver/Editar
                            trabajador</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="me-auto col-6 d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-block"
                            wire:click="cambioTab('tab3')">Añadir
                            trabajador</button>
                    </div>

                </div>
                <br>
            </div>
            <br>


            @livewire('trabajadores.edit-component', ['identificador' => $user], key('tab2'))

            <br>
        @elseif ($tab == 'tab3')
            <div style="border-bottom: 1px solid black !important;">
                <div class="row">
                    <div class="col-6 d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-block"
                            wire:click="cambioTab('tab1')">Buscador</button>
                    </div>
                    <div class="ms-auto col-6 d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-block"
                            wire:click="cambioTab('tab2')">Ver/Editar
                            trabajador</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="me-auto col-6 d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-block" wire:click="cambioTab('tab3')">Añadir
                            trabajador</button>
                    </div>

                </div>
                <br>
            </div>
            <br>

            @livewire('trabajadores.create-component', key('tab3'))
        @endif
    @else
        @if ($tab == 'tab1')
            <div style="border-bottom: 1px solid black !important;">
                <div>
                    <div class="row">
                        <div class="col-6 d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-block"
                                wire:click="cambioTab('tab1')">Buscador</button>
                        </div>
                        <div class="ms-auto col-6 d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-block" disabled
                                wire:click="cambioTab('tab2')">Ver/Editar
                                trabajador</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="me-auto col-6 d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-block"
                                wire:click="cambioTab('tab3')">Añadir
                                trabajador</button>
                        </div>

                    </div>
                    <br>
                </div>
            </div>
            <br>

            @livewire('trabajadores.index-component')

        @elseif ($tab == 'tab3')
            <div style="border-bottom: 1px solid black !important;">
                <div class="row">
                    <div class="col-6 d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-block"
                            wire:click="cambioTab('tab1')">Buscador</button>
                    </div>
                    <div class="ms-auto col-6 d-grid gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-block"
                            wire:click="cambioTab('tab2')" disabled>Ver/Editar
                            trabajador</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="me-auto col-6 d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-block"
                            wire:click="cambioTab('tab3')">Añadir
                            trabajador</button>
                    </div>
                </div>
                <br>
            </div>
            <br>

            @livewire('trabajadores.create-component', key('tab3'))

            <br>
        @endif
    @endif
    @elsemobile
    @if ($user != null)
        @if ($tab == 'tab1')
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <button class="nav-link active" wire:click.prevent="cambioTab('tab1')">
                        <h3>Buscador</h3>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab2')">
                        <h5>Ver
                            movimiento</h5>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab3')">
                        <h5>Añadir
                            trabajador</h5>
                    </button>
                </li>
            </ul>
            <br>

            @livewire('trabajadores.index-component')
        @elseif ($tab == 'tab2')
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab1')">
                        <h5>Buscador</h5>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link active" wire:click.prevent="cambioTab('tab2')">
                        <h3>Ver/Editar
                            trabajador</h3>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab3')">
                        <h5>Añadir
                            trabajador</h5>
                    </button>
                </li>
            </ul>
            <br>

            @livewire('trabajadores.edit-component', ['identificador' => $user], key('tab2'))

            <br>
        @elseif ($tab == 'tab3')
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab1')">
                        <h5>Buscador</h5>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab2')">
                        <h5>Ver/Editar
                            trabajador</h5>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link active" wire:click.prevent="cambioTab('tab3')">
                        <h3>Añadir
                            trabajador</h3>
                    </button>
                </li>
            </ul>
            <br>

            @livewire('trabajadores.create-component', key('tab3'))
        @endif
    @else
        @if ($tab == 'tab1')
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <button class="nav-link active" wire:click.prevent="cambioTab('tab1')">
                        <h3>Buscador</h3>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab2')" disabled>
                        <h5>Ver/Editar
                            trabajador</h5>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab3')">
                        <h5>Añadir
                            trabajador</h5>
                    </button>
                </li>
            </ul>
            <br>

            @livewire('trabajadores.index-component')
        @elseif ($tab == 'tab3')
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab1')">
                        <h5>Buscador</h5>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" wire:click.prevent="cambioTab('tab2')" disabled>
                        <h5>Ver/Editar
                            trabajador</h5>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link active" wire:click.prevent="cambioTab('tab3')">
                        <h3>Añadir
                            trabajador</h3>
                    </button>
                </li>
            </ul>
            <br>

            @livewire('trabajadores.create-component', key('tab3'))

            <br>
        @endif
    @endif
@endmobile
</div>
