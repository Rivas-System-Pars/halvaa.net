<div class="container Counseling-section-container mt-2" data-aos="fade-up" data-aos-duration="1000">
   <div class="Option_Card_Title mb-3 mb-md-4 mx-0 w-100">
        <div class="Option_Card_Right_Line"></div>
        <h3 class="Option_Card_Center_Line"> {{ $widget->option('title')}}</h3>
        <div class="Option_Card_Left_Line"></div>
</div>
	<div class="row mt-2 mb-5">
        <div class="col-2 d-flex justify-content-center align-items-center">
            <div class="Counseling-image-container">
                <img src="{{ theme_asset('images/MoshaverePattern.png') }}" alt="مشاوره و اجرای سامانه های تحت وب"
                    class="img-fluid Counseling-flipped-image Counseling-image">
            </div>
        </div>
        <div class="col-8 text-center mt-4">
			
           
            <div class="Counseling-buttons-container">
                <a href="{{$widget->option('right_link')}}"><button class="btn btn-primary Counseling-custom-button" style="font-size: 15px;font-weight: bold;">{{ $widget->option('right_title')}}</button></a>
                <a href="{{$widget->option('center_link')}}"><button class="btn btn-primary Counseling-custom-button" style="font-size: 14px;font-weight: bold;">{{ $widget->option('center_title')}}</button></a>
                <a href="{{$widget->option('left_link')}}"><button class="btn btn-primary Counseling-custom-button" style="font-size: 15px;font-weight: bold;">{{ $widget->option('left_title')}}</button></a>
            </div>
        </div>
        <div class="col-2 d-flex justify-content-center align-items-center">
            <div class="Counseling-image-container">
                <img src="{{ theme_asset('images/MoshaverePattern.png') }}" alt="{{ $widget->option('title')}}" class="img-fluid Counseling-image">
            </div>
        </div>
    </div>
</div>
