@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('css')
    <style>
        .panel .mce-panel {
            border-left-color: #fff;
            border-right-color: #fff;
        }

        .panel .mce-toolbar,
        .panel .mce-statusbar {
            padding-left: 20px;
        }

        .panel .mce-edit-area,
        .panel .mce-edit-area iframe,
        .panel .mce-edit-area iframe html {
            padding: 0 10px;
            min-height: 350px;
        }

        .mce-content-body {
            color: #555;
            font-size: 14px;
        }

        .panel.is-fullscreen .mce-statusbar {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 200000;
        }

        .panel.is-fullscreen .mce-tinymce {
            height:100%;
        }

        .panel.is-fullscreen .mce-edit-area,
        .panel.is-fullscreen .mce-edit-area iframe,
        .panel.is-fullscreen .mce-edit-area iframe html {
            height: 100%;
            position: absolute;
            width: 99%;
            overflow-y: scroll;
            overflow-x: hidden;
            min-height: 100%;
        }
    </style>
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form" action="@if(isset($dataTypeContent->id)){{ route('voyager.pages.update', $dataTypeContent->id) }}@else{{ route('voyager.pages.store') }}@endif" method="POST" enctype="multipart/form-data">
            <!-- PUT Method if we are editing -->
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <!-- ### TITLE ### -->
                    <div class="panel">


                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="voyager-character"></i> Página
                                <span class="panel-desc"> Nome principal da página</span>
                            </h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            @include('voyager::multilingual.input-hidden', [
                                '_field_name'  => 'title',
                                '_field_trans' => get_field_translations($dataTypeContent, 'title')
                            ])
                            <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('voyager::generic.title') }}" value="{{ $dataTypeContent->title ?? '' }}">
                        </div>
                    </div>

                    <!-- ### CONTENT ### -->
                {{--                    <div class="panel">--}}
                {{--                        <div class="panel-heading">--}}
                {{--                            <h3 class="panel-title">{{ __('voyager::post.content') }}</h3>--}}
                {{--                            <div class="panel-actions">--}}
                {{--                                <a class="panel-action voyager-resize-full" data-toggle="panel-fullscreen" aria-hidden="true"></a>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}

                {{--                        <div class="panel-body">--}}
                {{--                            @include('voyager::multilingual.input-hidden', [--}}
                {{--                                '_field_name'  => 'body',--}}
                {{--                                '_field_trans' => get_field_translations($dataTypeContent, 'body')--}}
                {{--                            ])--}}
                {{--                            @php--}}
                {{--                                $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};--}}
                {{--                                $row = $dataTypeRows->where('field', 'body')->first();--}}
                {{--                            @endphp--}}
                {{--                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}--}}
                {{--                        </div>--}}
                {{--                    </div><!-- .panel -->--}}

                <!-- ### EXCERPT ### -->
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Descrição <small> uma pequena descrição que será usado para vinculo da página</small></h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            @include('voyager::multilingual.input-hidden', [
                                '_field_name'  => 'excerpt',
                                '_field_trans' => get_field_translations($dataTypeContent, 'excerpt')
                            ])
                            <textarea class="form-control" name="excerpt">{{ $dataTypeContent->excerpt ?? '' }}</textarea>
                        </div>
                    </div>
                    @php
                        $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
                        $exclude = ['page_category_id','title', 'body', 'excerpt', 'slug', 'status', 'category_id', 'author_id', 'featured', 'image', 'meta_description', 'meta_keywords', 'seo_title'];
                        $extraRows = [];
                        foreach($dataTypeRows as $dataRow)
                        {
                            if(!in_array($dataRow->field, $exclude))
                            {
                            array_push($extraRows,$dataRow);
                            }
                        }
                    @endphp
                    @if(sizeof($extraRows))
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">{{ __('voyager::post.additional_fields') }}</h3>
                                <div class="panel-actions">
                                    <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                                </div>
                            </div>
                            <div class="panel-body">


                                @foreach($dataTypeRows as $row)
                                    @if(!in_array($row->field, $exclude))
                                        @php
                                            $display_options = $row->details->display ?? NULL;
                                        @endphp
                                        @if (isset($row->details->formfields_custom))
                                            @include('voyager::formfields.custom.' . $row->details->formfields_custom)
                                        @else
                                            <div class="form-group @if($row->type == 'hidden') hidden @endif @if(isset($display_options->width)){{ 'col-md-' . $display_options->width }}@endif" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                                {{ $row->slugify }}
                                                <label for="name">{{ $row->display_name }}</label>
                                                @include('voyager::multilingual.input-hidden-bread-edit-add')
                                                @if($row->type == 'relationship')
                                                    @include('voyager::formfields.relationship', ['options' => $row->details])
                                                @else
                                                    {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                                @endif

                                                @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                                    {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <!-- ### DETAILS ### -->
                    <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="icon wb-clipboard"></i>Detalhes da Página</h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                @include('voyager::multilingual.input-hidden', [
                                    '_field_name'  => 'slug',
                                    '_field_trans' => get_field_translations($dataTypeContent, 'slug')
                                ])
                                <a href="{{url($dataTypeContent->categories_path('url').$dataTypeContent->slug)}}" target="_blank">
                                    <small>{{url($dataTypeContent->categories_path('url').$dataTypeContent->slug)}}</small>
                                </a>
                                <div style="display:flex;align-items: center;">
                                    @if($dataTypeContent->categories_path('array') != null and sizeof($dataTypeContent->categories_path('array')))
                                        @foreach($dataTypeContent->categories_path('array') as $slug)
                                            <div style="padding: 6px 12px;border-radius: 2px;background:#eeeeee;margin-right:3px;">
                                                {{$slug}}/
                                            </div>
                                        @endforeach
                                    @endif
                                    <input type="text" class="form-control" id="slug" name="slug"
                                           placeholder="slug"
                                           {!! isFieldSlugAutoGenerator($dataType, $dataTypeContent, "slug") !!}
                                           value="{{ $dataTypeContent->slug ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status">
                                    <option value="ACTIVE"@if(isset($dataTypeContent->status) && $dataTypeContent->status == 'ACTIVE') selected="selected"@endif>Ativo</option>
                                    <option value="INACTIVE"@if(isset($dataTypeContent->status) && $dataTypeContent->status == 'INACTIVE') selected="selected"@endif>Inativo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="page_category_id">Categoria da Página</label>
                                <select class="form-control" name="page_category_id">
                                    <option value="">Nenhum</option>
                                    @foreach(\Viaativa\Viaroot\Models\PageCategory::all() as $category)
                                        <option value="{{ $category->id }}"@if(isset($dataTypeContent->page_category_id) && $dataTypeContent->page_category_id == $category->id) selected="selected"@endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ### IMAGE ### -->
                    <div class="panel panel-bordered panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="icon wb-image"></i>Imagem da Página</h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            @if(isset($dataTypeContent->image))
                                <img src="{{ filter_var($dataTypeContent->image, FILTER_VALIDATE_URL) ? $dataTypeContent->image : Voyager::image( $dataTypeContent->image ) }}" style="width:100%" />
                            @endif
                            <input type="file" name="image">
                        </div>
                    </div>

                    <!-- ### SEO CONTENT ### -->
                    <div class="panel panel-bordered panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="icon wb-search"></i> {{ __('voyager::post.seo_content') }}</h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="meta_description">Meta description</label>
                                @include('voyager::multilingual.input-hidden', [
                                    '_field_name'  => 'meta_description',
                                    '_field_trans' => get_field_translations($dataTypeContent, 'meta_description')
                                ])
                                <textarea class="form-control" name="meta_description">{{ $dataTypeContent->meta_description ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right">
                @if(isset($dataTypeContent->id))Salvar página @else <i class="fas fa-save"></i> Salvar página @endif
            </button>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>
    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>
                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('#slug').slugify();

            @if ($isModelTranslatable)
            $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });
            $('.form-group').on('click', '.remove-multi-image', function (e) {
                e.preventDefault();
                $image = $(this).siblings('img');
                params = {
                    slug:   '{{ $dataType->slug }}',
                    image:  $image.data('image'),
                    id:     $image.data('id'),
                    field:  $image.parent().data('field-name'),
                    _token: '{{ csrf_token() }}'
                }
                $('.confirm_delete_name').text($image.data('image'));
                $('#confirm_delete_modal').modal('show');
            });
            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {
                        toastr.success(response.data.message);
                        $image.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing image.");
                    }
                });
                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
