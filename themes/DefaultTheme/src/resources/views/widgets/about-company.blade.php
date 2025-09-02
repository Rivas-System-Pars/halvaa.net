<div class="container AboutCompany-container pt-4 pt-lg-5" data-aos="fade-up" data-aos-duration="1000">
    <div class="Option_Card_Title mb-5">
        <div class="Option_Card_Right_Line"></div>
        <h2 class="Option_Card_Center_Line">{{$widget->option('title1')}}</h2>
        <div class="Option_Card_Left_Line"></div>
    </div>
    <div class="row align-items-center">
        <div class="col-md-6 order-md-2 position-relative mb-md-0 col-12 mx-1 mx-md-0" style="margin-bottom: -3rem !important;">
            <div class="AboutCompany-shadow-div"></div>
            <div class="AboutCompany-main-div" >
                <div class="AboutCompany-content-div">
                    <img class="AboutCompany-image" src="{{$widget->option('image')}}" alt="Image">
                    <svg class="AboutCompany-svg" width="115" height="115" viewBox="0 0 115 115" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M51.5582 78.4396C49.5457 78.4396 47.677 77.9604 45.9999 77.0021C42.1666 74.7979 39.9624 70.2937 39.9624 64.5916V50.4083C39.9624 44.7542 42.1666 40.2021 45.9999 37.9979C49.8332 35.7937 54.8166 36.1291 59.752 39.0041L72.0666 46.0958C76.9541 48.9229 79.7812 53.0916 79.7812 57.5C79.7812 61.9083 76.9541 66.0771 72.0666 68.9042L59.752 75.9958C56.9728 77.625 54.1457 78.4396 51.5582 78.4396ZM51.6062 43.7479C50.8395 43.7479 50.1687 43.8916 49.6416 44.2271C48.1082 45.1375 47.1978 47.3896 47.1978 50.4083V64.5916C47.1978 67.6104 48.0603 69.8625 49.6416 70.7729C51.1749 71.6833 53.5707 71.3 56.2062 69.7667L68.5207 62.675C71.1562 61.1416 72.6416 59.2729 72.6416 57.5C72.6416 55.7271 71.1562 53.8104 68.5207 52.325L56.2062 45.2333C54.4812 44.2271 52.8999 43.7479 51.6062 43.7479Z" fill="white"/>
                        <path d="M57.4999 109.01C29.0853 109.01 5.9895 85.9146 5.9895 57.5C5.9895 29.0855 29.0853 5.98962 57.4999 5.98962C85.9145 5.98962 109.01 29.0855 109.01 57.5C109.01 85.9146 85.9145 109.01 57.4999 109.01ZM57.4999 13.1771C33.0624 13.1771 13.177 33.0625 13.177 57.5C13.177 81.9375 33.0624 101.823 57.4999 101.823C81.9374 101.823 101.823 81.9375 101.823 57.5C101.823 33.0625 81.9374 13.1771 57.4999 13.1771Z" fill="white"/>
                    </svg>
					
                </div>
            </div>
        </div>
        <div class="col-md-6 order-md-1 text-right col-12 px-4 d-flex align-items-center justify-content-center">
            <div class="AboutCompany-text-container">
                <div class="AboutCompany-text-section">
                    <p>
                        {{$widget->option('title2')}}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelector('.AboutCompany-svg').addEventListener('click', function () {
            // مخفی کردن تصویر و SVG
        document.querySelector('.AboutCompany-image').classList.add('hidden');
        this.classList.add('hidden');
    
        const iframe = document.createElement('iframe');
        iframe.src = "{{$widget->option('video')}}"; // لینک ویدیو
        iframe.allow = "autoplay; encrypted-media";
        iframe.frameBorder = "0";
        iframe.allowFullscreen = true;
        
        const contentDiv = document.querySelector('.AboutCompany-content-div');
        contentDiv.appendChild(iframe);
        
        iframe.style.display = "block"; // ویدیو نمایش داده می‌شود
    });
        
</script>



{{-- <div class="modal fade AboutCompanyVideo" id="aboutCompanyVideoModal" tabindex="-1" aria-labelledby="aboutCompanyVideoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg w-75">
      <div class="modal-content">
        <div class="modal-body">
          <div class="embed-responsive">
            <iframe src="{{$widget->option('video')}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"></iframe>
          </div>
        </div>
      </div>
    </div>
</div> --}}
