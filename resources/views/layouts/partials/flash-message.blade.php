@if (session()->has('flash_message'))

		<div class="flash-message alert alert-{!! session()->get('flash_message_level') !!}">

			<p>{!! session()->get('flash_message') !!}</p>
		</div>

@endif