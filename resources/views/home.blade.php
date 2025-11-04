@extends('layouts.app')

@section('content')

<!-- Hero Section with Carousel -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000" data-bs-pause="false">
    <div class="carousel-inner">
        @for($i = 1; $i <= 9; $i++)
            <div class="carousel-item {{ $i === 1 ? 'active' : '' }}">
                <div class="hero-slide" style="background-image: url('/images/banner_{{ $i }}.jpg');"></div>
            </div>
        @endfor
    </div>

    <!-- Search -->
    <div class="search-overlay">
        <div class="search-box">
            <h4 class="fw-semibold mb-4 text-center">Tìm & đặt vé máy bay giá rẻ</h4>
            <form action="{{ route('flights.search') }}" method="GET">
                <div class="row g-3 justify-content-center align-items-end">
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Từ</label>
                        <input type="text" name="from" class="form-control" placeholder="Thành phố hoặc mã sân bay" value="{{ request('from') }}">
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Đến</label>
                        <input type="text" name="to" class="form-control" placeholder="Thành phố hoặc mã sân bay" value="{{ request('to') }}">
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Ngày khởi hành</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <button type="submit" class="btn btn-search w-100">
                            Tìm chuyến bay
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- Thông tin vé máy bay -->
<div class="container my-5">
    <h3 class="mb-4 text-left">Vé máy bay nội địa</h3>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
        @foreach($flights as $flight)
            <div class="col">
                <a href="{{ route('flights.detail', $flight->id ?? $flight->FlightID) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm flight-card">
                        <img src="{{ asset($flight->airline_logo) }}" class="card-img-top" alt="{{ $flight->airline_name }}">
                        <div class="card-body">
                            <h6 class="card-title text-dark fw-bold">
                                {{ $flight->from_city }} → {{ $flight->to_city }}
                            </h6>
                            <p class="card-text text-muted mb-1">
                                Ngày bay: {{ date('d/m/Y H:i', strtotime($flight->departure_time)) }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $flights->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection



@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const wrapper = document.querySelector('.card-wrapper');
    const cards = document.querySelectorAll('.flight-card');

    if (!wrapper || cards.length === 0) return;

    const card = cards[0];
    const style = getComputedStyle(card);
    const cardWidth = card.offsetWidth + parseInt(style.marginRight || 20);

    let isScrolling = false;

    // --- Cập nhật hiển thị nút ---
    function updateButtons() {
        const atStart = wrapper.scrollLeft <= 5;
        const atEnd = wrapper.scrollLeft + wrapper.clientWidth >= wrapper.scrollWidth - 5;

        prevBtn.style.opacity = atStart ? "0" : "1";
        prevBtn.style.pointerEvents = atStart ? "none" : "auto";

        nextBtn.style.opacity = atEnd ? "0" : "1";
        nextBtn.style.pointerEvents = atEnd ? "none" : "auto";
    }

    // --- Cuộn mượt với animation ---
    function smoothScroll(distance) {
        if (isScrolling) return;
        isScrolling = true;

        const start = wrapper.scrollLeft;
        const end = start + distance;
        const duration = 400;
        const startTime = performance.now();

        function animateScroll(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            wrapper.scrollLeft = start + (end - start) * easeInOutCubic(progress);

            if (progress < 1) {
                requestAnimationFrame(animateScroll);
            } else {
                isScrolling = false;
                updateButtons();
            }
        }

        requestAnimationFrame(animateScroll);
    }

    // --- Hàm easing cho chuyển động mượt ---
    function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }

    // --- Sự kiện click ---
    nextBtn.addEventListener('click', () => smoothScroll(cardWidth));
    prevBtn.addEventListener('click', () => smoothScroll(-cardWidth));

    // --- Theo dõi cuộn thủ công ---
    wrapper.addEventListener('scroll', () => {
        clearTimeout(wrapper._scrollTimer);
        wrapper._scrollTimer = setTimeout(updateButtons, 150);
    });

    // --- Khi resize ---
    window.addEventListener('resize', updateButtons);

    updateButtons();
});
</script>
@endpush
