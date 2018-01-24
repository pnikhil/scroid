<div class="footer" style="color:#e9edf2 !important";>
	&copy; {{$config['main']['siteName']}}
	@if(!empty($widgets['footer']))
		@foreach($widgets['footer'] as $widget)
			{{do_shortcode($widget['content'])}}
		@endforeach
	@endif

</div>