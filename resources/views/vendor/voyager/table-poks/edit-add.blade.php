@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
   {{-- <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if (isset($row->details->view))
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                                 onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>--}}

   {{-- <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>--}}
    <!-- End Delete File Modal -->
   <form class="form-horizontal" action="{{--{{route('add.submenu')}}--}}" method="post">
       @csrf

       <div class="ibox-content">
           <div class="row">
               <div class="col-4">
                   <div class="form-group" id="pd">
                       <label class="col-lg-12 control-label">RKO Kegiatan</label>
                       <select class="form-control" name="rko_id" id="rko_id" required>
                           {{--@foreach($dropdown as $d)
                               <option value="{{$d->id}}">{{$d->nama_kegiatan}}</option>
                           @endforeach--}}
                       </select>
                   </div>
               </div>
               {{--<div class="col-4">
                   <div class="col-lg-12">
                       <div class="form-group"><label>Sub Kegiatan</label>
                           <input placeholder="Nama Kegiatan" name="nama_sub_keg" id="nama_sub_keg"
                                  class="form-control"> --}}{{--<span class="help-block m-b-none">Example block-level help text here.</span>--}}{{--
                       </div>
                   </div>
               </div>
               <div class="col-4">
                   <div class="col-lg-12">
                       <div class="form-group"><label>Jumlah Anggaran</label>
                           <input placeholder="Jumlah Anggaran" data-mask="#.##0" data-mask-reverse="true" name="jml_anggaran_sub" id="jml_anggaran_sub"
                                  class="form-control"> --}}{{--<span class="help-block m-b-none">Example block-level help text here.</span>--}}{{--
                       </div>
                   </div>
               </div>--}}
               {{--<div class="col-4">
                   <div class="col-lg-12">
                       <div class="form-group"><label>Bidang</label>
                           <select class="form-control" name="bidang" id="bidang">
                               @foreach($bidang as $b)
                                   <option value="{{$b->id}}">{{$b->name}}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>
               </div>--}}
           </div>
           <div class="row">
               <div class="col-12" >
                   <div class="form-group" ><label>Sub Kegiatan</label>
                       <div class="row" id="input-player-list">
                           <div class="col-4">
                               <input placeholder="Sub Kegiatan" name="nama_sub_keg[]" id="nama_sub_keg" class="form-control"
                                      required>
                           </div>
                           <div class="col-4">
                               <input placeholder="Jumlah Anggaran"  data-mask="#.##0" data-mask-reverse="true" name="jml_anggaran_sub[]" id="jml_anggaran_sub" class="form-control"
                                      required>
                           </div>
                           <div class="col-4">
                               <input placeholder="Target fisik"  data-mask="#.##0" data-mask-reverse="true" name="tager_sub[]" id="tager_sub" class="form-control"
                                      required>
                           </div>
                       </div>

                   </div>
                   <div class="col-lg-12">
                       <div class="ui-tooltip">
                           <button type='button' class="btn btn-danger btn-circle float-left"
                                   data-toggle="tooltip" data-placement="bottom" title="Hapus Sub Kegiatan"
                                   id='removeSubKeg'>
                               <i class="fa fa-minus"></i>
                           </button>
                           <button type='button' class="btn btn-info btn-circle float-right" id='addSubKeg'
                                   data-toggle="tooltip" data-placement="bottom" title="Tambah Sub Kegiatan">
                               <i class="fa fa-plus"></i>
                           </button>
                       </div>
                   </div>
               </div>
<<<<<<< HEAD
               {{$test}}
=======
               
>>>>>>> f1c3b0ebf12622ef7beb3679a6a34f0893347af6
               {{--<div class="col-4" id="input-player-lists">
                   <div class="form-group"><label>Jumlah Anggaran</label>
                       <input placeholder="Jumlah Anggaran"  data-mask="#.##0" data-mask-reverse="true" name="jml_anggaran_sub[]" id="jml_anggaran_sub" class="form-control"
                              required>
                   </div>
                   <div class="col-lg-12">
                       <div class="ui-tooltip">
                           <button type='button' class="btn btn-danger btn-circle float-left"
                                   data-toggle="tooltip" data-placement="bottom" title="Hapus Jumlah Anggaran"
                                   id='removePlayers'>
                               <i class="fa fa-minus"></i>
                           </button>
                           <button type='button' class="btn btn-info btn-circle float-right" id='addPlayers'
                                   data-toggle="tooltip" data-placement="bottom" title="Tambah Jumlah Anggaran">
                               <i class="fa fa-plus"></i>
                           </button>
                       </div>
                   </div>
               </div>--}}
           </div>
           <div class="ibox float-e-margins">
               <div class="ibox-content">
                   <div class="form-group">
                       <div class="col-lg-offset-0 col-lg-12">
                           <button class="btn btn-sm btn-white" type="submit" id="submit-keg">Submit</button>
                       </div>
                   </div>
               </div>
           </div>
       </div>


       <div class="space-15">
       </div>
   </form>

@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>
        document.getElementById('addSubKeg').onclick = function createInputField() {
            var input = document.createElement('input');
            var input2 = document.createElement('input');
            var input3 = document.createElement('input');
            var lineBreak = document.createElement('br');
            var testId = "nama_sub_keg";
            var i = 0;
            var x = document.getElementsByTagName('INPUT').length - 2;
            var col8 = document.createElement('div');
            col8.className ='col-4';
            var col4 = document.createElement('div');
            col4.className ='col-4';
            input.setAttribute('id', testId + i);
            input.className = 'form-control';
            input.name = 'nama_sub_keg[]';
            input.placeholder = 'Sub Kegiatan';
            input2.setAttribute('id', testId + i);
            input2.className = 'form-control';
            input2.name = 'jml_anggaran_sub[]';
            input2.placeholder = 'Anggaran';
            input3.setAttribute('id', testId + i);
            input3.className = 'form-control';
            input3.name = 'tager_sub[]';
            input3.placeholder = 'Target fisik';
            for (i = 0; i < x; i++) {
                i;
                var aplayer1 = document.getElementById('input-player-list');
                aplayer1.appendChild(col8);
                aplayer1.appendChild(input);
                aplayer1.appendChild(col4);
                aplayer1.appendChild(input2);
                aplayer1.appendChild(col4);
                aplayer1.appendChild(input3);
                aplayer1.appendChild(lineBreak);
            }
        }
        document.getElementById('removeSubKeg').onclick = function removeInputField() {
            var x = document.getElementsByTagName('INPUT').length;
            console.log(x);
            if ( x > 2 ) {
                $('#input-player-list input:last').remove();
                $('#input-player-list br:last').remove();
                return false;
            } else {
            }
        }
    </script>
@stop
