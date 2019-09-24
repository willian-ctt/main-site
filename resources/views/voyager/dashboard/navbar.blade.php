<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="hamburger btn-link">
                <span class="hamburger-inner"></span>
            </button>
            @section('breadcrumbs')
                <ol class="breadcrumb hidden-xs">
                    @php
                        $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                        $url = route('voyager.dashboard');
                        $configs = config('breadcrumbs');
                    @endphp
                    @if(count($segments) == 0)
                        <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>
                    @else
                        <li class="active">
                            <a href="{{ route('voyager.dashboard')}}"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>
                        </li>
                        @foreach ($segments as $segment)
                            @if($configs != null and array_key_exists($segment,$configs) and $configs[$segment]['name'] == null)
                                @continue
                            @endif
                            @php
                                $url .= '/'.$segment;
                            @endphp
                            @if ($loop->last)
                                @if($configs != null and array_key_exists($segment,$configs))
                                    <li>@if(isset($configs[$segment]['prefix'])){{ $configs[$segment]['prefix'] }}@endif
                                        @if(isset($configs[$segment]['name'])){{ $configs[$segment]['name'] }}@else {{ ucfirst($segment) }} @endif
                                        @if(isset($configs[$segment]['suffix'])){{ $configs[$segment]['suffix'] }}@endif</li>
                                @else
                                    <li>{{ ucfirst($segment) }}</li>
                                @endif
                            @else
                                <li>
                                    @if($configs != null and array_key_exists($segment,$configs))
                                        <a href="{{ $url }}">
                                            @if(isset($configs[$segment]['prefix'])){{ $configs[$segment]['prefix'] }}@endif
                                            @if(isset($configs[$segment]['name'])){{ $configs[$segment]['name'] }}@else {{ ucfirst($segment) }} @endif
                                            @if(isset($configs[$segment]['suffix'])){{ $configs[$segment]['suffix'] }}@endif</a>
                                    @else
                                        <a href="{{ $url }}">{{ ucfirst($segment) }}</a>
                                    @endif

                                </li>
                            @endif

                        @endforeach
                    @endif
                </ol>
            @show
        </div>
        <ul class="nav navbar-nav @if (config('voyager.multilingual.rtl')) navbar-left @else navbar-right @endif">
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                   aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span
                            class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-animated">
                    <li class="profile-img">
                        <img src="{{ $user_avatar }}" class="profile-img">
                        <div class="profile-body">
                            <h5>{{ app('VoyagerAuth')->user()->name }}</h5>
                            <h6>{{ app('VoyagerAuth')->user()->email }}</h6>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>
                    @if(is_array($nav_items) && !empty($nav_items))
                        @foreach($nav_items as $name => $item)
                            <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                                @if(isset($item['route']) && $item['route'] == 'voyager.logout')
                                    <form action="{{ route('voyager.logout') }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger btn-block">
                                            @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                                <i class="{!! $item['icon_class'] !!}"></i>
                                            @endif
                                            {{__($name)}}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                                        @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                            <i class="{!! $item['icon_class'] !!}"></i>
                                        @endif
                                        {{__($name)}}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</nav>