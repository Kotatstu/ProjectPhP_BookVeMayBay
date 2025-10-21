@extends('layouts.app')

@section('content')

<!-- Hero Section with Carousel -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000" data-bs-pause="false">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="hero-slide" style="background-image: url('/images/banner_1.jpg');"></div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/images/banner_2.jpg');"></div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/images/banner_3.jpg');"></div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/images/banner_4.jpg');"></div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/images/banner_5.jpg');"></div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/images/banner_6.jpg');"></div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/images/banner_7.jpg');"></div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/images/banner_8.jpg');"></div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image: url('/images/banner_9.jpg');"></div>
        </div>
    </div>

<!-- Search -->
<div class="search-overlay">
    <div class="search-box p-4 rounded shadow-lg bg-white">
        <h4 class="fw-semibold mb-3">Tìm & đặt vé máy bay giá rẻ</h4>
        <form>
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label class="form-label">Từ</label>
                    <input type="text" class="form-control" placeholder="From...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến</label>
                    <input type="text" class="form-control" placeholder="To...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ngày khởi hành</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                     <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-search w-100">Tìm chuyến bay</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Thông tin vé máy bay -->
<div class="card-container my-3">
        <h3 class="mb-3">Vé máy bay nội địa</h3>
        <div class="d-flex align-items-center">
            <button class="btn btn-light me-2 prev-btn" style="display: none;">&#8592;</button>
            <div class="card-wrapper">
                @foreach($flights as $flight)
                    <a href="{{ route('flights.detail', $flight->id) }}" class="text-decoration-none">
                        <div class="flight-card card">
                            <img src="{{ $flight->airline_logo }}" class="card-img-top" alt="{{ $flight->airline_name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $flight->from }} → {{ $flight->to }}</h5>
                                <p class="card-text">Ngày bay: {{ $flight->departure_date }}</p>
                                <p class="card-text fw-bold">{{ number_format($flight->fare) }} VND</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <button class="btn btn-light ms-2 next-btn">&#8594;</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const wrapper = document.querySelector('.card-wrapper');
    const nextBtn = document.querySelector('.next-btn');
    const prevBtn = document.querySelector('.prev-btn');
    const cards = document.querySelectorAll('.flight-card');

    if(cards.length === 0) return;

    // Tính tổng width 1 card bao gồm margin
    const card = cards[0];
    const style = getComputedStyle(card);
    const cardWidth = card.offsetWidth + parseInt(style.marginRight);

    // Cập nhật trạng thái nút
    function updateButtons() {
        prevBtn.style.display = wrapper.scrollLeft > 0 ? 'inline-block' : 'none';
        nextBtn.style.display = wrapper.scrollLeft + wrapper.clientWidth >= wrapper.scrollWidth - 1 ? 'none' : 'inline-block';
    }

    updateButtons();

    nextBtn.addEventListener('click', function() {
        wrapper.scrollLeft += cardWidth;
        setTimeout(updateButtons, 200); // cập nhật sau khi scroll
    });

    prevBtn.addEventListener('click', function() {
        wrapper.scrollLeft -= cardWidth;
        setTimeout(updateButtons, 200);
    });

    // Optional: khi resize window
    window.addEventListener('resize', updateButtons);
});

</script>
@endpush