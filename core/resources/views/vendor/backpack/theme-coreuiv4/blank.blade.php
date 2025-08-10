@extends(backpack_view('layouts.top_left'))

@php
	// Merge widgets that were fluently declared with widgets declared without the fluent syntax:
	// - $data['widgets']['before_content']
	// - $data['widgets']['after_content']
	if (isset($widgets)) {
		foreach ($widgets as $section => $widgetSection) {
			foreach ($widgetSection as $key => $widget) {
				\Backpack\CRUD\app\Library\Widget::add($widget)->section($section);
			}
		}
	}
@endphp

@section('before_breadcrumbs_widgets')
	@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('section', 'before_breadcrumbs')->toArray() ])
@endsection

@section('after_breadcrumbs_widgets')
	@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('section', 'after_breadcrumbs')->toArray() ])
@endsection

@section('before_content_widgets')
	@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('section', 'before_content')->toArray() ])
@endsection

@section('content')
@endsection

@section('after_content_widgets')
	@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('section', 'after_content')->toArray() ])
@endsection
@push('after_scripts')
    <script>
        // Check if geolocation is supported by the browser
if ("geolocation" in navigator) {

      // Prompt user for permission to access their location
  navigator.geolocation.getCurrentPosition(
    // Success callback function
    (position) => {
      // Get the user's latitude and longitude coordinates
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;
      localStorage.setItem('lat',lat)
      localStorage.setItem('lng',lng)
      if($("#lat").length) $("#lat").val(lat)
      if($("#lng").length) $("#lng").val(lng)
      $("#office-file-menuitem").removeClass('d-none')
      $("#inspection-log-menuitem").removeClass('d-none')
    },
    // Error callback function
    (error) => {
      $("#office-file-menuitem").addClass('d-none')
      $("#inspection-log-menuitem").addClass('d-none')
      switch(error.code) {
        case error.PERMISSION_DENIED:
        alert('لطفا دسترسی موقعیت مکانی را فعال کنید. در غیر اینصورت قادر به استفاده از برنامه نخواهید بود')
          break;
        case error.POSITION_UNAVAILABLE:
          alert('موقعیت مکانی شما در دسترس نمی باشد')
          break;
        case error.TIMEOUT:
          alert('دریافت موقعیت مکانی به تایم اوت خورد')
          break;
        case error.UNKNOWN_ERROR:
          alert('خطایی در دریافت موقعیت مکانی شما رخ داد')
          break;
      }
    }
  );

} else {
  // Geolocation is not supported by the browser
  console.error("Geolocation is not supported by this browser.");
}


// // Check if geolocation is supported by the browser
// if ("geolocation" in navigator) {
//   // Prompt user for permission to access their location
//   navigator.geolocation.watchPosition(
//     // Success callback function
//     function(position) {
//       // Get the user's latitude and longitude coordinates
//       const lat = position.coords.latitude;
//       const lng = position.coords.longitude;

//       // Update the map with the user's new location
//       console.log(`Latitude: ${lat}, longitude: ${lng}`);
//     },
//     // Error callback function
//     function(error) {
//       // Handle errors, e.g. user denied location sharing permissions
//       console.error("Error getting user location:", error);
//     }
//   );
// } else {
//   // Geolocation is not supported by the browser
//   console.error("Geolocation is not supported by this browser.");
// }
    </script>
@endpush
