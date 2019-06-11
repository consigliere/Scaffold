<footer class="app-footer">
    <div class="site-footer-right">
        @if (rand(1,100) == 100)
            <i class="voyager-rum-1"></i> {{ __('voyager::theme.footer_copyright2') }}
        @else
            {!! __('voyager::theme.footer_copyright') !!}
            <a href="" target="_blank"><?php echo \Illuminate\Support\Facades\Config::get('scaffold.name')?></a>
        @endif
        @php $version = \Illuminate\Support\Facades\Config::get('scaffold.version'); @endphp
        @if (!empty($version))
            - {{ $version }}
        @endif
    </div>
</footer>
