<div class="card">
    <div class="card-header">
        <div>
            @if($editing && $form->product_id > 0)
            <h5>Editar Producto | <small class="text-info">{{$form->name}}</small>
            </h5>
            @else
            <h5>Crear Nuevo Producto</h5>
            @endif
        </div>

    </div>
    <div class="card-body">

        {{-- @if($errors !=null)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif --}}


        <div class="row g-xl-5 g-3">
            <div class="col-xxl-3 col-xl-4 box-col-4e sidebar-left-wrapper">
                {{-- tabs --}}
                <ul class="sidebar-left-icons nav nav-pills" id="add-product-pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{$tab == 1 ? 'active' : '' }}" wire:click.prevent="$set('tab',1)"
                            id="detail-product-tab" data-bs-toggle="pill" href="#detail-product" role="tab"
                            aria-controls="detail-product" aria-selected="false">
                            <div class="nav-rounded">
                                <div class="product-icons">
                                    <svg class="stroke-icon">
                                        <use href="../assets/svg/icon-sprite.svg#product-detail"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="product-tab-content">
                                <h6>Producto Generales</h6>
                                <p>Agregar detalles</p>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$tab == 2 ? 'active' : '' }}" wire:click.prevent="$set('tab',2)"
                            id="gallery-product-tab" data-bs-toggle="pill" href="#gallery-product" role="tab"
                            aria-controls="gallery-product" aria-selected="false">
                            <div class="nav-rounded">
                                <div class="product-icons">
                                    <svg class="stroke-icon">
                                        <use href="../assets/svg/icon-sprite.svg#product-gallery"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="product-tab-content">
                                <h6>Producto Galeria</h6>
                                <p>Imagenes asociadas</p>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$tab == 3 ? 'active' : '' }}" wire:click.prevent="$set('tab',3)"
                            id="category-product-tab" data-bs-toggle="pill" href="#category-product" role="tab"
                            aria-controls="category-product" aria-selected="false">
                            <div class="nav-rounded">
                                <div class="product-icons">
                                    <svg class="stroke-icon">
                                        <use href="../assets/svg/icon-sprite.svg#product-category"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="product-tab-content">
                                <h6>Producto Categorías</h6>
                                <p>Categoría y Proveedor</p>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$tab == 4 ? 'active' : '' }}" wire:click.prevent="$set('tab',4)"
                            id="pricings-tab" data-bs-toggle="pill" href="#pricings" role="tab" aria-controls="pricings"
                            aria-selected="false">
                            <div class="nav-rounded">
                                <div class="product-icons">
                                    <svg class="stroke-icon">
                                        <use href="../assets/svg/icon-sprite.svg#pricing"> </use>
                                    </svg>
                                </div>
                            </div>
                            <div class="product-tab-content">
                                <h6>Producto Precios</h6>
                                <p>Lista de precios</p>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="col-xxl-9 col-xl-8 box-col-8 position-relative">
                <div class="tab-content" id="add-product-pills-tabContent">
                    <div class="tab-pane fade {{$tab == 1 ? 'active show' : '' }}" id="detail-product" role="tabpanel"
                        aria-labelledby="detail-product-tab">
                        <div class="sidebar-body">
                            <form class="row g-2">
                                {{-- name --}}
                                <div class="col-sm-12 col-md-8">
                                    <label class="form-label">Nombre <span class="txt-danger">*</span></label>
                                    <input wire:model="form.name" class="form-control" type="text">
                                    @error('form.name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- sku --}}
                                <div class="col-sm-12 col-md-4">
                                    <label class="form-label">Sku</label>
                                    <input wire:model="form.sku" class="form-control" type="text">
                                </div>
                                {{-- description --}}
                                <div class="col-sm-12 mb-3">
                                    <div class="toolbar-box" wire:ignore>
                                        <div id="toolbar2"><span class="ql-formats">
                                                <select class="ql-size"></select></span><span class="ql-formats">
                                                <button class="ql-bold">Bold </button>
                                                <button class="ql-italic">Italic </button>
                                                <button class="ql-underline">underline</button>
                                                <button class="ql-strike">Strike </button></span><span
                                                class="ql-formats">
                                                <button class="ql-list" value="ordered">List </button>
                                                <button class="ql-list" value="bullet"> </button>
                                                <button class="ql-indent" value="-1"> </button>
                                                <button class="ql-indent" value="+1"></button></span><span
                                                class="ql-formats">
                                                <button class="ql-link"></button>
                                                <button class="ql-image"></button>
                                                <button class="ql-video"></button></span></div>
                                        <div id="editor2"></div>
                                    </div>
                                </div>
                                {{-- type --}}
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label">Tipo <span class="txt-danger">*</span></label>
                                    <select wire:model="form.type" class="form-select" required="">
                                        <option value="service">Servicio</option>
                                        <option value="physical">Producto Físico</option>
                                    </select>
                                    {{-- @error('type') <span class="text-danger">{{ $message }}</span>
                                    @enderror --}}
                                </div>
                                {{-- status --}}
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label">Estatus <span class="txt-danger">*</span></label>
                                    <select wire:model="form.status" class="form-select" required="">
                                        <option value="available" selected>Disponible</option>
                                        <option value="out_of_stock">Sin Stock</option>
                                    </select>
                                    {{-- @error('status') <span class="text-danger">{{ $message }}</span>
                                    @enderror --}}
                                </div>
                                {{-- cost --}}
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label">Costo de Compra</label>
                                    <input wire:model="form.cost" class="form-control numerico" type="number">
                                </div>
                                {{-- price --}}
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label">Precio de Venta</label>
                                    <input wire:model="form.price" class="form-control numerico" type="number">
                                </div>

                                {{-- manage stock --}}
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label">Administrar Stock <span
                                            class="txt-danger">*</span></label>
                                    <select wire:model="form.manage_stock" class="form-select">
                                        <option value="1" selected>Si, Controlar Stock</option>
                                        <option value="0">Vender sin Límites</option>
                                    </select>
                                </div>
                                {{-- stock qty --}}
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label">Stock Actual <span class="txt-danger">*</span></label>
                                    <input wire:model="form.stock_qty" class="form-control numerico" type="number">
                                </div>
                                {{-- low stock --}}
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label">Stock de Alerta <span class="txt-danger">*</span></label>
                                    <input wire:model="form.low_stock" class="form-control numerico" type="number">
                                </div>

                            </form>
                            <div class="mt-3">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade {{$tab == 2 ? 'active show' : '' }}" id="gallery-product" role="tabpanel"
                        aria-labelledby="gallery-product-tab">
                        <div class="sidebar-body">
                            <div class="product-upload">
                                <p>Galeria de Imagenes </p>
                                <form class="dropzone dropzone-light" id="multiFileUploadA" action="/upload.php">
                                    <div class="dz-message needsclick">
                                        <svg>
                                            <use href="../assets/svg/icon-sprite.svg#file-upload"></use>
                                        </svg>
                                        <span class="note needsclick">SVG,PNG,JPG or GIF </span>
                                    </div>
                                </form>
                            </div>
                            <input type="file" class="form-control" wire:model="form.gallery"
                                accept="image/x-png,image/jpeg" style="height:44px" multiple id="inputImg">
                            {{-- @error('gallery.*')
                            <span style="color: red;">{{ $message }}</span>
                            @enderror --}}

                            <div class="mt-2">
                                <div wire:loading wire:target="form.gallery">Cargando imágenes...</div>
                                @if (!empty($form->gallery))
                                <div class="row">
                                    @foreach ($form->gallery as $photo)
                                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                                        <div class="media">
                                            <img src="{{ $photo->temporaryUrl() }}" class="img-fluid rounded" alt="img"
                                                width="40%">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>


                        </div>
                    </div>
                    <div class="tab-pane fade {{$tab == 3 ? 'active show' : '' }}" id="category-product" role="tabpanel"
                        aria-labelledby="category-product-tab">
                        <div class="sidebar-body">
                            <form>
                                <div class="row g-lg-4 g-3">
                                    <div class="col-12">
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label m-0">Categoría <span
                                                                class="txt-danger">*</span></label>
                                                    </div>
                                                    <div class="col-12">
                                                        <select wire:model="form.category_id" class="form-select">
                                                            <option value="0" disabled>
                                                                Seleccionar</option>
                                                            @foreach ($categories as $category)
                                                            <option value="{{$category->id}}" {{$category->id
                                                                ==$form->category_id ? 'selected' : '' }}>{{
                                                                $category->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        @error('form.category_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="category-buton">
                                                    <a class="btn button-primary" href="#!"
                                                        wire:click="modal('category')">
                                                        <i class="me-2 fa fa-plus">
                                                        </i>Nueva Categoría </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label m-0">Proveedor <span
                                                                class="txt-danger">*</span></label>
                                                    </div>
                                                    <div class="col-12">
                                                        <select wire:model="form.supplier_id" class="form-select"
                                                            id="supplier">
                                                            <option value="0" disabled> Seleccionar</option>
                                                            @foreach($suppliers as $supplier)
                                                            <option value="{{$supplier->id}}" {{$supplier->id
                                                                == $form->supplier_id ? 'selected' : '' }}>
                                                                {{$supplier->name}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        @error('form.supplier_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="category-buton"><a class="btn button-primary" href="#!"
                                                        wire:click="modal('supplier')"><i class="me-2 fa fa-plus">
                                                        </i>Nuevo Proveedor </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade {{$tab == 4 ? 'active show' : '' }}" id="pricings" role="tabpanel"
                        aria-labelledby="pricings-tab">
                        <div class="sidebar-body">
                            <form class="price-wrapper">
                                <div class="row g-3 custom-input">
                                    <div class="col-sm-3">
                                        <label class="form-label" for="initialCost">Precio de Venta <span
                                                class="txt-danger">*</span></label>
                                        <input wire:model="form.value" class="form-control numerico" type="number">
                                    </div>
                                    <div class="col-sm-12 col-md-2">
                                        <button wire:click.prevent="storeTempPrice"
                                            class="btn btn-primary mt-4">Agregar</button>
                                    </div>
                                </div>
                                <div class="row g-3 mt-3">
                                    {{-- @json($form) --}}
                                    <div class="col-sm-12 col-md-4">
                                        <table class="table table-light">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Precio</th>
                                                    <th class="text-right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($form->values as $item)
                                                <tr>
                                                    {{-- <td>{{ $item }}</td> --}}
                                                    <td>${{ $item['price'] }}</td>
                                                    <td>
                                                        <button class="btn btn-light btn-sm"
                                                            wire:click.prevent="removeTempPrice('{{ $item['id'] }}')">
                                                            <i class="fa fa-trash fa-2x"></i>
                                                        </button>
                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <button wire:click.prevent="cancel" class="btn btn-light">
            Cancelar
        </button>

        @if($editing && $form->product_id == 0)
        <button wire:click.prevent="Store" class="btn btn-warning">
            Registrar Producto
        </button>
        @else
        <button wire:click.prevent="Update" class="btn btn-dark">
            Actualizar Producto
        </button>
        @endif
    </div>
</div>